import { expect } from 'vitest'
import Mp3000TableHeader from "@/components/Mp3000TableHeader.vue";

type Option = {
    columnName: string
    columnIdx: number
    expectedIdsOrder: number[]
}

export function getRowIds(wrapper: any, rowComponent: any, prop: string): number[] {
    return wrapper.findAllComponents(rowComponent).map(row => row.props(prop).id)
}

export async function testSorter(wrapper: any, options: Option[], rowComponent: any, rowProp: string) {
    const headers = wrapper.findAllComponents(Mp3000TableHeader)
    wrapper.vm.sorter.reset()
    for (const option of options) {
        await headers[option.columnIdx].trigger('click')
        expect(getRowIds(wrapper, rowComponent, rowProp), 'Bad sorter configuration for column "'+option.columnName+'" (index '+option.columnIdx+')').toEqual(option.expectedIdsOrder)
        wrapper.vm.sorter.reset()
    }
}
