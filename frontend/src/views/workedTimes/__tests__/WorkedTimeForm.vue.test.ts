import { describe, expect, test } from 'vitest'
import Component from '@/views/workedTimes/WorkedTimeForm.vue'
import { mount } from '@vue/test-utils'
import { createTestingPinia } from '@pinia/testing'
import { vi } from 'vitest'
import Mp3000Button from '@/components/Mp3000Button.vue'
import { useWorkedTimeStore } from '@/stores/workedTime'
import dayjs from '../../../misc/dayjs'
import { setMidnight } from '../../../../tests/utils/dayjs'

const stubs = ['font-awesome-icon']

describe('WorkedTimeForm.vue', () => {
  test('triggers submit event', async () => {
    const wrapper = mount(Component, {
      props: {
        workedTime: null,
        opportunity: { id: 1 },
        isShowing: true
      },
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {
              security: {
                currentUser: { id: 1 }
              }
            }
          })
        ],
        stubs
      }
    })
    const submit = wrapper.findAllComponents(Mp3000Button)[1]

    await submit.trigger('click')
    expect(wrapper.find('.text-danger').text()).toEqual('Temps non valide')

    await wrapper.findAll('input[type=number]')[0].setValue(1.5)
    await submit.trigger('click')
    expect(wrapper.find('.text-danger').text()).toEqual('')

    const store = useWorkedTimeStore()
    expect(store.add).toHaveBeenCalledTimes(1)
    const arg = store.add.mock.calls[0][0]
    expect(setMidnight(arg, 'date')).toEqual({
      opportunity: { id: 1 },
      user: { id: 1 },
      date: dayjs().startOf('day'),
      workedDays: 1.5
    })
  })
})
