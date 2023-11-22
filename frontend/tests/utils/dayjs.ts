import dayjs from "../../src/misc/dayjs";

export function setMidnight<T>(object: T, prop: string): T {
    object[prop] = object[prop].startOf('day')
    return object
}
