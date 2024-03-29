import { describe, expect, test } from 'vitest'
import Component from '@/components/BootstrapModal.vue'
import { mount } from '@vue/test-utils'

const stubs = ['font-awesome-icon']

describe('BootstrapModal.vue', () => {
  test('is not showing', () => {
    const wrapper = mount(Component, {
      props: {
        isShowing: false
      },
      global: {
        stubs
      }
    })
    expect(wrapper.find('.modal').exists()).toBeFalsy()
  })

  test('is loading', () => {
    const wrapper = mount(Component, {
      props: {
        isShowing: true,
        isLoading: true
      },
      global: {
        stubs
      }
    })
    expect(wrapper.find('.modal').classes().includes('hidden')).toBeFalsy()
    expect(wrapper.find('.modal').classes().includes('show')).toBeTruthy()
    expect(wrapper.findAll('.spinner-border').length).toBe(1)
  })

  test('is showing', () => {
    const wrapper = mount(Component, {
      props: {
        isShowing: true
      },
      global: {
        stubs
      }
    })
    expect(wrapper.find('.modal').classes().includes('hidden')).toBeFalsy()
    expect(wrapper.find('.modal').classes().includes('show')).toBeTruthy()
  })

  test('close icon', async () => {
    const wrapper = mount(Component, {
      props: {
        isShowing: true
      },
      global: {
        stubs
      }
    })
    await wrapper.find('font-awesome-icon-stub').trigger('click')
    expect(wrapper.emitted()['stop-showing'].length).toBe(1)
    expect(wrapper.emitted()['stop-showing'][0]).toEqual([])
  })

  // todo test keydown escape
})
