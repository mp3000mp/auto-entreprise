import { describe, expect, test } from 'vitest'
import Component from '@/views/contacts/ContactsView.vue'
import { mount } from '@vue/test-utils'
import { createTestingPinia } from '@pinia/testing'
import { vi } from 'vitest'
import { getRowIds, testSorter } from '@tests/utils/mp3000Table'
import ContactRow from '@/views/contacts/ContactRow.vue'
import { initContacts } from '@tests/data/contact'
import { initCompanies } from '@tests/data/company'

const stubs = ['font-awesome-icon', 'router-link']

describe('ContactsView.vue', () => {
  test('sorts contacts', async () => {
    const wrapper = mount(Component, {
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {
              contact: {
                contacts: initContacts()
              }
            }
          })
        ],
        stubs
      }
    })

    // initial sorted by company name
    expect(getRowIds(wrapper, ContactRow, 'contact')).toEqual([1, 2, 3])

    await testSorter(
      wrapper,
      [
        { columnName: 'Nom', columnIdx: 0, expectedIdsOrder: [1, 3, 2] },
        { columnName: 'Client', columnIdx: 1, expectedIdsOrder: [1, 2, 3] },
        { columnName: 'Email', columnIdx: 2, expectedIdsOrder: [2, 3, 1] },
        { columnName: 'Téléphone', columnIdx: 3, expectedIdsOrder: [1, 3, 2] }
      ],
      ContactRow,
      'contact'
    )
  })
})

describe('ContactsView.vue', () => {
  test('filters contacts', async () => {
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
              contact: {
                contacts: initContacts()
              }
            }
          })
        ],
        stubs
      }
    })
    expect(getRowIds(wrapper, ContactRow, 'contact').length).toBe(3)

    // text
    await wrapper.find('input').setValue('Silva')
    await wrapper.vm.$nextTick()
    expect(getRowIds(wrapper, ContactRow, 'contact')).toEqual([2])

    // reset
    await wrapper.find('input').setValue('')
    await wrapper.vm.$nextTick()
    expect(getRowIds(wrapper, ContactRow, 'contact')).toEqual([1, 2, 3])

    // company
    await wrapper.find('.form-select').setValue('2')
    await wrapper.vm.$nextTick()
    expect(getRowIds(wrapper, ContactRow, 'contact')).toEqual([2])
  })
})

describe('ContactsView.vue', () => {
  test('opens new form', async () => {
    const wrapper = mount(Component, {
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
    wrapper.find('button').trigger('click')
    await wrapper.vm.$nextTick()
    expect(wrapper.find('.modal.show').exists()).toBeTruthy()
  })
})
