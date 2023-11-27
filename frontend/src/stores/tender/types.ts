import type { Dayjs } from 'dayjs'
import type { TenderOpportunity } from '@/stores/opportunity/types'
import type { WorkedTime } from '@/stores/workedTime/types'

export type TenderStatus = {
  id: number
  position: number
  label: string
}

export type TenderStatusLog = {
  id: number
  status: TenderStatus
  createdAt: Dayjs
}
export type TenderStatusLogDtoIn = Omit<TenderStatusLog, 'createdAt'> & {
  createdAt: string
}

export type TenderRow = {
  id: number
  position: number
  soldDays: number
  title: string
  description: string
}
export type NewTenderRow = Omit<TenderRow, 'id'>
export type TenderRowDtoOut = TenderRow & {
  tender: string
}
export type NewTenderRowDtoOut = Omit<TenderRowDtoOut, 'id'>

export type Tender = {
  id: number
  status: TenderStatus
  opportunity: TenderOpportunity
  version: number
  averageDailyRate: number
  createdAt: Dayjs
  sentAt: Dayjs | null
  acceptedAt: Dayjs | null
  refusedAt: Dayjs | null
  canceledAt: Dayjs | null
  comments: string | null
  tenderRows: TenderRow[]
  soldDays: number
  workedDays: number
  statusLogs: TenderStatusLog[]
  workedTimes: WorkedTime[]
  tenderFileDocx: string | null
  tenderFilePdf: string | null
}
export type NewTender = Omit<
  Tender,
  'id' | 'tenderRows' | 'statusLogs' | 'workedTimes' | 'tenderFileDocx' | 'tenderFilePdf'
>
export type ListTender = Pick<
  Tender,
  | 'id'
  | 'status'
  | 'version'
  | 'createdAt'
  | 'averageDailyRate'
  | 'soldDays'
  | 'workedDays'
  | 'opportunity'
> & {
  opportunity: TenderOpportunity
}
export type OpportunityTender = Omit<ListTender, 'createdAt' | 'opportunity'>
export type TenderDtoIn = Omit<
  Tender,
  'canceledAt' | 'acceptedAt' | 'refusedAt' | 'sentAt' | 'statusLogs'
> & {
  createdAt: string
  sentAt: string | null
  acceptedAt: string | null
  refusedAt: string | null
  canceledAt: string | null
  statusLogs: TenderStatusLogDtoIn[]
}
export type ListTenderDtoIn = Omit<ListTender, 'createdAt'> & {
  createdAt: string
}
export type TenderDtoOut = Omit<
  Tender,
  | 'opportunity'
  | 'status'
  | 'tenderRows'
  | 'statusLogs'
  | 'workedTimes'
  | 'canceledAt'
  | 'acceptedAt'
  | 'refusedAt'
  | 'sentAt'
  | 'tenderFileDocx'
  | 'tenderFilePdf'
> & {
  opportunity: string
  status: string
  sentAt: string | null
  acceptedAt: string | null
  refusedAt: string | null
  canceledAt: string | null
}
export type NewTenderDtoOut = Omit<TenderDtoOut, 'id'>
