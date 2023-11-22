import {Contact, ListContact} from "../../src/stores/contact/types";
import {initCompanies} from "./company";

export function initContacts(): ListContact[] {
    const companies = initCompanies()
    return [
        {id: 1, firstName: 'Albert', lastName: 'De Bert', email: 'ccc', phone: null, company: companies[0]},
        {id: 2, firstName: 'Sophie', lastName: 'Da Silva', email: 'aaa', phone: '999', company: companies[1]},
        {id: 3, firstName: 'Martine', lastName: 'Zèbre', email: 'bbb', phone: '111', company: companies[2]},
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
        opportunities: [1], // todo
    }
}
