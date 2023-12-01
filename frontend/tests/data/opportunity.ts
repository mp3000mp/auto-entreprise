import {
    ListOpportunity, MeanOfPayment,
    Opportunity,
    OpportunityStatus,
    OpportunityStatusLog
} from "@/stores/opportunity/types";
import {initCompanies} from "./company";
import dayjs from "@/misc/dayjs";
import {initTenders} from "./tender";
import {initWorkedTimes} from "./workedTime";

export function initMeanOfPayments(): MeanOfPayment[] {
    return [
        {id: 1, position: 10, label: 'mop1'},
        {id: 2, position: 10, label: 'mop2'},
        {id: 3, position: 10, label: 'mop3'},
    ]
}

export function initOpportunityStatuses(): OpportunityStatus[] {
    return [
        {id: 1, position: 10, label: 'tracked'},
        {id: 2, position: 20, label: 'need_ongoing'},
        {id: 3, position: 30, label: 'need_sent'},
        {id: 4, position: 40, label: 'tender_ongoing'},
        {id: 5, position: 50, label: 'tender_sent'},
        {id: 6, position: 60, label: 'dev_ongoing'},
        {id: 7, position: 70, label: 'delivered'},
        {id: 8, position: 80, label: 'billed'},
        {id: 9, position: 90, label: 'payed'},
        {id: 10, position: 100, label: 'canceled'},
    ]
}

export function initOpportunityStatusLog(status: OpportunityStatus, date: string): OpportunityStatusLog {
    return {
        id: 1,
        createdAt: dayjs(date),
        status,
    }
}

export function initOpportunities(noTender: boolean = true): ListOpportunity[] {
    const companies = initCompanies()
    const tenders = noTender ? [] : initTenders()
    return [
        {
            id: 1,
            ref: 'opp1',
            company: companies[0],
            status: initOpportunityStatuses()[0],
            createdAt: dayjs('2023-11-01'),
            forecastedDelivery: null,
            lastTender: noTender ? null : tenders[0],
            workedDays: 1.5,
        },
        {
            id: 2,
            ref: 'opp2',
            company: companies[1],
            status: initOpportunityStatuses()[0],
            createdAt: dayjs('2023-11-02'),
            forecastedDelivery: null,
            lastTender: noTender ? null : tenders[1],
            workedDays: 2,
        },
        {
            id: 3,
            ref: 'opp3',
            company: companies[2],
            status: initOpportunityStatuses()[0],
            createdAt: dayjs('2023-11-03'),
            forecastedDelivery: null,
            lastTender: noTender ? null : tenders[2],
            workedDays: 0,
        }
    ]
}

export function initEmptyOpportunity(): Opportunity {
    return {
        ...initOpportunities()[0],
        description: 'opp desc 1',
        contacts: [],
        status: initOpportunityStatuses()[0],
        statusLogs: [],
        trackedAt: dayjs('2023-11-01'),
        purchasedAt: null,
        forecastedDelivery: null,
        deliveredAt: null,
        billedAt: null,
        payedAt: null,
        canceledAt: null,
        lastTender: null,
        tenders: [],
        meanOfPayment: null,
        customerRef1: null,
        customerRef2: null,
        paymentRef: null,
        opportunityFiles: [],
        comments: null,
        workedDays: 0,
        workedTimes: [],
    }
}

export function initOpportunity(): Opportunity {
    const trackedDate = '2023-11-05'
    return {
        ...initEmptyOpportunity(),
        statusLogs: [initOpportunityStatusLog(initOpportunityStatuses()[0], trackedDate)],
        trackedAt: dayjs(trackedDate),
        lastTender: initTenders()[0],
        tenders: initTenders(),
        meanOfPayment: initMeanOfPayments()[0],
        workedDays: 2.25,
        workedTimes: initWorkedTimes(),
    }
}
