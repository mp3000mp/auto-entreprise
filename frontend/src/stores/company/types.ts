import type {BaseContact, ContactList} from "@/stores/contact/types";

export type BaseCompany = {
    id: number
    name: string;
}

export type Company = BaseCompany & {
    street1: string;
    street2: string;
    postCode: string;
    city: string;
    // opportunities
}

export type CompanyFull = Company & {
    contacts: BaseContact;
}
