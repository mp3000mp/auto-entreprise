<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue'
import type { Ref } from 'vue'
import { useTenderStore } from '@/stores/tender'
import { useCompanyStore } from '@/stores/company'

import TenderForm from '@/views/tenders/TenderForm.vue'
import type { Tender, ListTender } from '@/stores/tender/types'
import TenderRow from '@/views/tenders/TenderRow.vue'
import Mp3000Table from '@/components/Mp3000Table.vue'
import { SortConfigTypeEnum, Sorter } from '@/misc/sorter'
import Mp3000TableHeader from '@/components/Mp3000TableHeader.vue'

const tenderStore = useTenderStore()
const companyStore = useCompanyStore()

const isLoading = ref(false)
const isFormLoading = ref(false)
const isFormShowing = ref(false)

const companies = computed(() => companyStore.companies)
const tenders = computed(() => tenderStore.tenders)
const deletableIds = computed(() => tenderStore.deletableIds)
const currentTender = computed(() => tenderStore.currentTender)

const filterSearch = ref('')
const filterCompanyId = ref(null) as Ref<number | null>
const filteredTenders = computed(() =>
  tenders.value.filter((tender) => {
    if (null !== filterCompanyId.value && tender.opportunity.company.id !== filterCompanyId.value) {
      return false
    }
    if (filterSearch.value.length < 3) {
      return true
    }
    return tender.opportunity.ref.toLowerCase().includes(filterSearch.value.toLowerCase())
  })
)
const sorter = new Sorter(
  [
    { property: 'version', type: SortConfigTypeEnum.NUMBER },
    {
      property: 'opportunity',
      type: SortConfigTypeEnum.CUSTOM,
      customCompare: (a: Tender, b: Tender) => a.opportunity.ref.localeCompare(b.opportunity.ref)
    },
    {
      property: 'company',
      type: SortConfigTypeEnum.CUSTOM,
      customCompare: (a: Tender, b: Tender) =>
        a.opportunity.company.name.localeCompare(b.opportunity.company.name)
    },
    {
      property: 'status',
      type: SortConfigTypeEnum.CUSTOM,
      customCompare: (a: Tender, b: Tender) => a.status.label.localeCompare(b.status.label)
    },
    { property: 'soldDays', type: SortConfigTypeEnum.NUMBER },
    {
      property: 'amount',
      type: SortConfigTypeEnum.CUSTOM,
      customCompare: (a: Tender, b: Tender) =>
        a.soldDays * a.averageDailyRate - b.soldDays * b.averageDailyRate
    },
    { property: 'createdAt', type: SortConfigTypeEnum.DATE }
  ],
  filteredTenders
)

async function showForm(tender: ListTender) {
  isFormShowing.value = true
  isFormLoading.value = true
  await tenderStore.fetchOne(tender.id)
  isFormLoading.value = false
}
function hideForm() {
  isFormShowing.value = false
  tenderStore.resetCurrentTender()
}

onMounted(async () => {
  sorter.addSort('createdAt', false)
  isLoading.value = true
  await Promise.all([
    tenderStore.fetch(),
    tenderStore.fetchDeletables(),
    companies.value.length ? null : companyStore.fetch()
  ])
  isLoading.value = false
})
</script>

<template>
  <div>
    <h2>Devis</h2>
    <mp3000-table :is-loading="isLoading">
      <template v-slot:filters>
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
      </template>
      <template v-slot:header>
        <tr>
          <mp3000-table-header property="version" :sorter="sorter" label="Version" />
          <mp3000-table-header property="opportunity" :sorter="sorter" label="Opportunité" />
          <mp3000-table-header property="company" :sorter="sorter" label="Client" />
          <mp3000-table-header property="status" :sorter="sorter" label="Statut" />
          <mp3000-table-header property="soldDays" :sorter="sorter" label="Jours vendus" />
          <mp3000-table-header property="amount" :sorter="sorter" label="Montant" />
          <mp3000-table-header property="createdAt" :sorter="sorter" label="Création" />
        </tr>
      </template>
      <template v-slot:body>
        <tr v-if="sorter.sortedList.value.length === 0">
          <td colspan="100">Aucun devis</td>
        </tr>
        <tender-row
          v-else
          v-for="tender in sorter.sortedList.value"
          :key="tender.id"
          :is-deletable="deletableIds.includes(tender.id)"
          :tender="tender"
          :opportunity="tender.opportunity"
          :with-details="true"
          @show-form="showForm(tender)"
        />
      </template>
    </mp3000-table>
    <tender-form
      v-if="currentTender?.opportunity"
      :tender="currentTender"
      :opportunity="currentTender.opportunity"
      :is-showing="isFormShowing"
      @stop-showing="hideForm"
      :is-loading="isFormLoading"
    />
  </div>
</template>
