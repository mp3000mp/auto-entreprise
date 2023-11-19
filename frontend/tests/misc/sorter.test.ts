import { expect, test } from 'vitest'
import {SortConfigTypeEnum, Sorter} from "@/misc/sorter";
import dayjs, {Dayjs} from "dayjs";
import {Ref, ref} from 'vue'

interface ListElement {
    id: number
    propString: string
    propNumber: number
    propDate: Dayjs
    customProp: string
}

function getList(): Ref<ListElement[]> {
    return ref([
       {id: 1, propString: 'a', propNumber: 3, propDate: dayjs('2023-11-01'), customProp: '10'},
       {id: 2, propString: 'b', propNumber: 2, propDate: dayjs('2023-11-03'), customProp: '2'},
       {id: 3, propString: 'c', propNumber: 1, propDate: dayjs('2023-11-02'), customProp: '3'},
   ])
}

test('sorts number prop', () => {
    const sorter = new Sorter([{
        property: 'propNumber',
        type: SortConfigTypeEnum.NUMBER,
    }], getList())

    sorter.addSort('propNumber')
    expect(sorter.sortedList.value.map(s => s.id)).toEqual([3, 2, 1])
})

test('sorts string prop', () => {
    const sorter = new Sorter([{
        property: 'propString',
        type: SortConfigTypeEnum.STRING,
    }], getList())

    sorter.addSort('propString')
    expect(sorter.sortedList.value.map(s => s.id)).toEqual([1, 2, 3])
})

test('sorts date prop', () => {
    const sorter = new Sorter([{
        property: 'propDate',
        type: SortConfigTypeEnum.DATE,
    }], getList())

    sorter.addSort('propDate')
    expect(sorter.sortedList.value.map(s => s.id)).toEqual([1, 3, 2])
})

test('sorts with custom function', () => {
    const sorter = new Sorter([{
        property: 'customProp',
        type: SortConfigTypeEnum.CUSTOM,
        customCompare: (a, b) => Number(a.customProp) - Number(b.customProp),
    }], getList())

    sorter.addSort('customProp')
    expect(sorter.sortedList.value.map(s => s.id)).toEqual([2, 3, 1])
})

test('sorts null values', () => {
    const list = getList()
    const sorter = new Sorter([{
        property: 'propNumber',
        type: SortConfigTypeEnum.NUMBER,
    }], list)

    list.value[0].propNumber = null
    sorter.addSort('propNumber')
    expect(sorter.sortedList.value.map(s => s.id)).toEqual([1, 3, 2])
})

test('sorts with several props', () => {
    const list = getList()
    const sorter = new Sorter([{
        property: 'propNumber',
        type: SortConfigTypeEnum.NUMBER,
    }, {
        property: 'propDate',
        type: SortConfigTypeEnum.DATE,
    }], list)

    list.value[1].propNumber = 1
    sorter.addSort('propNumber')
    sorter.addSort('propDate')
    expect(sorter.sortedList.value.map(s => s.id)).toEqual([3, 2, 1])
})

test('handles props priority', () => {
    const sorter = new Sorter([{
        property: 'propNumber',
        type: SortConfigTypeEnum.NUMBER,
    }, {
        property: 'propString',
        type: SortConfigTypeEnum.STRING,
    }, {
        property: 'propDate',
        type: SortConfigTypeEnum.DATE,
    }], getList())

    expect(sorter.getPriority('propNumber')).toBe(0)
    sorter.addSort('propNumber')
    expect(sorter.getPriority('propNumber')).toBe(1)
    sorter.addSort('propNumber')
    expect(sorter.getPriority('propNumber')).toBe(1)
    sorter.addSort('propString')
    expect(sorter.getPriority('propString')).toBe(2)
    sorter.addSort('propDate')
    expect(sorter.getPriority('propDate')).toBe(3)
    sorter.addSort('propString')
    expect(sorter.getPriority('propNumber')).toBe(1)
    expect(sorter.getPriority('propDate')).toBe(2)
    expect(sorter.getPriority('propString')).toBe(3)
    sorter.removeSort('propString')
    expect(sorter.getPriority('propNumber')).toBe(1)
    expect(sorter.getPriority('propString')).toBe(0)
    expect(sorter.getPriority('propDate')).toBe(2)
    sorter.addSort('propString')
    expect(sorter.getPriority('propString')).toBe(3)
})

test('handles props asc', () => {
    const sorter = new Sorter([{
        property: 'propNumber',
        type: SortConfigTypeEnum.NUMBER,
    }], getList())

    expect(sorter.isAsc('propNumber')).toBe(null)
    sorter.addSort('propNumber')
    expect(sorter.isAsc('propNumber')).toBeTruthy()
    sorter.addSort('propNumber', true)
    expect(sorter.isAsc('propNumber')).toBeTruthy()
    sorter.addSort('propNumber')
    expect(sorter.isAsc('propNumber')).toBeFalsy()
    sorter.addSort('propNumber')
    expect(sorter.isAsc('propNumber')).toBeTruthy()
})

test('resets sorted props', () => {
    const sorter = new Sorter([{
        property: 'propNumber',
        type: SortConfigTypeEnum.NUMBER,
    }], getList())

    sorter.addSort('propNumber')
    sorter.reset()
    expect(sorter.getPriority('propNumber')).toBe(0)
})
