import { describe, expect, test } from 'vitest'
import Component from '@/views/costs/CostsView.vue'
import { mount } from '@vue/test-utils'
import {createTestingPinia} from "@pinia/testing";
import {vi} from "vitest";
import dayjs from "@/misc/dayjs";
import {Cost, CostType} from "@/stores/cost/types";
import {getRowIds, testSorter} from "../../utils/mp3000Table";
import CostRow from "@/views/costs/CostRow.vue";

const stubs = ['font-awesome-icon']

function initCosts(): Cost[] {
    return [
        {id: 1, type: {id: 1, label: 'type1'}, amount: 200, date: dayjs('2023-11-01'), description: 'first'},
        {id: 2, type: {id: 2, label: 'type2'}, amount: 100, date: dayjs('2023-11-02'), description: 'second'},
        {id: 3, type: {id: 3, label: 'type3'}, amount: 300, date: dayjs('2023-11-03'), description: 'third'},
    ]
}
function initCostTypes(): CostType[] {
    return [
        {id: 1, label: 'type1'},
        {id: 2, label: 'type2'},
        {id: 3, label: 'type3'},
    ]
}

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
