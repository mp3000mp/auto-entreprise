import type {
  WorkedTime,
  WorkedTimeDtoIn,
  WorkedTimeDtoOut,
  NewWorkedTime,
  NewWorkedTimeDtoOut
} from '@/stores/workedTime/types'

import dayjs from '@/misc/dayjs'

export function convertWorkedTimeIn(rawWorkedTime: WorkedTimeDtoIn): WorkedTime {
  return {
    ...rawWorkedTime,
    date: dayjs(rawWorkedTime.date)
  }
}

export function convertWorkedTimeOut(
  workedTime: WorkedTime | NewWorkedTime
): WorkedTimeDtoOut | NewWorkedTimeDtoOut {
  return {
    ...workedTime,
    date: workedTime.date.format('YYYY-MM-DD'),
    tender: '/api/tenders/' + workedTime.tender.id,
    user: '/api/users/' + workedTime.user.id
  }
}
