import { computed, ref } from 'vue'
import type { Ref } from 'vue'

export enum FilterConfigTypeEnum {
  STRING = 'string',
  // DATE = 'date', // todo
  // SELECT = 'select', // todo
  CUSTOM = 'custom'
}
type FilterConfig = {
  property: string
  type: FilterConfigTypeEnum
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  propFunc?: (a: any) => any // todo T
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  customFunc?: (a: any) => boolean // todo T
}
type Filter = FilterConfig & {
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  value: any // todo type ?
}

// eslint-disable-next-line @typescript-eslint/no-explicit-any
function propFilter(filter: Filter): (item: any) => boolean {
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  let filterFunc: (prop: any) => boolean
  switch (filter.type) {
    case FilterConfigTypeEnum.STRING:
      filterFunc = (prop: string) => {
        if (filter.value.length < 1) {
          return true
        }
        return String(prop).toLowerCase().includes(filter.value.toLowerCase())
      }
      break
  }

  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  return (item: any): boolean => {
    if (FilterConfigTypeEnum.CUSTOM === filter.type) {
      return filter.customFunc!(item)
    }

    const prop = filter.propFunc ? filter.propFunc(item) : item[filter.property]
    return filterFunc(prop)
  }
}

export function useFilter<T>(options: FilterConfig[], list: Ref<T[]>) {
  const filters: Ref<Filter[]> = ref(options.map((option) => ({ ...option, value: '' })))

  const filteredList = computed(() => {
    return filters.value.reduce(
      (result, filter) => result.filter(propFilter(filter)),
      [...list.value]
    )
  })

  return {
    filteredList
  }
}
