import { describe, expect, test } from 'vitest'
import Component from '@/views/companies/CompanyRow.vue'
import { mount } from '@vue/test-utils'
import {createTestingPinia} from "@pinia/testing";
import {vi} from "vitest";
import {useCompanyStore} from "../../../src/stores/company";

const stubs = ['font-awesome-icon', 'router-link']

describe('CompanyRow.vue', () => {
    test('triggers edit events', async () => {
        const wrapper = mount(Component, {
            props: {
                company: {id: 1, name: 'comp'},
                isDeletable: false,
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
        expect(icons.length).toBe(1)

        // edit
        await icons[0].trigger('click')
        expect(wrapper.emitted()['show-form'].length).toBe(1)
    })

    test('triggers remove events', async () => {
        const wrapper = mount(Component, {
            props: {
                company: {id: 1, name: 'comp'},
                isDeletable: true,
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

        // remove
        await icons[1].trigger('click')
        const modal = wrapper.find('.modal.show')
        expect(modal.exists()).toBeTruthy()

        const store = useCompanyStore()
        const buttons = modal.findAll('button')
        await buttons[1].trigger('click')
        expect(store.delete).toHaveBeenCalledTimes(1)
        expect(store.delete).toHaveBeenLastCalledWith(1)
    })
})
