import type {WorkedTime} from "@/stores/workedTime/types";
import dayjs from "@/misc/dayjs";

export function initWorkedTimes(): WorkedTime[] {
    return [
        {id: 1, date: dayjs('2023-11-01'), workedDays: 0.5, user: {id: 1}, opportunity: {id: 1}},
        {id: 2, date: dayjs('2023-11-02'), workedDays: 1, user: {id: 1}, opportunity: {id: 1}},
        {id: 3, date: dayjs('2023-11-03'), workedDays: 0.75, user: {id: 1}, opportunity: {id: 1}},
    ]
}

export function initWorkedTime(): WorkedTime {
    return {
        id: 1,
        date: dayjs('2023-12-01'),
        workedDays: 0.25,
        user: {id: 1},
        opportunity: {id: 1},
    }
}
