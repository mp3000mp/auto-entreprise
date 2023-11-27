import { describe, expect, test } from 'vitest'
import Component from '@/views/contacts/ContactView.vue'
import { mount } from '@vue/test-utils'
import { createTestingPinia } from '@pinia/testing'
import { vi } from 'vitest'
import { initContact, initEmptyContact } from '@tests/data/contact'
import { useContactStore } from '@/stores/contact'
import { useOpportunityStore } from '@/stores/opportunity'
import { useRouter } from 'vue-router'

vi.mock('vue-router')
const stubs = ['font-awesome-icon', 'router-link']

describe('ContactView.vue', () => {
  test('triggers edit event', async () => {
    const wrapper = mount(Component, {
      props: {
        contactId: 1
      },
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {
              contact: {
                currentContact: initContact()
              }
            }
          })
        ],
        stubs
      }
    })
    expect(wrapper.findAll('font-awesome-icon-stub[icon="fa,pen-to-square"]').length).toBe(4) // 3 opportunities
    expect(wrapper.findAll('font-awesome-icon-stub[icon="fa,trash"]').length).toBe(0)

    await wrapper.find('font-awesome-icon-stub[icon="fa,pen-to-square"]').trigger('click')
    expect(wrapper.find('.modal.show').exists()).toBeTruthy()
  })

  test('triggers edit opportunity event', async () => {
    const wrapper = mount(Component, {
      props: {
        contactId: 1
      },
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {
              contact: {
                currentContact: initContact()
              }
            }
          })
        ],
        stubs
      }
    })
    expect(wrapper.findAll('font-awesome-icon-stub[icon="fa,pen-to-square"]').length).toBe(4) // 3 opportunities
    expect(wrapper.findAll('font-awesome-icon-stub[icon="fa,trash"]').length).toBe(0)

    await wrapper.findAll('font-awesome-icon-stub[icon="fa,pen-to-square"]')[0].trigger('click')
    expect(wrapper.find('.modal.show').exists()).toBeTruthy()
  })

  test('triggers remove event', async () => {
    const routerMock = {
      push: vi.fn()
    }
    useRouter.mockReturnValue(routerMock)
    const wrapper = mount(Component, {
      props: {
        contactId: 1
      },
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {
              contact: {
                currentContact: initEmptyContact()
              }
            }
          })
        ],
        stubs
      }
    })
    expect(wrapper.findAll('font-awesome-icon-stub[icon="fa,pen-to-square"]').length).toBe(1)
    expect(wrapper.findAll('font-awesome-icon-stub[icon="fa,trash"]').length).toBe(1)

    await wrapper.find('font-awesome-icon-stub[icon="fa,trash"]').trigger('click')
    const modal = wrapper.find('.modal.show')
    expect(modal.exists()).toBeTruthy()

    const store = useContactStore()
    const buttons = modal.findAll('button')
    await buttons[1].trigger('click')
    expect(store.delete).toHaveBeenCalledTimes(1)
    expect(store.delete).toHaveBeenLastCalledWith(1)
    expect(routerMock.push).toHaveBeenCalledTimes(1)
    expect(routerMock.push).toHaveBeenLastCalledWith({ name: 'contacts' })
  })

  test('triggers remove opportunity event', async () => {
    const routerMock = {
      push: vi.fn()
    }
    useRouter.mockReturnValue(routerMock)
    const wrapper = mount(Component, {
      props: {
        contactId: 1
      },
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {
              contact: {
                currentContact: initContact()
              },
              opportunity: {
                deletableIds: [1, 2, 3]
              }
            }
          })
        ],
        stubs
      }
    })
    expect(wrapper.findAll('font-awesome-icon-stub[icon="fa,pen-to-square"]').length).toBe(4) // 3 opportunities
    expect(wrapper.findAll('font-awesome-icon-stub[icon="fa,trash"]').length).toBe(3)

    await wrapper.find('font-awesome-icon-stub[icon="fa,trash"]').trigger('click')
    const modal = wrapper.find('.modal.show')
    expect(modal.exists()).toBeTruthy()

    const store = useOpportunityStore()
    const buttons = modal.findAll('button')
    await buttons[1].trigger('click')
    expect(store.delete).toHaveBeenCalledTimes(1)
    expect(store.delete).toHaveBeenLastCalledWith(1)
    expect(routerMock.push).toHaveBeenCalledTimes(0)
  })
})
