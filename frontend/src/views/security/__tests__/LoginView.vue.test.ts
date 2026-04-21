import { describe, expect, test, vi } from 'vitest'
import Component from '@/views/security/LoginView.vue'
import { mount } from '@vue/test-utils'
import { createTestingPinia } from '@pinia/testing'
import { useSecurityStore } from '@/stores/security'
import { createRouter, createMemoryHistory } from 'vue-router'

const stubs = ['font-awesome-icon']

function createTestRouter() {
  return createRouter({
    history: createMemoryHistory(),
    routes: [{ path: '/', component: { template: '<div/>' } }]
  })
}

describe('LoginView.vue', () => {
  test('shows login form by default', () => {
    const wrapper = mount(Component, {
      global: {
        plugins: [createTestingPinia({ createSpy: vi.fn, stubActions: true }), createTestRouter()],
        stubs
      }
    })

    expect(wrapper.find('input[placeholder="Username"]').exists()).toBeTruthy()
    expect(wrapper.find('input[type="password"]').exists()).toBeTruthy()
    expect(wrapper.find('input[placeholder="Code"]').exists()).toBeFalsy()
  })

  test('shows 2FA form when twoFactorAuthRequired is true', () => {
    const wrapper = mount(Component, {
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: { security: { twoFactorAuthRequired: true } }
          }),
          createTestRouter()
        ],
        stubs
      }
    })

    expect(wrapper.find('input[placeholder="Code"]').exists()).toBeTruthy()
    expect(wrapper.find('input[placeholder="Username"]').exists()).toBeFalsy()
  })

  test('does not call login when fields are empty', async () => {
    const wrapper = mount(Component, {
      global: {
        plugins: [createTestingPinia({ createSpy: vi.fn, stubActions: true }), createTestRouter()],
        stubs
      }
    })

    await wrapper.find('button').trigger('click')

    const store = useSecurityStore()
    expect(store.login).not.toHaveBeenCalled()
  })

  test('calls login with credentials and redirects', async () => {
    const router = createTestRouter()
    const pushSpy = vi.spyOn(router, 'push')

    const wrapper = mount(Component, {
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: { security: { twoFactorAuthRequired: false } }
          }),
          router
        ],
        stubs
      }
    })

    await wrapper.find('input[placeholder="Username"]').setValue('admin')
    await wrapper.find('input[type="password"]').setValue('secret')
    await wrapper.find('button').trigger('click')
    await wrapper.vm.$nextTick()

    const store = useSecurityStore()
    expect(store.login).toHaveBeenCalledWith('admin', 'secret')
    expect(pushSpy).toHaveBeenCalledWith({ path: '/' })
  })

  test('triggers connect on Enter key', async () => {
    const wrapper = mount(Component, {
      global: {
        plugins: [createTestingPinia({ createSpy: vi.fn, stubActions: true }), createTestRouter()],
        stubs
      }
    })

    await wrapper.find('input[placeholder="Username"]').setValue('admin')
    await wrapper.find('input[type="password"]').setValue('secret')
    await wrapper.find('input[placeholder="Username"]').trigger('keyup.enter')
    await wrapper.vm.$nextTick()

    const store = useSecurityStore()
    expect(store.login).toHaveBeenCalledWith('admin', 'secret')
  })

  test('does not call twoFactorAuth when code is empty', async () => {
    const wrapper = mount(Component, {
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: { security: { twoFactorAuthRequired: true } }
          }),
          createTestRouter()
        ],
        stubs
      }
    })

    await wrapper.find('button').trigger('click')

    const store = useSecurityStore()
    expect(store.twoFactorAuth).not.toHaveBeenCalled()
  })

  test('calls twoFactorAuth with code and redirects', async () => {
    const router = createTestRouter()
    const pushSpy = vi.spyOn(router, 'push')

    const wrapper = mount(Component, {
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: { security: { twoFactorAuthRequired: true } }
          }),
          router
        ],
        stubs
      }
    })

    await wrapper.find('input[placeholder="Code"]').setValue('123456')
    await wrapper.find('button').trigger('click')
    await wrapper.vm.$nextTick()

    const store = useSecurityStore()
    expect(store.twoFactorAuth).toHaveBeenCalledWith('123456')
    expect(pushSpy).toHaveBeenCalledWith({ path: '/' })
  })

  test('triggers twoFactorAuth on Enter key', async () => {
    const wrapper = mount(Component, {
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: { security: { twoFactorAuthRequired: true } }
          }),
          createTestRouter()
        ],
        stubs
      }
    })

    await wrapper.find('input[placeholder="Code"]').setValue('123456')
    await wrapper.find('input[placeholder="Code"]').trigger('keyup.enter')
    await wrapper.vm.$nextTick()

    const store = useSecurityStore()
    expect(store.twoFactorAuth).toHaveBeenCalledWith('123456')
  })
})
