<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue'
import { useCompanyStore } from '@/stores/company'

import CompanyForm from '@/views/companies/CompanyForm.vue'

import type { ListCompany } from '@/stores/company/types'

import Mp3000Table from '@/components/Mp3000Table.vue'
import CompanyRow from '@/views/companies/CompanyRow.vue'
import Mp3000TableHeader from '@/components/Mp3000TableHeader.vue'
import { useSorter, SortConfigTypeEnum } from '@/composables/useSorter'

const companyStore = useCompanyStore()

const isLoading = ref(false)
const isFormLoading = ref(false)
const isFormShowing = ref(false)

const companies = computed(() => companyStore.companies)
const deletableIds = computed(() => companyStore.deletableIds)
const currentCompany = computed(() => companyStore.currentCompany)

const filterSearch = ref('')
const filteredCompanies = computed(() =>
  companies.value.filter((company) => {
    if (filterSearch.value.length < 1) {
      return true
    }
    return company.name.toLowerCase().includes(filterSearch.value.toLowerCase())
  })
)
const { getAsc, getPriority, sort, sortedList } = useSorter(
  [{ property: 'name', type: SortConfigTypeEnum.STRING }],
  filteredCompanies
)

async function showForm(company: ListCompany | null) {
  isFormShowing.value = true
  if (null === company) {
    companyStore.resetCurrentCompany()
    return
  }
  isFormLoading.value = true
  await companyStore.fetchOne(company.id)
  isFormLoading.value = false
}
function hideForm() {
  isFormShowing.value = false
  companyStore.resetCurrentCompany()
}

onMounted(async () => {
  isLoading.value = true
  sort('name')
  await Promise.all([companyStore.fetch(), companyStore.fetchDeletables()])
  isLoading.value = false
})
</script>

<template>
  <div>
    <h2>Clients</h2>
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
      </template>
      <template v-slot:header>
        <tr>
          <mp3000-table-header
            :asc="getAsc('name')"
            :priority="getPriority('name')"
            @click="sort('name')"
            label="Nom"
          />
        </tr>
      </template>
      <template v-slot:body>
        <tr v-if="sortedList.length === 0">
          <td colspan="100">Aucun client</td>
        </tr>
        <company-row
          v-else
          v-for="company in sortedList"
          :key="company.id"
          :is-deletable="deletableIds.includes(company.id)"
          :company="company"
          @show-form="showForm(company)"
        />
      </template>
    </mp3000-table>
    <company-form
      :company="currentCompany"
      :is-showing="isFormShowing"
      @stop-showing="hideForm"
      :is-loading="isFormLoading"
    />
  </div>
</template>
