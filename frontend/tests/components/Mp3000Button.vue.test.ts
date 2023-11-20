import { expect, test } from 'vitest'
import Component from '@/components/Mp3000Button.vue'
import { mount } from '@vue/test-utils'

test('Mp3000Button.vue is loading', () => {
    const wrapper = mount(Component, {
        props: {
            label: 'label',
            isLoading: true,
        },
    })
    expect(wrapper.find('button').isDisabled()).toBeTruthy()
    expect(wrapper.text()).toBe('')
})

test('Mp3000Button.vue is disabled', () => {
    const wrapper = mount(Component, {
        props: {
            label: 'label',
            disabled: true,
        },
    })
    expect(wrapper.find('button').isDisabled()).toBeTruthy()
    expect(wrapper.text()).toBe('label')
})

test('Mp3000Button.vue show label', () => {
    const wrapper = mount(Component, {
        props: {
            label: 'label',
            isLoading: false,
        },
    })
    expect(wrapper.find('button').isDisabled()).toBeFalsy()
    expect(wrapper.text()).toBe('label')
})
