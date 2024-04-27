import { describe, expect, test } from 'vitest'
import Component from '@/components/Mp3000Button.vue'
import { mount } from '@vue/test-utils'

const stubs = ['font-awesome-icon']

describe('Mp3000Button.vue', () => {
  test('is loading', () => {
    const wrapper = mount(Component, {
      props: {
        label: 'label',
        isLoading: true
      }
    })
    expect(wrapper.find('button').attributes('disabled')).toBeDefined()
    expect(wrapper.text()).toBe('')
  })

  test('is disabled', () => {
    const wrapper = mount(Component, {
      props: {
        label: 'label',
        disabled: true
      }
    })
    expect(wrapper.find('button').attributes('disabled')).toBeDefined()
    expect(wrapper.text()).toBe('label')
  })

  test('show label', () => {
    const wrapper = mount(Component, {
      props: {
        label: 'label',
        isLoading: false
      }
    })
    expect(wrapper.find('button').attributes('disabled')).toBeUndefined()
    expect(wrapper.text()).toBe('label')
  })

  test('emit click event', async () => {
    const wrapper = mount(Component, {
      props: {
        label: 'label',
        isLoading: false
      }
    })
    await wrapper.find('button').trigger('click')
    expect(wrapper.emitted()['click'].length).toBe(1)
  })

  test('handles confirm message cancel', async () => {
    const wrapper = mount(Component, {
      props: {
        label: 'label',
        isLoading: false,
        confirmConfig: {
          title: 'confirm title',
          message: 'confirm message'
        }
      },
      global: {
        stubs
      }
    })
    await wrapper.find('button').trigger('click')
    expect(wrapper.emitted()['click']?.length ?? 0).toBe(0)

    expect(wrapper.find('p').text()).toBe('confirm message')

    const buttons = wrapper.findAll('.btn')
    expect(buttons.length).toBe(3)

    await buttons[1].trigger('click')
    expect(wrapper.emitted()['click']?.length ?? 0).toBe(0)
  })

  test('handles confirm message confirm', async () => {
    const wrapper = mount(Component, {
      props: {
        label: 'label',
        isLoading: false,
        confirmConfig: {
          title: 'confirm title',
          message: 'confirm message'
        }
      },
      global: {
        stubs
      }
    })
    await wrapper.find('button').trigger('click')
    expect(wrapper.emitted()['click']?.length ?? 0).toBe(0)

    expect(wrapper.find('p').text()).toBe('confirm message')

    const buttons = wrapper.findAll('.btn')
    expect(buttons.length).toBe(3)

    await buttons[2].trigger('click')
    expect(wrapper.emitted()['click'].length).toBe(1)
  })
})
