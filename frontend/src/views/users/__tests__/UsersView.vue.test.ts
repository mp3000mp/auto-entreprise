import { describe, expect, test } from 'vitest'
import Component from '@/views/users/UsersView.vue'
import { mount } from '@vue/test-utils'
import { createTestingPinia } from '@pinia/testing'
import { vi } from 'vitest'
import { getRowIds, testSorter } from '@tests/utils/mp3000Table'
import UserRow from '@/views/users/UserRow.vue'
import { initUsers } from '@tests/data/user'

const stubs = ['font-awesome-icon', 'router-link']

describe('UsersView.vue', () => {
  test('sorts users', async () => {
    const wrapper = mount(Component, {
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {
              user: {
                users: initUsers()
              }
            }
          })
        ],
        stubs
      }
    })

    await testSorter(
      wrapper,
      [
        { columnName: '#', columnIdx: 0, expectedIdsOrder: [1, 2, 3] },
        { columnName: 'Email', columnIdx: 1, expectedIdsOrder: [1, 3, 2] },
        { columnName: 'Username', columnIdx: 2, expectedIdsOrder: [2, 3, 1] },
        { columnName: 'Roles', columnIdx: 3, expectedIdsOrder: [1, 3, 2] }
      ],
      UserRow,
      'user'
    )
  })
})

describe('UsersView.vue', () => {
  test('filters users', async () => {
    const wrapper = mount(Component, {
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {
              user: {
                users: initUsers()
              }
            }
          })
        ],
        stubs
      }
    })
    expect(getRowIds(wrapper, UserRow, 'user').length).toBe(3)

    await wrapper.find('input').setValue('duck')
    await wrapper.vm.$nextTick()

    expect(getRowIds(wrapper, UserRow, 'user')).toEqual([1])
  })
})
