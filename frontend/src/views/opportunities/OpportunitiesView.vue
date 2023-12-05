<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue'
import { useOpportunityStore } from '@/stores/opportunity'
import { useCompanyStore } from '@/stores/company'

import type { Ref } from 'vue'

import OpportunityForm from '@/views/opportunities/OpportunityForm.vue'
import type { Opportunity, ListOpportunity } from '@/stores/opportunity/types'
import OpportunityRow from '@/views/opportunities/OpportunityRow.vue'
import Mp3000Table from '@/components/Mp3000Table.vue'
import { SortConfigTypeEnum, Sorter } from '@/misc/sorter'
import Mp3000TableHeader from '@/components/Mp3000TableHeader.vue'

const companyStore = useCompanyStore()
const opportunityStore = useOpportunityStore()

const isLoading = ref(false)
const isFormLoading = ref(false)
const isFormShowing = ref(false)

const companies = computed(() => companyStore.companies)
const statuses = computed(() => opportunityStore.statuses)
const opportunities = computed(() => opportunityStore.opportunities)
const deletableIds = computed(() => opportunityStore.deletableIds)
const currentOpportunity = computed(() => opportunityStore.currentOpportunity)

const filterSearch = ref('')
const filterCompanyId = ref(null) as Ref<number | null>
const filterStatusId = ref(null) as Ref<number | null>
const filteredOpportunities = computed(() =>
  opportunities.value.filter((opportunity) => {
    if (null !== filterCompanyId.value && opportunity.company.id !== filterCompanyId.value) {
      return false
    }
    if (null !== filterStatusId.value && opportunity.status.id !== filterStatusId.value) {
      return false
    }
    if (filterSearch.value.length < 3) {
      return true
    }
    return opportunity.ref.toLowerCase().includes(filterSearch.value.toLowerCase())
  })
)
const sorter = new Sorter(
  [
    { property: 'ref', type: SortConfigTypeEnum.STRING },
    {
      property: 'company',
      type: SortConfigTypeEnum.CUSTOM,
      customCompare: (a: Opportunity, b: Opportunity) =>
        a.company.name.localeCompare(b.company.name)
    },
    {
      property: 'status',
      type: SortConfigTypeEnum.CUSTOM,
      customCompare: (a: Opportunity, b: Opportunity) =>
        a.status.label.localeCompare(b.status.label)
    },
    {
      property: 'amount',
      type: SortConfigTypeEnum.CUSTOM,
      customCompare: (a: Opportunity, b: Opportunity) =>
        (a.lastTender?.soldDays ?? 0) * (a.lastTender.averageDailyRate ?? 0) -
        (b.lastTender?.soldDays ?? 0) * (b.lastTender.averageDailyRate ?? 0)
    },
    {
      property: 'soldDays',
      type: SortConfigTypeEnum.CUSTOM,
      customCompare: (a: Opportunity, b: Opportunity) =>
        (a.lastTender?.soldDays ?? 0) - (b.lastTender?.soldDays ?? 0)
    },
    { property: 'workedDays', type: SortConfigTypeEnum.NUMBER },
    { property: 'createdAt', type: SortConfigTypeEnum.DATE }
  ],
  filteredOpportunities
)

async function showForm(opportunity: ListOpportunity | null) {
  isFormShowing.value = true
  if (null === opportunity) {
    opportunityStore.resetCurrentOpportunity()
    return
  }
  isFormLoading.value = true
  await opportunityStore.fetchOne(opportunity.id)
  isFormLoading.value = false
}
function hideForm() {
  isFormShowing.value = false
  opportunityStore.resetCurrentOpportunity()
}

onMounted(async () => {
  sorter.addSort('createdAt', false)
  isLoading.value = true
  await Promise.all([
    statuses.value.length ? null : opportunityStore.fetchStatuses(),
    opportunityStore.fetch(),
    opportunityStore.fetchDeletables(),
    companies.value.length ? null : companyStore.fetch()
  ])
  isLoading.value = false
})
</script>

<template>
  <div>
    <h2>Opportunités</h2>
    <mp3000-table :is-loading="isLoading">
      <template v-slot:filters>
        <div class="col-auto">
          <button @click.prevent="showForm(null)" class="btn btn-primary mt-4">Nouveau</button>
        </div>
        <div class="col-auto">
          <div class="form-group">
            <label>Recherche</label>
            <input type="text" class="form-control" v-model="filterSearch" />
          </div>
        </div>
        <div class="col-auto">
          <div class="form-group">
            <label>Client</label>
            <select class="form-select" v-model="filterCompanyId">
              <option :value="null">-</option>
              <option v-for="company in companies" :key="company.id" :value="company.id">
                {{ company.name }}
              </option>
            </select>
          </div>
        </div>
        <div class="col-auto">
          <div class="form-group">
            <label>Statut</label>
            <select class="form-select" v-model="filterStatusId">
              <option :value="null">-</option>
              <option v-for="status in statuses" :key="status.id" :value="status.id">
                {{ status.label }}
              </option>
            </select>
          </div>
        </div>
      </template>
      <template v-slot:header>
        <tr>
          <mp3000-table-header property="ref" :sorter="sorter" label="Ref" />
          <mp3000-table-header property="company" :sorter="sorter" label="Client" />
          <mp3000-table-header property="status" :sorter="sorter" label="Statut" />
          <mp3000-table-header property="amount" :sorter="sorter" label="Montant" />
          <mp3000-table-header property="soldDays" :sorter="sorter" label="Jours vendus" />
          <mp3000-table-header property="workedDays" :sorter="sorter" label="Jours travaillés" />
          <mp3000-table-header property="createdAt" :sorter="sorter" label="Création" />
        </tr>
      </template>
      <template v-slot:body>
        <tr v-if="sorter.sortedList.value.length === 0">
          <td colspan="100">Aucune opportunité</td>
        </tr>
        <opportunity-row
          v-else
          v-for="opportunity in sorter.sortedList.value"
          :key="opportunity.id"
          :is-deletable="deletableIds.includes(opportunity.id)"
          :opportunity="opportunity"
          :with-details="true"
          @show-form="showForm(opportunity)"
        />
      </template>
    </mp3000-table>
    <opportunity-form
      :opportunity="currentOpportunity"
      :is-showing="isFormShowing"
      @stop-showing="hideForm"
      :is-loading="isFormLoading"
    />
  </div>
</template>
