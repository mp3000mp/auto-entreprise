import { describe, expect, test } from 'vitest'
import Component from '@/views/tenders/TenderView.vue'
import { mount } from '@vue/test-utils'
import { createTestingPinia } from '@pinia/testing'
import { vi } from 'vitest'
import { initTender, initEmptyTender } from '@tests/data/tender'
import { useTenderStore } from '@/stores/tender'
import { useRouter } from 'vue-router'

vi.mock('vue-router')
const stubs = ['font-awesome-icon', 'router-link']

describe('TenderView.vue', () => {
  test('triggers edit event', async () => {
    const wrapper = mount(Component, {
      props: {
        tenderId: 1
      },
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {
              tender: {
                currentTender: initTender()
              }
            }
          })
        ],
        stubs
      }
    })
    expect(wrapper.findAll('font-awesome-icon-stub[icon="fa,pen-to-square"]').length).toBe(4) // 3 tender rows
    expect(wrapper.findAll('font-awesome-icon-stub[icon="fa,trash"]').length).toBe(3)

    await wrapper.find('font-awesome-icon-stub[icon="fa,pen-to-square"]').trigger('click')
    expect(wrapper.find('.modal.show').exists()).toBeTruthy()
  })

  test('triggers tender row edit event', async () => {
    const wrapper = mount(Component, {
      props: {
        tenderId: 1
      },
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {
              tender: {
                currentTender: initTender()
              }
            }
          })
        ],
        stubs
      }
    })
    expect(wrapper.findAll('font-awesome-icon-stub[icon="fa,pen-to-square"]').length).toBe(4) // 3 tender rows
    expect(wrapper.findAll('font-awesome-icon-stub[icon="fa,trash"]').length).toBe(3)

    await wrapper.findAll('font-awesome-icon-stub[icon="fa,pen-to-square"]')[1].trigger('click')
    expect(wrapper.find('.modal.show').exists()).toBeTruthy()
  })

  test('triggers remove event', async () => {
    const routerMock = {
      push: vi.fn()
    }
    useRouter.mockReturnValue(routerMock)
    const wrapper = mount(Component, {
      props: {
        tenderId: 1
      },
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {
              tender: {
                currentTender: initEmptyTender()
              }
            }
          })
        ],
        stubs
      }
    })
    expect(wrapper.findAll('font-awesome-icon-stub[icon="fa,pen-to-square"]').length).toBe(1) // 3 tender rows
    expect(wrapper.findAll('font-awesome-icon-stub[icon="fa,trash"]').length).toBe(1)

    await wrapper.find('font-awesome-icon-stub[icon="fa,trash"]').trigger('click')
    const modal = wrapper.find('.modal.show')
    expect(modal.exists()).toBeTruthy()

    const store = useTenderStore()
    const buttons = modal.findAll('button')
    await buttons[1].trigger('click')
    expect(store.delete).toHaveBeenCalledTimes(1)
    expect(store.delete).toHaveBeenLastCalledWith(1)
    expect(routerMock.push).toHaveBeenCalledTimes(1)
    expect(routerMock.push).toHaveBeenLastCalledWith({ name: 'tenders' })
  })

  test('triggers remove tender row event', async () => {
    const routerMock = {
      push: vi.fn()
    }
    useRouter.mockReturnValue(routerMock)
    const wrapper = mount(Component, {
      props: {
        tenderId: 1
      },
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {
              tender: {
                currentTender: initTender()
              }
            }
          })
        ],
        stubs
      }
    })
    expect(wrapper.findAll('font-awesome-icon-stub[icon="fa,pen-to-square"]').length).toBe(4) // 3 tender rows
    expect(wrapper.findAll('font-awesome-icon-stub[icon="fa,trash"]').length).toBe(3)

    await wrapper.find('font-awesome-icon-stub[icon="fa,trash"]').trigger('click')
    const modal = wrapper.find('.modal.show')
    expect(modal.exists()).toBeTruthy()

    const store = useTenderStore()
    const buttons = modal.findAll('button')
    await buttons[1].trigger('click')
    expect(store.deleteTenderRow).toHaveBeenCalledTimes(1)
    expect(store.deleteTenderRow).toHaveBeenLastCalledWith(1)
    expect(routerMock.push).toHaveBeenCalledTimes(0)
  })
})
