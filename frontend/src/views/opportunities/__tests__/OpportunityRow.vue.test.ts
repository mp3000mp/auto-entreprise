import { describe, expect, test } from 'vitest'
import Component from '@/views/opportunitys/OpportunityRow.vue'
import { mount } from '@vue/test-utils'
import { createTestingPinia } from '@pinia/testing'
import { vi } from 'vitest'
import { useOpportunityStore } from '@/stores/opportunity'
import { initOpportunitys } from '@tests/data/opportunity'

const stubs = ['font-awesome-icon', 'router-link']

describe('OpportunityRow.vue', () => {
  test('triggers events', async () => {
    const wrapper = mount(Component, {
      props: {
        opportunity: initOpportunitys()[0],
        isDeletable: false
      },
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: false,
            initialState: {}
          })
        ],
        stubs
      }
    })
    expect(wrapper.findAll('font-awesome-icon-stub').length).toBe(1)

    await wrapper.find('font-awesome-icon-stub[icon="fa,pen-to-square"]').trigger('click')
    expect(wrapper.emitted()['show-form'].length).toBe(1)
  })

  test('triggers remove events', async () => {
    const wrapper = mount(Component, {
      props: {
        opportunity: initOpportunitys()[0],
        isDeletable: true
      },
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: false,
            initialState: {}
          })
        ],
        stubs
      }
    })
    expect(wrapper.findAll('font-awesome-icon-stub').length).toBe(3)

    await wrapper.find('font-awesome-icon-stub[icon="fa,trash"]').trigger('click')
    const modal = wrapper.find('.modal.show')
    expect(modal.exists()).toBeTruthy()

    const store = useOpportunityStore()
    const buttons = modal.findAll('button')
    await buttons[1].trigger('click')
    expect(store.delete).toHaveBeenCalledTimes(1)
    expect(store.delete).toHaveBeenLastCalledWith(1)
  })
})
