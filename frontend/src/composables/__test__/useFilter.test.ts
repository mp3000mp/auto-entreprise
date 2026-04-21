import { describe, expect, test } from 'vitest'
import { ref } from 'vue'
import { useFilter, FilterConfigTypeEnum } from '@/composables/useFilter'

interface Item {
  id: number
  name: string
  category: string
}

function getList() {
  return ref<Item[]>([
    { id: 1, name: 'Alice', category: 'admin' },
    { id: 2, name: 'Bob', category: 'user' },
    { id: 3, name: 'alice2', category: 'admin' }
  ])
}

describe('useFilter.ts', () => {
  test('returns all items when filter is empty', () => {
    const { filteredList } = useFilter(
      [{ property: 'name', type: FilterConfigTypeEnum.STRING }],
      getList()
    )

    expect(filteredList.value.map((i) => i.id)).toEqual([1, 2, 3])
  })

  test('filters by string property', () => {
    const { filters, filteredList } = useFilter(
      [{ property: 'name', type: FilterConfigTypeEnum.STRING }],
      getList()
    )

    filters.value[0].value = 'alice'
    expect(filteredList.value.map((i) => i.id)).toEqual([1, 3])
  })

  test('string filter is case-insensitive', () => {
    const { filters, filteredList } = useFilter(
      [{ property: 'name', type: FilterConfigTypeEnum.STRING }],
      getList()
    )

    filters.value[0].value = 'ALICE'
    expect(filteredList.value.map((i) => i.id)).toEqual([1, 3])
  })

  test('filters with propFunc', () => {
    const { filters, filteredList } = useFilter(
      [
        {
          property: 'category',
          type: FilterConfigTypeEnum.STRING,
          propFunc: (item: Item) => item.category
        }
      ],
      getList()
    )

    filters.value[0].value = 'admin'
    expect(filteredList.value.map((i) => i.id)).toEqual([1, 3])
  })

  test('filters with custom function', () => {
    const { filteredList } = useFilter(
      [
        {
          property: 'isAdmin',
          type: FilterConfigTypeEnum.CUSTOM,
          customFunc: (item: Item) => item.category === 'admin'
        }
      ],
      getList()
    )

    expect(filteredList.value.map((i) => i.id)).toEqual([1, 3])
  })

  test('applies multiple filters', () => {
    const { filters, filteredList } = useFilter(
      [
        { property: 'name', type: FilterConfigTypeEnum.STRING },
        { property: 'category', type: FilterConfigTypeEnum.STRING }
      ],
      getList()
    )

    filters.value[0].value = 'alice'
    filters.value[1].value = 'admin'
    expect(filteredList.value.map((i) => i.id)).toEqual([1, 3])
  })

  test('updates when list changes', () => {
    const list = getList()
    const { filteredList } = useFilter(
      [{ property: 'name', type: FilterConfigTypeEnum.STRING }],
      list
    )

    expect(filteredList.value.length).toBe(3)
    list.value.push({ id: 4, name: 'Charlie', category: 'user' })
    expect(filteredList.value.length).toBe(4)
  })
})
