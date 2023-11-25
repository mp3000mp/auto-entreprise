import { describe, expect, test } from 'vitest'
import Component from '@/views/MenuView.vue'
import { mount } from '@vue/test-utils'
import { createTestingPinia } from '@pinia/testing'
import { vi } from 'vitest'

vi.mock('vue-router')
const stubs = ['router-link']

describe('MenuView.vue', () => {
  test('does not show links', () => {
    const wrapper = mount(Component, {
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            initialState: {
              security: {
                currentUser: null
              }
            }
          })
        ],
        stubs
      }
    })
    const links = wrapper.findAll('.nav-item')
    expect(links.length).toBe(0)
  })

  test('does not show links', () => {
    const wrapper = mount(Component, {
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
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
    const links = wrapper.findAll('.nav-item')
    expect(links.length).toBe(9)
  })
})
