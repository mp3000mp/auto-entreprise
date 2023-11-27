import { describe, expect, test } from 'vitest'
import Component from '@/views/costs/CostRow.vue'
import { mount } from '@vue/test-utils'
import { createTestingPinia } from '@pinia/testing'
import { vi } from 'vitest'
import { useCostStore } from '@/stores/cost'
import { initCost } from '@tests/data/cost'

const stubs = ['font-awesome-icon']

describe('CostRow.vue', () => {
  test('triggers edit events', async () => {
    const wrapper = mount(Component, {
      props: {
        cost: initCost()
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
    expect(wrapper.findAll('font-awesome-icon-stub').length).toBe(2)

    await wrapper.find('font-awesome-icon-stub[icon="fa,pen-to-square"]').trigger('click')
    expect(wrapper.emitted()['show-form'].length).toBe(1)
  })

  test('triggers remove events', async () => {
    const wrapper = mount(Component, {
      props: {
        cost: initCost()
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
    expect(wrapper.findAll('font-awesome-icon-stub').length).toBe(2)

    await wrapper.find('font-awesome-icon-stub[icon="fa,trash"]').trigger('click')
    const modal = wrapper.find('.modal.show')
    expect(modal.exists()).toBeTruthy()

    const store = useCostStore()
    const buttons = modal.findAll('button')
    await buttons[1].trigger('click')
    expect(store.deleteCost).toHaveBeenCalledTimes(1)
    expect(store.deleteCost).toHaveBeenLastCalledWith(1)
  })
})
