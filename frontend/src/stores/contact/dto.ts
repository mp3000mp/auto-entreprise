import type {Contact, ContactDtoOut} from "@/stores/contact/types";

export function convertContactOut(contact: Contact): ContactDtoOut {
    return {
        ...contact,
        company: '/api/companies/' + contact.company.id,
    }
}
