import {computed, ref} from 'vue'
import type {Ref} from 'vue'

export enum FilterConfigTypeEnum {
    STRING = 'string',
    // DATE = 'date', // todo
    // SELECT = 'select', // todo
    CUSTOM = 'custom'
}
type FilterConfig = {
    property: string
    type: FilterConfigTypeEnum
    propFunc?: (a: any) => any // todo T
    customFunc?: (a: any) => boolean // todo T
}
type Filter = FilterConfig & {
    value: any // todo type ?
}

function propFilter<T>(filter: FilterConfig): (item: any) => bool {
    let filterFunc: (prop: any) => bool
    switch (item.type) {
        case FilterConfigTypeEnum.DATE:
            // todo
          break;
        case FilterConfigTypeEnum.SELECT:
            // todo
          break;
        case FilterConfigTypeEnum.STRING:
          filterFunc = (prop: string) => {
            if (filter.filterValue.length < 1) {
              return true
            }
            return String(prop).toLowerCase().includes(filter.filterValue.toLowerCase())
          }
          break;
    }

    return (item: any): bool => {
        if (FilterConfigTypeEnum.CUSTOM === filter.type) {
            return filter.customFunc(item)
        }

        return filterFunc(filter.propFunc(item))
    }
}

export function useFilter<T>(options: FilterConfig[], list: Ref<T>[]) {
    const filters: Ref<Filter[]> = ref(options.map((option) => ({...option, value: ''})))

    const filteredList = computed(() => {
        const rList = [...list.value]
        filters.value.forEach((filter) => {
            rList.filter(propFilter(filter))
        })
        return rList
    })

    return {
        filteredList
    }
}
