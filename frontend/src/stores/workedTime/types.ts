import type { Dayjs } from 'dayjs'

export type WorkedTime = {
  id: number
  opportunity: { id: number }
  user: { id: number }
  workedDays: number
  date: Dayjs
}
export type NewWorkedTime = Omit<WorkedTime, 'id'>
export type WorkedTimeDtoIn = Omit<WorkedTime, 'date'> & {
  date: string
}
export type WorkedTimeDtoOut = Omit<WorkedTime, 'date' | 'opportunity' | 'user'> & {
  date: string
  opportunity: string
  user: string
}
export type NewWorkedTimeDtoOut = Omit<WorkedTimeDtoOut, 'id'>
