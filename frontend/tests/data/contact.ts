import type {Contact, ListContact} from "@/stores/contact/types";
import {initCompanies} from "./company";
import {initOpportunities} from "./opportunity";

export function initContacts(): ListContact[] {
    const companies = initCompanies()
    return [
        {id: 1, firstName: 'Albert', lastName: 'De Bert', email: 'ccc', phone: null, company: companies[0], comments: 'Comment 1'},
        {id: 2, firstName: 'Sophie', lastName: 'Da Silva', email: 'aaa', phone: '999', company: companies[1], comments: 'Comment 2'},
        {id: 3, firstName: 'Martine', lastName: 'Zèbre', email: 'bbb', phone: '111', company: companies[2], comments: 'Comment 3'},
    ]
}

export function initEmptyContact(): Contact {
    return {
        id: 1,
        company: initCompanies()[0],
        firstName: 'Jean',
        lastName: 'Bon',
        email: 'user@mp3000.fr',
        phone: null,
        comments: '',
        opportunities: [],
    }
}

export function initContact(): Contact {
    return {
        ...initEmptyContact(),
        opportunities: initOpportunities(),
    }
}
