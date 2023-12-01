import { describe, expect, test } from 'vitest'
import Component from '@/views/workedTimes/WorkedTimeRow.vue'
import { mount } from '@vue/test-utils'
import { createTestingPinia } from '@pinia/testing'
import { vi } from 'vitest'
import { useWorkedTimeStore } from '@/stores/workedTime'
import { initWorkedTimes } from '@tests/data/workedTime'

const stubs = ['font-awesome-icon', 'router-link']

describe('WorkedTimeRow.vue', () => {
  test('triggers events', async () => {
    const wrapper = mount(Component, {
      props: {
        workedTime: initWorkedTimes()[0],
        averageDailyRate: 100
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
        workedTime: initWorkedTimes()[0],
        averageDailyRate: 100
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

    const store = useWorkedTimeStore()
    const buttons = modal.findAll('button')
    await buttons[1].trigger('click')
    expect(store.delete).toHaveBeenCalledTimes(1)
    expect(store.delete).toHaveBeenLastCalledWith(1)
  })
})
