import type {
  Opportunity,
  OpportunityDtoIn,
  OpportunityDtoOut,
  NewOpportunity,
  NewOpportunityDtoOut,
  OpportunityStatusLogDtoIn,
  OpportunityStatusLog,
  ListOpportunity,
  ListOpportunityDtoIn,
  OpportunityFileDtoIn,
  OpportunityFile
} from '@/stores/opportunity/types'
import dayjs from '@/misc/dayjs'
import { convertWorkedTimeIn } from '@/stores/workedTime/dto'

function convertOpportunityStatusIn(rawStatus: OpportunityStatusLogDtoIn): OpportunityStatusLog {
  return {
    ...rawStatus,
    createdAt: dayjs(rawStatus.createdAt)
  }
}

export function convertOpportunityFileIn(rawFile: OpportunityFileDtoIn): OpportunityFile {
  return {
    ...rawFile,
    createdAt: dayjs(rawFile.createdAt)
  }
}

export function convertOpportunityIn(rawOpportunity: OpportunityDtoIn): Opportunity {
  return {
    ...rawOpportunity,
    createdAt: dayjs(rawOpportunity.createdAt),
    trackedAt: dayjs(rawOpportunity.trackedAt),
    purchasedAt: rawOpportunity.purchasedAt ? dayjs(rawOpportunity.purchasedAt) : null,
    forecastedDelivery: rawOpportunity.forecastedDelivery
      ? dayjs(rawOpportunity.forecastedDelivery)
      : null,
    deliveredAt: rawOpportunity.deliveredAt ? dayjs(rawOpportunity.deliveredAt) : null,
    billedAt: rawOpportunity.billedAt ? dayjs(rawOpportunity.billedAt) : null,
    payedAt: rawOpportunity.payedAt ? dayjs(rawOpportunity.payedAt) : null,
    canceledAt: rawOpportunity.canceledAt ? dayjs(rawOpportunity.canceledAt) : null,
    statusLogs: rawOpportunity.statusLogs.map((status) => convertOpportunityStatusIn(status)),
    opportunityFiles: rawOpportunity.opportunityFiles.map((file) => convertOpportunityFileIn(file)),
    workedTimes: rawOpportunity.workedTimes.map((workedTime) => convertWorkedTimeIn(workedTime))
  }
}
export function convertListOpportunityIn(rawOpportunity: ListOpportunityDtoIn): ListOpportunity {
  return {
    ...rawOpportunity,
    createdAt: dayjs(rawOpportunity.createdAt),
    forecastedDelivery: rawOpportunity.forecastedDelivery
      ? dayjs(rawOpportunity.forecastedDelivery)
      : null
  }
}

export function convertOpportunityOut(
  opportunity: Opportunity | NewOpportunity
): OpportunityDtoOut | NewOpportunityDtoOut {
  return {
    ...opportunity,
    company: '/api/companies/' + opportunity.company.id,
    meanOfPayment: opportunity.meanOfPayment
      ? '/api/mean_of_payments/' + opportunity.meanOfPayment.id
      : null,
    status: '/api/opportunity_statuses/' + opportunity.status.id,
    trackedAt: opportunity.trackedAt.format('YYYY-MM-DD'),
    purchasedAt: opportunity.purchasedAt?.format('YYYY-MM-DD') ?? null,
    forecastedDelivery: opportunity.forecastedDelivery?.format('YYYY-MM-DD') ?? null,
    deliveredAt: opportunity.deliveredAt?.format('YYYY-MM-DD') ?? null,
    billedAt: opportunity.billedAt?.format('YYYY-MM-DD') ?? null,
    payedAt: opportunity.payedAt?.format('YYYY-MM-DD') ?? null,
    canceledAt: opportunity.canceledAt?.format('YYYY-MM-DD') ?? null
  }
}
