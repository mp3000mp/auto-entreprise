import { computed, ref } from 'vue'
import type { Ref } from 'vue'
import type { Dayjs } from 'dayjs'

export enum SortConfigTypeEnum {
  STRING = 'string',
  NUMBER = 'number',
  DATE = 'date',
  CUSTOM = 'custom'
}

interface SortConfig {
  property: string
  type: SortConfigTypeEnum
  priority: number
  asc: boolean
  customCompare?: (a: any, b: any) => number // todo T
}
type SorterOption = Pick<SortConfig, 'property' | 'type' | 'customCompare'>

export class Sorter<T> {
  private options: Ref<SortConfig[]>
  private list: Ref<T[]>
  private maxPriority = 0

  private propCompare = (option: SortConfig) => {
    let fn = (a: number, b: number): number => a - b
    switch (option.type) {
      case SortConfigTypeEnum.STRING:
        fn = (a: string, b: string): number => a.localeCompare(b)
        break
      case SortConfigTypeEnum.DATE: // dayjs
        fn = (a: Dayjs, b: Dayjs): number => a.diff(b)
        break
    }
    return (a: T, b: T): number => {
      const coef = option.asc ? 1 : -1
      if (SortConfigTypeEnum.CUSTOM === option.type) {
        return option.customCompare(a, b) * coef
      }
      const aVal = a[option.property]
      const bVal = b[option.property]
      if (aVal === null) {
        return (null === bVal ? 0 : -1) * coef
      }
      if (bVal === null) {
        return 1 * coef
      }
      return fn(aVal, bVal) * coef
    }
  }

  constructor(options: SorterOption[], list: Ref<T[]>) {
    this.options = ref(options.map((option) => ({ ...option, priority: 0, asc: true })))
    this.list = list
  }

  public addSort(property: string, asc: boolean | null = null) {
    const currentOption = this.options.value.find((opt) => opt.property === property)
    if (!currentOption) {
      return
    }
    const currentPriority = currentOption.priority
    if (currentPriority === 0) {
      currentOption.asc = asc ?? true
      this.maxPriority++
      currentOption.priority = this.maxPriority
    } else {
      currentOption.asc = null === asc ? !currentOption.asc : asc
      if (currentOption.priority === this.maxPriority) {
        return
      }
      this.options.value.forEach((option) => {
        if (option.priority > currentPriority) {
          option.priority--
        }
      })
      currentOption.priority = this.maxPriority
    }
    this.options.value.sort((a, b) => b.priority - a.priority)
  }

  public removeSort(property: string) {
    const currentOption = this.options.value.find((opt) => opt.property === property)
    if (!currentOption || currentOption.priority === 0) {
      return
    }
    const currentPriority = currentOption.priority
    currentOption.priority = 0
    currentOption.asc = true
    this.options.value.forEach((option) => {
      if (option.priority > currentPriority) {
        option.priority--
      }
    })
    if (this.maxPriority === currentPriority) {
      this.maxPriority--
    }
  }

  public reset() {
    this.options.value = this.options.value.map((option) => ({ ...option, priority: 0, asc: true }))
    this.maxPriority = 0
  }

  public sortedList = computed(() => {
    this.options.value.forEach((option) => {
      if (option.priority === 0) {
        return
      }
      this.list.value.sort(this.propCompare(option))
    })
    return this.list.value
  })

  public getPriority = (property: string): number => {
    const option = this.options.value.find((opt) => opt.property === property)
    return option?.priority ?? 0
  }

  public isAsc = (property: string): boolean | null => {
    const option = this.options.value.find((opt) => opt.property === property)
    return !option || option.priority === 0 ? null : option.asc
  }
}
