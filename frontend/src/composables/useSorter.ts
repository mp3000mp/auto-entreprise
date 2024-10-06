import { computed, ref } from 'vue'
import type { Ref } from 'vue'
import type { Dayjs } from 'dayjs'

export enum SortConfigTypeEnum {
  STRING = 'string',
  NUMBER = 'number',
  DATE = 'date',
  CUSTOM = 'custom'
}
type SortConfig = {
  property: string
  type: SortConfigTypeEnum
  priority: number
  asc: boolean
  customCompare?: (a: any, b: any) => number // todo T
}
type Sort = Pick<SortConfig, 'property' | 'type' | 'customCompare'>

function propCompare<T>(sort: SortConfig): (a: T, b: T) => number {
  let compareFunc: (a: number, b: number) => number
  switch (sort.type) {
    case SortConfigTypeEnum.STRING:
      compareFunc = (a: string, b: string): number => String(a).localeCompare(String(b))
      break
    case SortConfigTypeEnum.DATE: // dayjs
      compareFunc = (a: Dayjs, b: Dayjs): number => a.diff(b)
      break
    case SortConfigTypeEnum.NUMBER:
    default:
      compareFunc = (a: number, b: number): number => a - b
      break
  }

  return (a: T, b: T): number => {
    const coef = sort.asc ? 1 : -1
    if (SortConfigTypeEnum.CUSTOM === sort.type) {
      return sort.customCompare(a, b) * coef
    }

    const aVal = a[sort.property]
    const bVal = b[sort.property]
    if (aVal === null) {
      return (null === bVal ? 0 : -1) * coef
    }
    if (bVal === null) {
      return 1 * coef
    }

    return compareFunc(aVal, bVal) * coef
  }
}

export function useSorter<T>(options: SortConfig[], list: Ref<T>[]) {
  const sorts: Ref<Sort[]> = ref(options.map((option) => ({ ...option, priority: 0, asc: true })))
  let maxPriority = 0

  function levelSorts(currentPriority: number) {
    sorts.value.forEach((sort) => {
      if (sort.priority > currentPriority) {
        sort.priority--
      }
    })
  }
  function findSort(property: string): Sort | null {
    return sorts.value.find((sort) => sort.property === property) ?? null
  }

  function addSort(targetSort: Sort, asc: boolean | null = null) {
    const currentPriority = targetSort.priority
    if (currentPriority === 0) {
      targetSort.asc = asc ?? true
      maxPriority++
      targetSort.priority = maxPriority
    } else {
      targetSort.asc = null === asc ? !targetSort.asc : asc
      if (targetSort.priority === maxPriority) {
        return
      }
      levelSorts(currentPriority)
      targetSort.priority = maxPriority
    }

    sorts.value.sort((a, b) => b.priority - a.priority)
  }

  function removeSort(targetSort: Sort) {
    if (targetSort.priority === 0) {
      return
    }

    const currentPriority = targetSort.priority
    targetSort.priority = 0
    targetSort.asc = true
    levelSorts(currentPriority)
    if (maxPriority === currentPriority) {
      maxPriority--
    }
  }

  function sort(property: string, asc: boolean | null = null) {
    const targetSort = findSort(property)
    if (null === targetSort) {
      return
    }
    targetSort.asc === false ? removeSort(targetSort) : addSort(targetSort, asc)
  }

  function resetSorts() {
    sorts.value = sorts.value.map((sort) => ({ ...sort, priority: 0, asc: true }))
    maxPriority = 0
  }

  function getPriority(property: string): number {
    const targetSort = findSort(property)
    return targetSort?.priority ?? 0
  }

  function getAsc(property: string): boolean | null {
    const targetSort = findSort(property)
    return !targetSort || targetSort.priority === 0 ? null : targetSort.asc
  }

  const sortedList = computed(() => {
    const rList = [...list.value]
    sorts.value.forEach((sort) => {
      if (sort.priority !== 0) {
        rList.sort(propCompare(sort))
      }
    })
    return rList
  })

  return {
    sort,
    resetSorts,
    getPriority,
    getAsc,
    sortedList
  }
}
