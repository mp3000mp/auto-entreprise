import type { Dayjs } from 'dayjs'

export function setMidnight<T extends Record<string, unknown>>(object: T, prop: string): T {
    ;(object as Record<string, unknown>)[prop] = (object[prop] as Dayjs).startOf('day')
    return object
}
