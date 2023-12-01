import type {ListTender, Tender, TenderRow, TenderStatus, TenderStatusLog} from "@/stores/tender/types";
import {initOpportunities} from "./opportunity";
import dayjs from "@/misc/dayjs";

export function initTenderStatuses(): TenderStatus[] {
    return [
        {id: 1, position: 10, label: 'ongoing'},
        {id: 2, position: 20, label: 'sent'},
        {id: 3, position: 30, label: 'accepted'},
        {id: 4, position: 40, label: 'refused'},
        {id: 5, position: 50, label: 'canceled'},
    ]
}

export function initTenderStatusLog(status: TenderStatus, date: string): TenderStatusLog {
    return {
        id: 1,
        createdAt: dayjs(date),
        status,
    }
}

export function initTenderRows(): TenderRow[] {
    const rows = []
    for (let i = 1; i <= 3; i++) {
        rows.push({
            id: i,
            position: i*10,
            description: 'desc'+i,
            title: 'row'+i,
            soldDays: 1,
        })
    }
    return rows
}

export function initTenders(noOpportunity: boolean = true): ListTender[] {
    const opportunities = noOpportunity ? [] : initOpportunities()
    return [
        {
            id: 1,
            version: 1,
            opportunity: noOpportunity ? null : opportunities[0],
            status: initTenderStatuses()[0],
            createdAt: dayjs('2023-11-01'),
            averageDailyRate: 100,
            soldDays: 2,
            tenderRows: [],
        },
        {
            id: 2,
            version: 2,
            opportunity: noOpportunity ? null : opportunities[1],
            status: initTenderStatuses()[0],
            createdAt: dayjs('2023-11-02'),
            averageDailyRate: 100,
            soldDays: 1.5,
            tenderRows: [],
        },
        {
            id: 3,
            version: 3,
            opportunity: noOpportunity ? null : opportunities[2],
            status: initTenderStatuses()[0],
            createdAt: dayjs('2023-11-03'),
            averageDailyRate: 100,
            soldDays: 3,
            tenderRows: [],
        }
    ]
}

export function initEmptyTender(): Tender {
    return {
        id: 1,
        version: 1,
        status: initTenderStatuses()[0],
        statusLogs: [],
        opportunity: initOpportunities()[0],
        averageDailyRate: 100,
        soldDays: 2,
        createdAt: dayjs('2023-11-01'),
        sentAt: null,
        acceptedAt: null,
        refusedAt: null,
        canceledAt: null,
        comments: null,
        tenderRows: [],
        tenderFiles: [],
    }
}

export function initTender(): Tender {
    const sentDate = '2023-11-05'
    return {
        ...initEmptyTender(),
        status: initTenderStatuses()[1],
        statusLogs: [initTenderStatusLog(initTenderStatuses()[1], sentDate)],
        tenderRows: initTenderRows(),
    }
}
