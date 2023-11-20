import { expect, test } from 'vitest'
import Component from '@/components/Mp3000Icon.vue'
import { mount } from '@vue/test-utils'

const stubs = ['font-awesome-icon']

test('Mp3000Icon.vue is loading', () => {
    const wrapper = mount(Component, {
        props: {
            icon: 'test',
            isLoading: true,
        },
        global: {
            stubs,
        }
    })
    expect(wrapper.findAll('.spinner-border').length).toBe(1)
    expect(wrapper.findAll('font-awesome-icon-stub').length).toBe(0)
})

test('Mp3000Icon.vue is disabled', async () => {
    const wrapper = mount(Component, {
        props: {
            icon: 'test',
            disabled: true,
        },
        global: {
            stubs,
        }
    })
    expect(wrapper.findAll('.spinner-border').length).toBe(0)
    expect(wrapper.findAll('font-awesome-icon-stub').length).toBe(1)

    await wrapper.find('a').trigger('click')
    expect(wrapper.emitted()['click']?.length ?? 0).toBe(0)
})

test('Mp3000Icon.vue show label', async () => {
    const wrapper = mount(Component, {
        props: {
            icon: 'test',
            isLoading: false,
        },
        global: {
            stubs,
        }
    })
    expect(wrapper.findAll('.spinner-border').length).toBe(0)
    expect(wrapper.findAll('font-awesome-icon-stub').length).toBe(1)

    await wrapper.find('a').trigger('click')
    expect(wrapper.emitted()['click'].length).toBe(1)
})

test('Mp3000Icon.vue handle confirm message cancel', async () => {
    const wrapper = mount(Component, {
        props: {
            icon: 'test',
            isLoading: false,
            confirmMessage: 'confirm message',
        },
        global: {
            stubs,
        }
    })
    await wrapper.find('a').trigger('click')
    expect(wrapper.emitted()['click']?.length ?? 0).toBe(0)

    expect(wrapper.find('p').text()).toBe('confirm message')
    const buttons = wrapper.findAll('.btn')
    expect(buttons.length).toBe(2)

    await buttons[0].trigger('click')
    expect(wrapper.emitted()['click']?.length ?? 0).toBe(0)
})

test('Mp3000Icon.vue handle confirm message confirm', async () => {
    const wrapper = mount(Component, {
        props: {
            icon: 'test',
            isLoading: false,
            confirmMessage: 'confirm message',
        },
        global: {
            stubs,
        }
    })
    await wrapper.find('a').trigger('click')
    expect(wrapper.emitted()['click']?.length ?? 0).toBe(0)

    expect(wrapper.find('p').text()).toBe('confirm message')
    const buttons = wrapper.findAll('.btn')
    expect(buttons.length).toBe(2)

    await buttons[1].trigger('click')
    expect(wrapper.emitted()['click'].length).toBe(1)
})
