import type { ListContact } from '@/stores/contact/types'
import type { ListOpportunity } from '@/stores/opportunity/types'

export interface Company {
  id: number
  name: string
  street1: string
  street2: string | null
  postCode: string
  city: string
  contacts: ListContact[]
  opportunities: ListOpportunity[]
}
export type NewCompany = Omit<Company, 'id' | 'contacts' | 'opportunities'>
export type ListCompany = Pick<Company, 'id' | 'name'>
