<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue'
import type { Ref } from 'vue'
import { useTenderStore } from '@/stores/tender'
import { useCompanyStore } from '@/stores/company'

import TenderForm from '@/views/tenders/TenderForm.vue'
import type { Tender, ListTender } from '@/stores/tender/types'
import TenderRow from '@/views/tenders/TenderRow.vue'
import Mp3000Table from '@/components/Mp3000Table.vue'
import Mp3000TableHeader from '@/components/Mp3000TableHeader.vue'
import { useSorter, SortConfigTypeEnum } from '@/composables/useSorter'

const tenderStore = useTenderStore()
const companyStore = useCompanyStore()

const isLoading = ref(false)
const isFormLoading = ref(false)
const isFormShowing = ref(false)

const companies = computed(() => companyStore.companies)
const statuses = computed(() => tenderStore.statuses)
const tenders = computed(() => tenderStore.tenders)
const deletableIds = computed(() => tenderStore.deletableIds)
const currentTender = computed(() => tenderStore.currentTender)

const filterSearch = ref('')
const filterCompanyId = ref(null) as Ref<number | null>
const filterStatusId = ref(null) as Ref<number | null>
const filteredTenders = computed(() =>
  tenders.value.filter((tender) => {
    if (null !== filterCompanyId.value && tender.opportunity.company.id !== filterCompanyId.value) {
      return false
    }
    if (null !== filterStatusId.value && tender.status.id !== filterStatusId.value) {
      return false
    }
    if (filterSearch.value.length < 1) {
      return true
    }
    return tender.opportunity.ref.toLowerCase().includes(filterSearch.value.toLowerCase())
  })
)

const { getAsc, getPriority, sort, sortedList } = useSorter(
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
      customCompare: (a: Tender, b: Tender) => a.totalRate - b.totalRate
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
  isLoading.value = true
  sort('createdAt', false)
  await Promise.all([
    statuses.value.length ? null : tenderStore.fetchStatuses(),
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
          <mp3000-table-header
            :asc="getAsc('version')"
            :priority="getPriority('version')"
            @click="sort('version')"
            label="Version"
          />
          <mp3000-table-header
            :asc="getAsc('opportunity')"
            :priority="getPriority('opportunity')"
            @click="sort('opportunity')"
            label="Opportunité"
          />
          <mp3000-table-header
            :asc="getAsc('company')"
            :priority="getPriority('company')"
            @click="sort('company')"
            label="Client"
          />
          <mp3000-table-header
            :asc="getAsc('status')"
            :priority="getPriority('status')"
            @click="sort('status')"
            label="Statut"
          />
          <mp3000-table-header
            :asc="getAsc('soldDays')"
            :priority="getPriority('soldDays')"
            @click="sort('soldDays')"
            label="Jours vendus"
          />
          <mp3000-table-header
            :asc="getAsc('amount')"
            :priority="getPriority('amount')"
            @click="sort('amount')"
            label="Montant"
          />
          <mp3000-table-header
            :asc="getAsc('createdAt')"
            :priority="getPriority('createdAt')"
            @click="sort('createdAt')"
            label="Création"
          />
        </tr>
      </template>
      <template v-slot:body>
        <tr v-if="sortedList.length === 0">
          <td colspan="100">Aucun devis</td>
        </tr>
        <tender-row
          v-else
          v-for="tender in sortedList"
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
