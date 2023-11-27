import { describe, expect, test } from 'vitest'
import Component from '@/views/tenders/tenderRows/tenderRowForm.vue'
import { mount } from '@vue/test-utils'
import { createTestingPinia } from '@pinia/testing'
import { vi } from 'vitest'
import Mp3000Button from '@/components/Mp3000Button.vue'
import { useTenderStore } from '@/stores/tender'

const stubs = ['font-awesome-icon']

describe('TenderRowForm.vue', () => {
  test('triggers submit event', async () => {
    const wrapper = mount(Component, {
      props: {
        tenderRow: null,
        tender: { id: 1 },
        isShowing: true
      },
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {}
          })
        ],
        stubs
      }
    })
    const submit = wrapper.findAllComponents(Mp3000Button)[1]

    await submit.trigger('click')
    expect(wrapper.find('.text-danger').text()).toEqual('Titre non valide')

    await wrapper.findAll('input[type=text]')[0].setValue('title1')
    await submit.trigger('click')
    expect(wrapper.find('.text-danger').text()).toEqual('Description non valide')

    await wrapper.findAll('input[type=text]')[1].setValue('desc1')
    await submit.trigger('click')
    expect(wrapper.find('.text-danger').text()).toEqual('')

    const store = useTenderStore()
    expect(store.addTenderRow).toHaveBeenCalledTimes(1)
    const arg = store.addTenderRow.mock.calls[0][0]
    expect(arg).toEqual({
      tender: { id: 1 },
      position: 10,
      title: 'title1',
      description: 'desc1',
      soldDays: 0
    })
  })
})
