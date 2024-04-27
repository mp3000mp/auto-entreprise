import { describe, expect, test } from 'vitest'
import Component from '@/views/costs/CostForm.vue'
import { mount } from '@vue/test-utils'
import dayjs from 'dayjs'
import { createTestingPinia } from '@pinia/testing'
import { vi } from 'vitest'
import Mp3000Button from '@/components/Mp3000Button.vue'
import { useCostStore } from '@/stores/cost'
import { setMidnight } from '@tests/utils/dayjs'
import { initCostTypes } from '@tests/data/cost'

const stubs = ['font-awesome-icon']

describe('CostForm.vue', () => {
  test('triggers submit event', async () => {
    const wrapper = mount(Component, {
      props: {
        cost: null,
        isShowing: true
      },
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: false,
            initialState: {
              cost: {
                costTypes: initCostTypes()
              }
            }
          })
        ],
        stubs
      }
    })
    const submit = wrapper.findAllComponents(Mp3000Button)[1].find('button')

    await submit.trigger('click')
    expect(wrapper.find('.text-danger').text()).toEqual('Type non valide')

    await wrapper.find('.form-select').setValue('2')
    await submit.trigger('click')
    expect(wrapper.find('.text-danger').text()).toEqual('')

    const store = useCostStore()
    expect(store.addCost).toHaveBeenCalledTimes(1)
    const arg = store.addCost.mock.calls[0][0]
    expect(setMidnight(arg, 'date')).toEqual({
      amount: 0,
      date: dayjs().startOf('day'),
      description: '',
      type: { id: 2, label: '' }
    })
  })
})
