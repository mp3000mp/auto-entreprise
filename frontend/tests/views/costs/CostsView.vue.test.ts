import { describe, expect, test } from 'vitest'
import Component from '@/views/costs/CostsView.vue'
import { mount } from '@vue/test-utils'
import {createTestingPinia} from "@pinia/testing";
import {vi} from "vitest";
import {getRowIds, testSorter} from "../../utils/mp3000Table";
import CostRow from "@/views/costs/CostRow.vue";
import {initCosts, initCostTypes} from "../../data/cost";

const stubs = ['font-awesome-icon']

describe('CostsView.vue', () => {
    test('sorts costs', async () => {
        const wrapper = mount(Component, {
            global: {
                plugins: [createTestingPinia({
                    createSpy: vi.fn,
                    stubActions: true,
                    initialState: {
                        cost: {
                            costs: initCosts(),
                        }
                    },
                })],
                stubs,
            }
        })

        // todo why 3 needed ?
        await wrapper.vm.$nextTick()
        await wrapper.vm.$nextTick()
        await wrapper.vm.$nextTick()

        // initial sorted by date
        expect(getRowIds(wrapper, CostRow, 'cost')).toEqual([3, 2, 1])

        await testSorter(wrapper, [
            {columnName: 'Type', columnIdx: 0, expectedIdsOrder: [1, 2, 3]},
            {columnName: 'Date', columnIdx: 1, expectedIdsOrder: [1, 2, 3]},
            {columnName: 'Montant', columnIdx: 2, expectedIdsOrder: [2, 1, 3]},
            {columnName: 'Description', columnIdx: 3, expectedIdsOrder: [1, 2, 3]},
        ], CostRow, 'cost')
    })
})

describe('CostsView.vue', () => {
    test('filters costs', async () => {
        const wrapper = mount(Component, {
            global: {
                plugins: [createTestingPinia({
                    createSpy: vi.fn,
                    stubActions: true,
                    initialState: {
                        cost: {
                            costs: initCosts(),
                            costTypes: initCostTypes(),
                        }
                    },
                })],
                stubs,
            }
        })
        expect(getRowIds(wrapper, CostRow, 'cost').length).toBe(3)

        await wrapper.find('.form-select').setValue('2')
        await wrapper.vm.$nextTick()

        expect(getRowIds(wrapper, CostRow, 'cost')).toEqual([2])
    })
})

describe('CostsView.vue', () => {
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
