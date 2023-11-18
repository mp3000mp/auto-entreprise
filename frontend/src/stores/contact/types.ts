import type {BaseCompany} from "@/stores/company/types";

export type BaseContact = {
    id: number;
    firstName: string;
    lastName: string;
}

export type ContactDtoOut = BaseContact & {
    company: string;
}
export type ContactList = BaseContact & {
    company: BaseCompany;
}
export type Contact = ContactList & {
    email: string;
    phone: string;
    comments: string;
    // opportunities
}
