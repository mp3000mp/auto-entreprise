import { describe, expect, test } from 'vitest'
import Component from '@/views/opportunities/OpportunityView.vue'
import { mount } from '@vue/test-utils'
import { createTestingPinia } from '@pinia/testing'
import { vi } from 'vitest'
import { initOpportunity, initEmptyOpportunity } from '@tests/data/opportunity'
import { useOpportunityStore } from '@/stores/opportunity'
import { useTenderStore } from '@/stores/tender'
import { useRouter } from 'vue-router'
import { initTenderStatuses } from '../../../../tests/data/tender'

vi.mock('vue-router')
const stubs = ['font-awesome-icon', 'router-link']

describe('OpportunityView.vue', () => {
  test('triggers edit event', async () => {
    const wrapper = mount(Component, {
      props: {
        opportunityId: 1
      },
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {
              opportunity: {
                currentOpportunity: initOpportunity()
              },
              tender: {
                deletableIds: [1, 2, 3]
              }
            }
          })
        ],
        stubs
      }
    })
    expect(wrapper.findAll('font-awesome-icon-stub[icon="fa,pen-to-square"]').length).toBe(4) // 3 tenders
    expect(wrapper.findAll('font-awesome-icon-stub[icon="fa,trash"]').length).toBe(3) // 3 tenders

    await wrapper.find('font-awesome-icon-stub[icon="fa,pen-to-square"]').trigger('click')
    expect(wrapper.find('.modal.show').exists()).toBeTruthy()
  })

  test('triggers edit tender event', async () => {
    const tenderStatuses = initTenderStatuses()
    const wrapper = mount(Component, {
      props: {
        opportunityId: 1
      },
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {
              opportunity: {
                currentOpportunity: initOpportunity()
              },
              tender: {
                statuses: tenderStatuses,
                deletableIds: [1, 2, 3]
              }
            }
          })
        ],
        stubs
      }
    })
    expect(wrapper.findAll('font-awesome-icon-stub[icon="fa,pen-to-square"]').length).toBe(4) // 3 tenders
    expect(wrapper.findAll('font-awesome-icon-stub[icon="fa,trash"]').length).toBe(3) // 3 tenders

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
        opportunityId: 1
      },
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {
              opportunity: {
                currentOpportunity: initEmptyOpportunity()
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

    const store = useOpportunityStore()
    const buttons = modal.findAll('button')
    await buttons[1].trigger('click')
    expect(store.delete).toHaveBeenCalledTimes(1)
    expect(store.delete).toHaveBeenLastCalledWith(1)
    expect(routerMock.push).toHaveBeenCalledTimes(1)
    expect(routerMock.push).toHaveBeenLastCalledWith({ name: 'opportunities' })
  })

  test('triggers remove tender event', async () => {
    const routerMock = {
      push: vi.fn()
    }
    useRouter.mockReturnValue(routerMock)
    const wrapper = mount(Component, {
      props: {
        opportunityId: 1
      },
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {
              opportunity: {
                currentOpportunity: initOpportunity()
              },
              tender: {
                deletableIds: [1, 2, 3]
              }
            }
          })
        ],
        stubs
      }
    })
    expect(wrapper.findAll('font-awesome-icon-stub[icon="fa,pen-to-square"]').length).toBe(4) // 3 tenders
    expect(wrapper.findAll('font-awesome-icon-stub[icon="fa,trash"]').length).toBe(3) // 3 tenders

    await wrapper.find('font-awesome-icon-stub[icon="fa,trash"]').trigger('click')
    const modal = wrapper.find('.modal.show')
    expect(modal.exists()).toBeTruthy()

    const store = useTenderStore()
    const buttons = modal.findAll('button')
    await buttons[1].trigger('click')
    expect(store.delete).toHaveBeenCalledTimes(1)
    expect(store.delete).toHaveBeenLastCalledWith(1)
    expect(routerMock.push).toHaveBeenCalledTimes(0)
  })
})
