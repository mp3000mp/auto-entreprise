import { describe, expect, test } from 'vitest'
import Component from '@/components/BootstrapLoader.vue'
import { mount } from '@vue/test-utils'

describe('BootstrapLoader.vue', () => {
    test('renders', () => {
        const wrapper = mount(Component)
        expect(wrapper.exists()).toBeTruthy()
    })
})
