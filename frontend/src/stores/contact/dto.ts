import type {
  Contact,
  ContactDtoIn,
  ContactDtoOut,
  NewContact,
  NewContactDtoOut
} from '@/stores/contact/types'
import { convertListOpportunityIn } from '@/stores/opportunity/dto'

export function convertContactIn(rawContact: ContactDtoIn): Contact {
  return {
    ...rawContact,
    opportunities: rawContact.opportunities.map((rawOpportunity) =>
      convertListOpportunityIn(rawOpportunity)
    )
  }
}

export function convertContactOut(contact: Contact | NewContact): ContactDtoOut | NewContactDtoOut {
  return {
    ...contact,
    company: '/api/companies/' + contact.company.id
  }
}
