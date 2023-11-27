import { describe, expect, test } from 'vitest'
import Component from '@/views/opportunities/OpportunitiesView.vue'
import { mount } from '@vue/test-utils'
import { createTestingPinia } from '@pinia/testing'
import { vi } from 'vitest'
import { getRowIds, testSorter } from '@tests/utils/mp3000Table'
import OpportunityRow from '@/views/opportunities/OpportunityRow.vue'
import { initOpportunities, initOpportunityStatuses } from '@tests/data/opportunity'
import { initCompanies } from '@tests/data/company'

const stubs = ['font-awesome-icon', 'router-link']

describe('OpportunitiesView.vue', () => {
  test('sorts opportunities', async () => {
    const wrapper = mount(Component, {
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {
              opportunity: {
                opportunities: initOpportunities(false),
                statuses: initOpportunityStatuses()
              }
            }
          })
        ],
        stubs
      }
    })

    // initial sorted by company name
    await wrapper.vm.$nextTick()
    await wrapper.vm.$nextTick()
    await wrapper.vm.$nextTick()
    expect(getRowIds(wrapper, OpportunityRow, 'opportunity')).toEqual([3, 2, 1])

    await testSorter(
      wrapper,
      [
        { columnName: 'Ref', columnIdx: 0, expectedIdsOrder: [1, 2, 3] },
        { columnName: 'Client', columnIdx: 1, expectedIdsOrder: [1, 2, 3] },
        { columnName: 'Statut', columnIdx: 2, expectedIdsOrder: [1, 2, 3] },
        { columnName: 'Montant', columnIdx: 3, expectedIdsOrder: [2, 1, 3] },
        { columnName: 'Jours vendus', columnIdx: 4, expectedIdsOrder: [2, 1, 3] },
        { columnName: 'Jours travaillés', columnIdx: 5, expectedIdsOrder: [3, 1, 2] },
        { columnName: 'Création', columnIdx: 6, expectedIdsOrder: [1, 2, 3] }
      ],
      OpportunityRow,
      'opportunity'
    )
  })

  test('filters opportunities', async () => {
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
              opportunity: {
                opportunities: initOpportunities(),
                statuses: initOpportunityStatuses()
              }
            }
          })
        ],
        stubs
      }
    })
    expect(getRowIds(wrapper, OpportunityRow, 'opportunity').length).toBe(3)

    // text
    await wrapper.find('input').setValue('opp3')
    await wrapper.vm.$nextTick()
    expect(getRowIds(wrapper, OpportunityRow, 'opportunity')).toEqual([3])

    // reset
    await wrapper.find('input').setValue('')
    await wrapper.vm.$nextTick()
    expect(getRowIds(wrapper, OpportunityRow, 'opportunity')).toEqual([3, 2, 1])

    // company
    await wrapper.find('.form-select').setValue('1')
    await wrapper.vm.$nextTick()
    expect(getRowIds(wrapper, OpportunityRow, 'opportunity')).toEqual([1])
  })

  test('opens new form', async () => {
    const wrapper = mount(Component, {
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: false,
            initialState: {
              opportunity: {
                statuses: initOpportunityStatuses()
              }
            }
          })
        ],
        stubs
      }
    })
    wrapper.find('button').trigger('click')
    await wrapper.vm.$nextTick()
    expect(wrapper.find('.modal.show').exists()).toBeTruthy()
  })
})
