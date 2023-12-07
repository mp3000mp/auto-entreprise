import { describe, expect, test } from 'vitest'
import Component from '@/views/tenders/TendersView.vue'
import { mount } from '@vue/test-utils'
import { createTestingPinia } from '@pinia/testing'
import { vi } from 'vitest'
import { getRowIds, testSorter } from '@tests/utils/mp3000Table'
import TenderRow from '@/views/tenders/TenderRow.vue'
import { initTenders, initTenderStatuses } from '@tests/data/tender'
import { initCompanies } from '@tests/data/company'

const stubs = ['font-awesome-icon', 'router-link']

describe('TendersView.vue', () => {
  test('sorts tenders', async () => {
    const wrapper = mount(Component, {
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {
              tender: {
                tenders: initTenders(false),
                statuses: initTenderStatuses()
              }
            }
          })
        ],
        stubs
      }
    })

    // initial sorted by company name
    await wrapper.vm.$nextTick() // todo waitUntil
    await wrapper.vm.$nextTick()
    await wrapper.vm.$nextTick()
    expect(getRowIds(wrapper, TenderRow, 'tender')).toEqual([3, 2, 1])

    await testSorter(
      wrapper,
      [
        { columnName: 'Version', columnIdx: 0, expectedIdsOrder: [1, 2, 3] },
        { columnName: 'Opportunité', columnIdx: 1, expectedIdsOrder: [1, 2, 3] },
        { columnName: 'Client', columnIdx: 2, expectedIdsOrder: [1, 2, 3] },
        { columnName: 'Statut', columnIdx: 3, expectedIdsOrder: [1, 2, 3] },
        { columnName: 'Montant', columnIdx: 4, expectedIdsOrder: [2, 1, 3] },
        { columnName: 'Jours vendus', columnIdx: 5, expectedIdsOrder: [2, 1, 3] },
        { columnName: 'Date création', columnIdx: 6, expectedIdsOrder: [1, 2, 3] }
      ],
      TenderRow,
      'tender'
    )
  })

  test('filters tenders', async () => {
    const wrapper = mount(Component, {
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {
              company: {
                companies: initCompanies()
              },
              tender: {
                tenders: initTenders(false),
                statuses: initTenderStatuses()
              }
            }
          })
        ],
        stubs
      }
    })
    expect(getRowIds(wrapper, TenderRow, 'tender').length).toBe(3)

    // text
    await wrapper.find('input').setValue('opp3')
    await wrapper.vm.$nextTick()
    expect(getRowIds(wrapper, TenderRow, 'tender')).toEqual([3])

    // reset
    await wrapper.find('input').setValue('')
    await wrapper.vm.$nextTick()
    expect(getRowIds(wrapper, TenderRow, 'tender')).toEqual([3, 2, 1])

    // company
    await wrapper.find('.form-select').setValue('1')
    await wrapper.vm.$nextTick()
    expect(getRowIds(wrapper, TenderRow, 'tender')).toEqual([1])
  })
})
