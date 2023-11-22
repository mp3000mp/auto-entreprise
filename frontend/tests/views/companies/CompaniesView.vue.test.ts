import { describe, expect, test } from 'vitest'
import Component from '@/views/companies/CompaniesView.vue'
import { mount } from '@vue/test-utils'
import {createTestingPinia} from "@pinia/testing";
import {vi} from "vitest";
import {getRowIds, testSorter} from "../../utils/mp3000Table";
import CompanyRow from "@/views/companies/CompanyRow.vue";
import {initCompanies} from "../../data/company";

const stubs = ['font-awesome-icon', 'router-link']

describe('CompaniesView.vue', () => {
    test('sorts companies', async () => {
        const wrapper = mount(Component, {
            global: {
                plugins: [createTestingPinia({
                    createSpy: vi.fn,
                    stubActions: true,
                    initialState: {
                        company: {
                            companies: initCompanies(),
                        }
                    },
                })],
                stubs,
            }
        })

        // initial sorted by company name
        expect(getRowIds(wrapper, CompanyRow, 'company')).toEqual([1, 2, 3])

        await testSorter(wrapper, [
            {columnName: 'Nom', columnIdx: 0, expectedIdsOrder: [1, 2, 3]},
        ], CompanyRow, 'company')
    })
})

describe('CompaniesView.vue', () => {
    test('filters companies', async () => {
        const wrapper = mount(Component, {
            global: {
                plugins: [createTestingPinia({
                    createSpy: vi.fn,
                    stubActions: true,
                    initialState: {
                        company: {
                            companies: initCompanies(),
                        }
                    },
                })],
                stubs,
            }
        })
        expect(getRowIds(wrapper, CompanyRow, 'company').length).toBe(3)

        await wrapper.find('input').setValue('first')
        await wrapper.vm.$nextTick()

        expect(getRowIds(wrapper, CompanyRow, 'company')).toEqual([1])
    })
})

describe('CompaniesView.vue', () => {
    test('opens new form', async () => {
        const wrapper = mount(Component, {
            global: {
                plugins: [createTestingPinia({
                    createSpy: vi.fn,
                    stubActions: false,
                    initialState: {},
                })],
                stubs,
            }
        })
        wrapper.find('button').trigger('click')
        await wrapper.vm.$nextTick()
        expect(wrapper.find('.modal.show').exists()).toBeTruthy()
    })
})
