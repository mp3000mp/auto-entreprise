import type { Cost, CostDtoIn, CostDtoOut } from '@/stores/cost/types'

import dayjs from '@/misc/dayjs'

export function convertCostIn(rawCost: CostDtoIn): Cost {
  return {
    ...rawCost,
    date: dayjs(rawCost.date),
  }
}
export function convertCostOut(cost: Cost): CostDtoOut {
  return {
    ...cost,
    date: cost.date.format('YYYY-MM-DD'),
    type: '/api/cost_types/' + cost.type.id,
  }
}
