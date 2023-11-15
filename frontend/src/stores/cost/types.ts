import type {Dayjs} from 'dayjs'

export type CostType = {
    id: number;
    label: string;
}

export type CostDTO = {
    id: number;
    date: string;
    description: string;
    amount: number;
    type: CostType;
}

export type Cost = CostDTO & {
    date: Dayjs;
}
