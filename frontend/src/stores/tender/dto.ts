import type {
  Tender,
  TenderDtoIn,
  TenderDtoOut,
  NewTender,
  NewTenderDtoOut,
  TenderStatusLogDtoIn,
  TenderStatusLog,
  ListTenderDtoIn,
  ListTender
} from '@/stores/tender/types'
import dayjs from '@/misc/dayjs'

function convertTenderStatusIn(rawStatus: TenderStatusLogDtoIn): TenderStatusLog {
  return {
    ...rawStatus,
    createdAt: dayjs(rawStatus.createdAt)
  }
}

export function convertTenderIn(rawTender: TenderDtoIn): Tender {
  return {
    ...rawTender,
    createdAt: dayjs(rawTender.createdAt),
    sentAt: dayjs(rawTender.sentAt),
    acceptedAt: dayjs(rawTender.acceptedAt),
    refusedAt: dayjs(rawTender.refusedAt),
    canceledAt: dayjs(rawTender.canceledAt),
    statusLogs: rawTender.statusLogs.map((status) => convertTenderStatusIn(status)),
    ...('createdAt' in rawTender ? { createdAt: dayjs(rawTender.createdAt) } : {})
  }
}
export function convertListTenderIn(rawTender: ListTenderDtoIn): ListTender {
  return {
    ...rawTender,
    createdAt: dayjs(rawTender.createdAt)
  }
}

export function convertTenderOut(tender: Tender | NewTender): TenderDtoOut | NewTenderDtoOut {
  return {
    ...tender,
    opportunity: '/api/opportunities/' + tender.opportunity.id,
    status: '/api/tender_statuses/' + tender.status.id,
    sentAt: tender.sentAt?.format('YYYY-MM-DD') ?? null,
    acceptedAt: tender.acceptedAt?.format('YYYY-MM-DD') ?? null,
    refusedAt: tender.refusedAt?.format('YYYY-MM-DD') ?? null,
    canceledAt: tender.canceledAt?.format('YYYY-MM-DD') ?? null
  }
}
