import {Company, ListCompany} from "../../src/stores/company/types";
import {initContacts} from "./contact";

export function initCompanies(): ListCompany[] {
    return [
        {id: 1, name: 'first'},
        {id: 2, name: 'second'},
        {id: 3, name: 'third'},
    ]
}

export function initEmptyCompany(): Company {
    return {
        id: 1,
        name: 'MP3000',
        street1: '1 rue du first',
        street2: null,
        city: 'VERSAILLES',
        postCode: '78000',
        contacts: [],
        opportunities: [],
    }
}

export function initCompany(): Company {
    return {
        ...initEmptyCompany(),
        contacts: initContacts(),
        opportunities: [], // todo
    }
}
