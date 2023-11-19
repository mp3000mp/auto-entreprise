import type { Dayjs } from 'dayjs'

export type CostType = {
  id: number
  label: string
}

export interface Cost {
  id: number
  type: CostType
  date: Dayjs
  amount: number
  description: string
}
export type NewCost = Omit<Cost, 'id'>
export type CostDtoIn = Omit<Cost, 'date'> & {
  date: string
}
export type CostDtoOut = Omit<Cost, 'date'|'type'> & {
  type: string
  date: string
}
export type NewCostDtoOut = Omit<CostDtoOut, 'id'>
