import type {Cost, CostDTO} from "@/stores/cost/types";

import dayjs from "@/misc/dayjs";

export function convertCost(rawCost: CostDTO): Cost {
    return {
        ...rawCost,
        date: dayjs(rawCost.date),
    }
}
