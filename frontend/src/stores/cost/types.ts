import type { Dayjs } from 'dayjs'

export type CostType = {
  id: number
  label: string
}

type BaseCost = {
  id: number
  description: string
  amount: number
}
export type CostDtoIn = BaseCost & {
  date: string
  type: CostType
}
export type CostDtoOut = BaseCost & {
  date: string
  type: string
}
export type Cost = BaseCost & {
  date: Dayjs
  type: CostType
}
