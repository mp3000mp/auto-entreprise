import type { ListCompany } from '@/stores/company/types'
import type { Opportunity } from '@/stores/opportunity/types'

export interface Contact {
  id: number
  firstName: string
  lastName: string
  company: ListCompany
  email: string
  phone: string | null
  comments: string | null
  opportunities: Opportunity[]
}
export type NewContact = Omit<Contact, 'id' | 'opportunities'>
export type ListContact = Omit<Contact, 'comments' | 'opportunities'>
export type ContactDtoOut = Omit<Contact, 'company' | 'opportunities'> & {
  company: string
}
export type NewContactDtoOut = Omit<ContactDtoOut, 'id' | 'opportunities'>
