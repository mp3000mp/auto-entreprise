import { describe, expect, test } from 'vitest'
import dayjs, { Dayjs } from 'dayjs'
import { Ref, ref } from 'vue'
import { useSorter, SortConfigTypeEnum } from '@/composables/useSorter'

interface ListElement {
  id: number
  propString: string
  propNumber: number
  propDate: Dayjs
  customProp: string
}

function getList(): Ref<ListElement[]> {
  return ref([
    { id: 1, propString: 'a', propNumber: 3, propDate: dayjs('2023-11-01'), customProp: '10' },
    { id: 2, propString: 'b', propNumber: 2, propDate: dayjs('2023-11-03'), customProp: '2' },
    { id: 3, propString: 'c', propNumber: 1, propDate: dayjs('2023-11-02'), customProp: '3' }
  ])
}

describe('useSorter.ts', () => {
  test('sorts number prop', () => {
    const { sort, sortedList } = useSorter(
      [{ property: 'propNumber', type: SortConfigTypeEnum.NUMBER }],
      getList()
    )

    sort('propNumber')
    expect(sortedList.value.map((s) => s.id)).toEqual([3, 2, 1])
  })

  test('sorts string prop', () => {
    const { sort, sortedList } = useSorter(
      [{ property: 'propString', type: SortConfigTypeEnum.STRING }],
      getList()
    )

    sort('propString')
    expect(sortedList.value.map((s) => s.id)).toEqual([1, 2, 3])
  })

  test('sorts date prop', () => {
    const { sort, sortedList } = useSorter(
      [{ property: 'propDate', type: SortConfigTypeEnum.DATE }],
      getList()
    )

    sort('propDate')
    expect(sortedList.value.map((s) => s.id)).toEqual([1, 3, 2])
  })

  test('sorts with custom function', () => {
    const { sort, sortedList } = useSorter(
      [
        {
          property: 'customProp',
          type: SortConfigTypeEnum.CUSTOM,
          customCompare: (a, b) => Number(a.customProp) - Number(b.customProp)
        }
      ],
      getList()
    )

    sort('customProp')
    expect(sortedList.value.map((s) => s.id)).toEqual([2, 3, 1])
  })

  test('sorts null values', () => {
    const list = getList()
    const { sort, sortedList } = useSorter(
      [{ property: 'propNumber', type: SortConfigTypeEnum.NUMBER }],
      list
    )

    list.value[0].propNumber = null
    sort('propNumber')
    expect(sortedList.value.map((s) => s.id)).toEqual([1, 3, 2])
  })

  test('sorts with several props', () => {
    const list = getList()
    const { sort, sortedList } = useSorter(
      [
        { property: 'propNumber', type: SortConfigTypeEnum.NUMBER },
        { property: 'propDate', type: SortConfigTypeEnum.DATE }
      ],
      list
    )

    list.value[1].propNumber = 1
    sort('propNumber')
    sort('propDate')
    expect(sortedList.value.map((s) => s.id)).toEqual([3, 2, 1])
  })

  test('handles props priority', () => {
    const { getPriority, sort } = useSorter(
      [
        { property: 'propNumber', type: SortConfigTypeEnum.NUMBER },
        { property: 'propString', type: SortConfigTypeEnum.STRING },
        { property: 'propDate', type: SortConfigTypeEnum.DATE }
      ],
      getList()
    )

    expect(getPriority('propNumber')).toBe(0)
    sort('propNumber')
    expect(getPriority('propNumber')).toBe(1)
    sort('propNumber')
    expect(getPriority('propNumber')).toBe(1)
    sort('propString')
    expect(getPriority('propString')).toBe(2)
    sort('propDate')
    expect(getPriority('propDate')).toBe(3)
    sort('propString')
    expect(getPriority('propNumber')).toBe(1)
    expect(getPriority('propDate')).toBe(2)
    expect(getPriority('propString')).toBe(3)
    sort('propString')
    expect(getPriority('propNumber')).toBe(1)
    expect(getPriority('propString')).toBe(0)
    expect(getPriority('propDate')).toBe(2)
    sort('propString')
    expect(getPriority('propString')).toBe(3)
  })

  test('handles props asc', () => {
    const { getAsc, sort } = useSorter(
      [{ property: 'propNumber', type: SortConfigTypeEnum.NUMBER }],
      getList()
    )

    expect(getAsc('propNumber')).toBe(null)
    sort('propNumber', false)
    expect(getAsc('propNumber')).toBeFalsy()
    sort('propNumber')
    expect(getAsc('propNumber')).toBe(null)
    sort('propNumber')
    expect(getAsc('propNumber')).toBeTruthy()
    sort('propNumber')
    expect(getAsc('propNumber')).toBeFalsy()
    sort('propNumber', true)
    expect(getAsc('propNumber')).toBeTruthy()
  })

  test('resets sorted props', () => {
    const { getPriority, sort, resetSorts } = useSorter(
      [{ property: 'propNumber', type: SortConfigTypeEnum.NUMBER }],
      getList()
    )

    sort('propNumber')
    resetSorts()
    expect(getPriority('propNumber')).toBe(0)
  })
})
