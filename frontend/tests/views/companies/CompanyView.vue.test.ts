import { describe, expect, test } from 'vitest'
import Component from '@/views/companies/CompanyView.vue'
import { mount } from '@vue/test-utils'
import {createTestingPinia} from "@pinia/testing";
import {vi} from "vitest";
import {initCompany, initEmptyCompany} from "../../data/company";
import {useCompanyStore} from "../../../src/stores/company";
import { useRouter } from 'vue-router'

vi.mock('vue-router')
const stubs = ['font-awesome-icon', 'router-link']

describe('CompanyView.vue', () => {
    test('triggers edit event', async () => {
        const wrapper = mount(Component, {
            props: {
                companyId: 1,
            },
            global: {
                plugins: [createTestingPinia({
                    createSpy: vi.fn,
                    stubActions: true,
                    initialState: {
                        company: {
                            currentCompany: initCompany(),
                        }
                    },
                })],
                stubs,
            }
        })
        expect(wrapper.findAll('font-awesome-icon-stub').length).toBe(2)

        await wrapper.find('font-awesome-icon-stub[icon="fa,pen-to-square"]').trigger('click')
        expect(wrapper.find('.modal.show').exists()).toBeTruthy()
    })

    test('triggers remove event', async () => {
        const routerMock = {
            push: vi.fn(),
        }
        useRouter.mockReturnValue(routerMock)
        const wrapper = mount(Component, {
            props: {
                companyId: 1,
            },
            global: {
                plugins: [createTestingPinia({
                    createSpy: vi.fn,
                    stubActions: true,
                    initialState: {
                        company: {
                            currentCompany: initEmptyCompany(),
                        }
                    },
                })],
                stubs,
            }
        })
        expect(wrapper.findAll('font-awesome-icon-stub').length).toBe(4)

        await wrapper.find('font-awesome-icon-stub[icon="fa,trash"]').trigger('click')
        const modal = wrapper.find('.modal.show')
        expect(modal.exists()).toBeTruthy()

        const store = useCompanyStore()
        const buttons = modal.findAll('button')
        await buttons[1].trigger('click')
        expect(store.delete).toHaveBeenCalledTimes(1)
        expect(store.delete).toHaveBeenLastCalledWith(1)
        expect(routerMock.push).toHaveBeenCalledTimes(1)
        expect(routerMock.push).toHaveBeenLastCalledWith({name: 'companies'})
    })
})
