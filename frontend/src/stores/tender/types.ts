import type { Dayjs } from 'dayjs'
import type { TenderOpportunity } from '@/stores/opportunity/types'

export type TenderStatus = {
  id: number
  position: number
  label: string
  code: string
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

export enum TenderFileTypeEnum {
  TENDER = 'tender',
  OTHER = 'other'
}
export const tenderFileTypeLabels = {
  [TenderFileTypeEnum.TENDER]: 'Devis',
  [TenderFileTypeEnum.OTHER]: 'Autre'
}

export type TenderFile = {
  id: number
  type: TenderFileTypeEnum
  title: string
  description: string
  createdAt: Dayjs
}
export type TenderFileDtoIn = Omit<TenderFile, 'createdAt'> & {
  createdAt: string
}

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
  statusLogs: TenderStatusLog[]
  tenderFiles: TenderFile[]
}
export type NewTender = Omit<Tender, 'id' | 'tenderRows' | 'statusLogs' | 'tenderFiles'>
export type ListTender = Pick<
  Tender,
  'id' | 'status' | 'version' | 'createdAt' | 'averageDailyRate' | 'soldDays' | 'opportunity'
> & {
  opportunity: TenderOpportunity
}
export type OpportunityTender = Omit<ListTender, 'createdAt' | 'opportunity'>
export type TenderDtoIn = Omit<
  Tender,
  'canceledAt' | 'acceptedAt' | 'refusedAt' | 'sentAt' | 'statusLogs' | 'tenderFiles'
> & {
  createdAt: string
  sentAt: string | null
  acceptedAt: string | null
  refusedAt: string | null
  canceledAt: string | null
  statusLogs: TenderStatusLogDtoIn[]
  tenderFiles: TenderFileDtoIn[]
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
  | 'canceledAt'
  | 'acceptedAt'
  | 'refusedAt'
  | 'sentAt'
  | 'tenderFiles'
> & {
  opportunity: string
  status: string
  sentAt: string | null
  acceptedAt: string | null
  refusedAt: string | null
  canceledAt: string | null
}
export type NewTenderDtoOut = Omit<TenderDtoOut, 'id'>
