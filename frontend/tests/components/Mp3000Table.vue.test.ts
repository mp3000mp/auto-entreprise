import { expect, test } from 'vitest'
import Component from '@/components/Mp3000Table.vue'
import { mount } from '@vue/test-utils'

test('Mp3000Table.vue is loading', () => {
    const wrapper = mount(Component, {
        props: {
            isLoading: true,
        },
    })
    expect(wrapper.findAll('.spinner-border').length).toBe(1)
    expect(wrapper.findAll('.table-responsive').length).toBe(0)
})

test('Mp3000Table.vue is shown', () => {
    const wrapper = mount(Component, {
        props: {
            isLoading: false,
        },
    })
    expect(wrapper.findAll('.spinner-border').length).toBe(0)
    expect(wrapper.findAll('.table-responsive').length).toBe(1)
})
