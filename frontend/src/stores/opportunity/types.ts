import type { ListCompany } from '@/stores/company/types'
import type { ListContact } from '@/stores/contact/types'
import type { Dayjs } from 'dayjs'
import type { ListTender, OpportunityTender } from '@/stores/tender/types'

export type MeanOfPayment = {
  id: number
  position: number
  label: string
}

export type OpportunityStatus = {
  id: number
  position: number
  label: string
}

export type OpportunityStatusLog = {
  id: number
  status: OpportunityStatus
  createdAt: Dayjs
}
export type OpportunityStatusLogDtoIn = Omit<OpportunityStatusLog, 'createdAt'> & {
  createdAt: string
}

export enum OpportunityFileTypeEnum {
  INVOICE = 'invoice',
  ORDER = 'order',
  OTHER = 'other'
}
export const opportunityFileTypeLabels = {
  [OpportunityFileTypeEnum.INVOICE]: 'Facture',
  [OpportunityFileTypeEnum.ORDER]: 'Commande',
  [OpportunityFileTypeEnum.OTHER]: 'Autre'
}

export type OpportunityFile = {
  id: number
  type: OpportunityFileTypeEnum
  title: string
  description: string
  createdAt: Dayjs
}
export type OpportunityFileDtoIn = Omit<OpportunityFile, 'createdAt'> & {
  createdAt: string
}

export type Opportunity = {
  id: number
  ref: string
  description: string
  company: ListCompany
  createdAt: Dayjs
  trackedAt: Dayjs
  purchasedAt: Dayjs | null
  forecastedDelivery: Dayjs | null
  deliveredAt: Dayjs | null
  billedAt: Dayjs | null
  payedAt: Dayjs | null
  canceledAt: Dayjs | null
  customerRef1: string | null
  customerRef2: string | null
  paymentRef: string | null
  contacts: ListContact[]
  status: OpportunityStatus
  meanOfPayment: MeanOfPayment | null
  tenders: ListTender[]
  lastTender: OpportunityTender
  comments: string | null
  statusLogs: OpportunityStatusLog[]
  opportunityFiles: OpportunityFile[]
}
export type NewOpportunity = Omit<
  Opportunity,
  'id' | 'contacts' | 'tenders' | 'statusLogs' | 'opportunityFiles'
>
export type ListOpportunity = Pick<
  Opportunity,
  'id' | 'company' | 'ref' | 'status' | 'createdAt' | 'forecastedDelivery' | 'lastTender'
>
export type TenderOpportunity = Omit<ListOpportunity, 'createdAt' | 'lastTender'>
export type OpportunityDtoIn = Omit<
  Opportunity,
  | 'createdAt'
  | 'trackedAt'
  | 'purchasedAt'
  | 'forecastedDelivery'
  | 'deliveredAt'
  | 'billedAt'
  | 'payedAt'
  | 'canceledAt'
  | 'statusLogs'
  | 'opportunityFiles'
> & {
  createdAt: string
  trackedAt: string
  purchasedAt: string | null
  forecastedDelivery: string | null
  deliveredAt: string | null
  billedAt: string | null
  payedAt: string | null
  canceledAt: string | null
  statusLogs: OpportunityStatusLogDtoIn[]
  opportunityFiles: OpportunityFileDtoIn[]
}
export type ListOpportunityDtoIn = Omit<ListOpportunity, 'createdAt' | 'forecastedDelivery'> & {
  createdAt: string
  forecastedDelivery: string | null
}
export type OpportunityDtoOut = Omit<
  Opportunity,
  | 'company'
  | 'status'
  | 'meanOfPayment'
  | 'createdAt'
  | 'trackedAt'
  | 'purchasedAt'
  | 'forecastedDelivery'
  | 'deliveredAt'
  | 'billedAt'
  | 'payedAt'
  | 'canceledAt'
  | 'contacts'
  | 'tenders'
  | 'statusLogs'
  | 'opportunityFiles'
> & {
  company: string
  status: string
  meanOfPayment: string | null
  trackedAt: string
  purchasedAt: string | null
  forecastedDelivery: string | null
  deliveredAt: string | null
  billedAt: string | null
  payedAt: string | null
  canceledAt: string | null
}
export type NewOpportunityDtoOut = Omit<OpportunityDtoOut, 'id'>
