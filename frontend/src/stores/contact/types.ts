import type {ListCompany} from "@/stores/company/types";

export interface Contact {
    id: number
    firstName: string
    lastName: string
    company: ListCompany;
    email: string
    phone: string|null
    comments: string|null
}
export type NewContact = Omit<Contact, 'id'>
export type ListContact = Omit<Contact, 'comments'>
export type ContactDtoOut = Omit<Contact, 'company'> & {
    company: string
}
export type NewContactDtoOut = Omit<ContactDtoOut, 'id'>
