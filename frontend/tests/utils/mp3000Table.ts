import { expect } from 'vitest'
import Mp3000TableHeader from "@/components/Mp3000TableHeader.vue";
import type { VueWrapper } from '@vue/test-utils'
import type { Component } from 'vue'

type Option = {
    columnName: string
    columnIdx: number
    expectedIdsOrder: number[]
}

export function getRowIds(wrapper: VueWrapper, rowComponent: Component, prop: string): number[] {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    return wrapper.findAllComponents(rowComponent).map((row) => (row.props() as any)[prop].id)
}

export async function testSorter(wrapper: VueWrapper, expectedInitialIdsOrder: number[], options: Option[], rowComponent: Component, rowProp: string) {
    const headers = wrapper.findAllComponents(Mp3000TableHeader)
    expect(getRowIds(wrapper, rowComponent, rowProp)).toEqual(expectedInitialIdsOrder)
    for (const option of options) {
        await headers[option.columnIdx].trigger('click')
        expect(getRowIds(wrapper, rowComponent, rowProp), 'Bad sorter configuration for column "'+option.columnName+'" (index '+option.columnIdx+')').toEqual(option.expectedIdsOrder)
    }
}
