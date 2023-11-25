import { describe, expect, test } from 'vitest'
import Component from '@/components/Mp3000Button.vue'
import { mount } from '@vue/test-utils'

describe('Mp3000Button.vue', () => {
  test('is loading', () => {
    const wrapper = mount(Component, {
      props: {
        label: 'label',
        isLoading: true
      }
    })
    expect(wrapper.find('button').isDisabled()).toBeTruthy()
    expect(wrapper.text()).toBe('')
  })

  test('is disabled', () => {
    const wrapper = mount(Component, {
      props: {
        label: 'label',
        disabled: true
      }
    })
    expect(wrapper.find('button').isDisabled()).toBeTruthy()
    expect(wrapper.text()).toBe('label')
  })

  test('show label', () => {
    const wrapper = mount(Component, {
      props: {
        label: 'label',
        isLoading: false
      }
    })
    expect(wrapper.find('button').isDisabled()).toBeFalsy()
    expect(wrapper.text()).toBe('label')
  })
})
