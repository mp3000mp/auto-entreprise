import { describe, expect, test } from 'vitest'
import Component from '@/views/contacts/ContactRow.vue'
import { mount } from '@vue/test-utils'
import {createTestingPinia} from "@pinia/testing";
import {vi} from "vitest";
import {useContactStore} from "@/stores/contact";

const stubs = ['font-awesome-icon', 'router-link']

describe('ContactRow.vue', () => {
    test('triggers events', async () => {
        const wrapper = mount(Component, {
            props: {
                contact: {id: 1, company: {id: 10, name: 'comp'}, firstName: 'Jean', lastName: 'Bon', email: 'user@mp3000.fr', phone: null},
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
                contact: {id: 1, company: {id: 10, name: 'comp'}, firstName: 'Jean', lastName: 'Bon', email: 'user@mp3000.fr', phone: null},
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

        const store = useContactStore()
        const buttons = modal.findAll('button')
        await buttons[1].trigger('click')
        expect(store.delete).toHaveBeenCalledTimes(1)
        expect(store.delete).toHaveBeenLastCalledWith(1)
    })
})
