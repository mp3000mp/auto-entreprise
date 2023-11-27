import { describe, expect, test } from 'vitest'
import Component from '@/views/tenders/TenderForm.vue'
import { mount } from '@vue/test-utils'
import { createTestingPinia } from '@pinia/testing'
import { vi } from 'vitest'
import Mp3000Button from '@/components/Mp3000Button.vue'
import { useTenderStore } from '@/stores/tender'
import { initTenderStatuses } from '@tests/data/tender'

const stubs = ['font-awesome-icon']

describe('TenderForm.vue', () => {
  test('triggers submit event', async () => {
    const fakeOpportunity = { id: 1, name: 'opp1', lastTender: { id: 1, version: 3 } }
    const statuses = initTenderStatuses()
    const wrapper = mount(Component, {
      props: {
        tender: null,
        opportunity: fakeOpportunity,
        isShowing: true
      },
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {
              tender: {
                statuses
              }
            }
          })
        ],
        stubs
      }
    })
    const submit = wrapper.findAllComponents(Mp3000Button)[1]

    await submit.trigger('click')
    expect(wrapper.find('.text-danger').text()).toEqual('TJM non valide')

    await wrapper.findAll('input[type=number]')[0].setValue(150)
    await submit.trigger('click')
    expect(wrapper.find('.text-danger').text()).toEqual('')

    // todo date status vs dates consistency

    const store = useTenderStore()
    expect(store.add).toHaveBeenCalledTimes(1)
    const arg = store.add.mock.calls[0][0]
    expect(arg).toEqual({
      version: 4,
      averageDailyRate: 150,
      opportunity: fakeOpportunity,
      status: statuses[0],
      sentAt: null,
      acceptedAt: null,
      refusedAt: null,
      canceledAt: null,
      comments: ''
    })
  })
})
