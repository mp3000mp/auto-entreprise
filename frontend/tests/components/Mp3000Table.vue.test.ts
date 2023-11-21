import { describe, expect, test } from 'vitest'
import Component from '@/components/Mp3000Table.vue'
import { mount } from '@vue/test-utils'

describe('Mp3000Table.vue', () => {
    test('is loading', () => {
        const wrapper = mount(Component, {
            props: {
                isLoading: true,
            },
        })
        expect(wrapper.findAll('.spinner-border').length).toBe(1)
    })

    test('is shown', () => {
        const wrapper = mount(Component, {
            props: {
                isLoading: false,
            },
        })
        expect(wrapper.findAll('.spinner-border').length).toBe(0)
    })
})
