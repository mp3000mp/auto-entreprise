<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue'
import type { Ref } from 'vue'
import { useCompanyStore } from '@/stores/company'

import CompanyForm from '@/views/companies/CompanyForm.vue'

import type { ListCompany } from '@/stores/company/types'

import Mp3000Table from '@/components/Mp3000Table.vue'
import CompanyRow from '@/views/companies/CompanyRow.vue'
import { SortConfigTypeEnum, Sorter } from '@/misc/sorter'
import Mp3000TableHeader from '@/components/Mp3000TableHeader.vue'

const companyStore = useCompanyStore()

const isLoading = ref(false)
const isFormShowing = ref(false)
const currentCompanyId = ref(null) as Ref<number | null>

const companies = computed(() => companyStore.companies)
const deletableIds = computed(() => companyStore.deletableIds)

const filerSearch = ref('')
const filteredCompanies = computed(() =>
  companies.value.filter((company) => {
    if (filerSearch.value.length < 3) {
      return true
    }
    return company.name.toLowerCase().includes(filerSearch.value.toLowerCase())
  })
)
const sorter = new Sorter(
  [{ property: 'name', type: SortConfigTypeEnum.STRING }],
  filteredCompanies
)

function showForm(company: ListCompany | null) {
  isFormShowing.value = true
  currentCompanyId.value = company?.id ?? null
}
function hideForm() {
  isFormShowing.value = false
  currentCompanyId.value = null
}

onMounted(async () => {
  companyStore.fetchDeletables()
  isLoading.value = true
  await companyStore.fetch()
  isLoading.value = false
})
</script>

<template>
  <div>
    <mp3000-table :is-loading="isLoading">
      <template v-slot:filters>
        <div class="col-auto">
          <div class="form-group">
            <label>Recherche</label>
            <input type="text" class="form-control" v-model="filerSearch" />
          </div>
        </div>
      </template>
      <template v-slot:header>
        <tr>
          <mp3000-table-header property="name" :sorter="sorter" label="Nom" />
        </tr>
      </template>
      <template v-slot:body>
        <company-row
          v-for="company in sorter.sortedList.value"
          :key="company.id"
          :is-deletable="deletableIds.includes(company.id)"
          :company="company"
          @show-form="showForm(company)"
        />
      </template>
    </mp3000-table>
    <div class="text-center my-5" v-if="isLoading">
      <div class="spinner-border">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    <button @click.prevent="showForm(null)" class="btn btn-primary">Nouveau</button>
    <company-form
      :company-id="currentCompanyId"
      :is-showing="isFormShowing"
      @stop-showing="hideForm"
    />
  </div>
</template>
