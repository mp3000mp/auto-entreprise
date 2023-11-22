import {Cost, CostType} from "../../src/stores/cost/types";
import dayjs from "../../src/misc/dayjs";

export function initCosts(): Cost[] {
    return [
        {id: 1, type: {id: 1, label: 'type1'}, amount: 200, date: dayjs('2023-11-01'), description: 'first'},
        {id: 2, type: {id: 2, label: 'type2'}, amount: 100, date: dayjs('2023-11-02'), description: 'second'},
        {id: 3, type: {id: 3, label: 'type3'}, amount: 300, date: dayjs('2023-11-03'), description: 'third'},
    ]
}
export function initCostTypes(): CostType[] {
    return [
        {id: 1, label: 'type1'},
        {id: 2, label: 'type2'},
        {id: 3, label: 'type3'},
    ]
}

export function initCost(): Cost {
    return {
        id: 1,
        type: initCostTypes()[1],
        amount: 50,
        date: dayjs('2023-12-01'),
        description: 'desc'
    }
}
