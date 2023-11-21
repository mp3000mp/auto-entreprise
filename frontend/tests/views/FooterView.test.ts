import { describe, expect, test } from 'vitest'
import Component from '@/views/FooterView.vue'
import { mount } from '@vue/test-utils'

describe('FooterView.vue', () => {
    test('renders', () => {
        const wrapper = mount(Component)
        expect(wrapper.exists()).toBeTruthy()
    })
})
