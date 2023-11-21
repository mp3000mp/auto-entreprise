import { describe, expect, test } from 'vitest'
import Component from '@/views/costs/CostRow.vue'
import { mount } from '@vue/test-utils'
import dayjs from "dayjs";
import {createTestingPinia} from "@pinia/testing";
import {vi} from "vitest";
import {useCostStore} from "@/stores/cost";

const stubs = ['font-awesome-icon']

describe('CostRow.vue', () => {
    test('triggers edit events', async () => {
        const wrapper = mount(Component, {
            props: {
                cost: {id: 1, type: {id: 10, name: 'bank'}, amount: 50, date: dayjs('2023-12-01'), description: 'desc'},
            },
            global: {
                plugins: [createTestingPinia({
                    createSpy: vi.fn,
                    stubActions: false,
                    initialState: {},
                })],
                stubs,
            }
        })
        const icons = wrapper.findAll('font-awesome-icon-stub')
        expect(icons.length).toBe(3)

        // edit
        await icons[0].trigger('click')
        expect(wrapper.emitted()['show-form'].length).toBe(1)

        // remove
        await icons[1].trigger('click')
        const modal = wrapper.find('.modal.show')
        expect(modal.exists()).toBeTruthy()
    })

    test('triggers remove events', async () => {
        const wrapper = mount(Component, {
            props: {
                cost: {id: 1, type: {id: 10, name: 'bank'}, amount: 50, date: dayjs('2023-12-01'), description: 'desc'},
            },
            global: {
                plugins: [createTestingPinia({
                    createSpy: vi.fn,
                    stubActions: false,
                    initialState: {},
                })],
                stubs,
            }
        })
        const icons = wrapper.findAll('font-awesome-icon-stub')
        expect(icons.length).toBe(3)

        // edit
        await icons[0].trigger('click')
        expect(wrapper.emitted()['show-form'].length).toBe(1)

        // remove
        await icons[1].trigger('click')
        const modal = wrapper.find('.modal.show')
        expect(modal.exists()).toBeTruthy()

        const store = useCostStore()
        const buttons = modal.findAll('button')
        await buttons[1].trigger('click')
        expect(store.deleteCost).toHaveBeenCalledTimes(1)
        expect(store.deleteCost).toHaveBeenLastCalledWith(1)
    })
})
