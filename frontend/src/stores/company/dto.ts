import type { Company, CompanyDtoIn } from '@/stores/company/types'
import { convertListOpportunityIn } from '@/stores/opportunity/dto'

export function convertCompanyIn(rawCompany: CompanyDtoIn): Company {
  return {
    ...rawCompany,
    opportunities: rawCompany.opportunities.map((rawOpportunity) =>
      convertListOpportunityIn(rawOpportunity)
    )
  }
}
