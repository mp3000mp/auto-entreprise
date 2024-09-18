import type {
  Tender,
  TenderDtoIn,
  TenderDtoOut,
  NewTender,
  NewTenderDtoOut,
  TenderStatusLogDtoIn,
  TenderStatusLog,
  ListTenderDtoIn,
  ListTender,
  TenderRow,
  TenderRowDtoOut,
  NewTenderRowDtoOut,
  NewTenderRow,
  TenderFileDtoIn,
  TenderFile
} from '@/stores/tender/types'
import dayjs from '@/misc/dayjs'

function convertTenderStatusIn(rawStatus: TenderStatusLogDtoIn): TenderStatusLog {
  return {
    ...rawStatus,
    createdAt: dayjs(rawStatus.createdAt)
  }
}

export function convertTenderFileIn(rawFile: TenderFileDtoIn): TenderFile {
  return {
    ...rawFile,
    createdAt: dayjs(rawFile.createdAt)
  }
}

export function convertTenderIn(rawTender: TenderDtoIn): Tender {
  return {
    ...rawTender,
    createdAt: dayjs(rawTender.createdAt),
    sentAt: rawTender.sentAt ? dayjs(rawTender.sentAt) : null,
    acceptedAt: rawTender.acceptedAt ? dayjs(rawTender.acceptedAt) : null,
    refusedAt: rawTender.refusedAt ? dayjs(rawTender.refusedAt) : null,
    canceledAt: rawTender.canceledAt ? dayjs(rawTender.canceledAt) : null,
    statusLogs: rawTender.statusLogs.map((status) => convertTenderStatusIn(status)),
    tenderFiles: rawTender.tenderFiles.map((file) => convertTenderFileIn(file)),
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
export function convertTenderRowOut(
  tenderRow: TenderRow | NewTenderRow,
  tender: Tender
): TenderRowDtoOut | NewTenderRowDtoOut {
  return {
    ...tenderRow,
    tender: '/api/tenders/' + tender.id
  }
}
