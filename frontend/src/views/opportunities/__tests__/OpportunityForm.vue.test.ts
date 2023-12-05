import { describe, expect, test } from 'vitest'
import Component from '@/views/opportunities/OpportunityForm.vue'
import { mount } from '@vue/test-utils'
import { createTestingPinia } from '@pinia/testing'
import { vi } from 'vitest'
import Mp3000Button from '@/components/Mp3000Button.vue'
import { useOpportunityStore } from '@/stores/opportunity'
import { initMeanOfPayments, initOpportunityStatuses } from '@tests/data/opportunity'
import { initCompanies } from '@tests/data/company'
import dayjs from '@/misc/dayjs'
import { setMidnight } from '@tests/utils/dayjs'

const stubs = ['font-awesome-icon']

describe('OpportunityForm.vue', () => {
  test('triggers submit event', async () => {
    const statuses = initOpportunityStatuses()
    const meanOfPayments = initMeanOfPayments()
    const companies = initCompanies()
    const wrapper = mount(Component, {
      props: {
        opportunity: null,
        isShowing: true
      },
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {
              company: {
                companies
              },
              opportunity: {
                statuses,
                meanOfPayments
              }
            }
          })
        ],
        stubs
      }
    })
    const submit = wrapper.findAllComponents(Mp3000Button)[1]

    await submit.trigger('click')
    expect(wrapper.find('.text-danger').text()).toEqual('Ref non valide')

    await wrapper.findAll('input[type=text]')[0].setValue('ref1')
    await submit.trigger('click')
    expect(wrapper.find('.text-danger').text()).toEqual('Description non valide')

    await wrapper.findAll('input[type=text]')[1].setValue('desc1')
    await submit.trigger('click')
    expect(wrapper.find('.text-danger').text()).toEqual('Client non valide')

    await wrapper.find('.form-select').setValue('2')
    await submit.trigger('click')
    expect(wrapper.find('.text-danger').text()).toEqual('')

    // todo date status vs dates consistency

    const store = useOpportunityStore()
    expect(store.add).toHaveBeenCalledTimes(1)
    const arg = store.add.mock.calls[0][0]
    expect(setMidnight(arg, 'trackedAt')).toEqual({
      ref: 'ref1',
      description: 'desc1',
      company: { id: 2, name: '' },
      status: statuses[0],
      meanOfPayment: { id: null },
      trackedAt: dayjs().startOf('day'),
      purchasedAt: null,
      forecastedDelivery: null,
      deliveredAt: null,
      billedAt: null,
      payedAt: null,
      canceledAt: null,
      customerRef1: null,
      customerRef2: null,
      paymentRef: null,
      comments: null
    })
  })
})
