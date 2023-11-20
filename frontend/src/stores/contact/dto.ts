import type { Contact, ContactDtoOut, NewContact, NewContactDtoOut } from '@/stores/contact/types'

export function convertContactOut(contact: Contact | NewContact): ContactDtoOut | NewContactDtoOut {
  return {
    ...contact,
    company: '/api/companies/' + contact.company.id
  }
}
