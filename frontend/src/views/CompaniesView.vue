<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue'
import type { Ref } from 'vue'
import { useCompanyStore } from '@/stores/company'

import CompanyForm from "@/views/forms/CompanyForm.vue";

import type {BaseCompany, Company} from '@/stores/company/types'

const companyStore = useCompanyStore()

const isLoading = ref(false)
const isFormShowing = ref(false)
const currentCompany = ref(null) as Ref<Company | null>

const companies = computed(() => companyStore.companies)

function showForm(company: Company | null) {
  isFormShowing.value = true
  currentCompany.value = company ? { ...company } : null
}
function hideForm() {
  isFormShowing.value = false
  currentCompany.value = null
}

onMounted(async () => {
  isLoading.value = true
  await companyStore.fetchCompanies()
  isLoading.value = false
})
</script>

<template>
  <div>
    <div class="text-center my-5" v-if="isLoading">
      <div class="spinner-border">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    <div class="table-responsive" v-else>
      <table class="table table-hover">
        <thead>
        <tr>
          <th>Nom</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="company in companies" :key="company.id">
          <td>
            <a href="#" @click.prevent="showForm(company)" title="Editer">
              <font-awesome-icon :icon="['fa', 'pen-to-square']" />
            </a>
            {{ company.name }}
          </td>
        </tr>
        </tbody>
      </table>
    </div>
    <button @click.prevent="showForm(null)" class="btn btn-primary">Nouveau</button>
    <company-form :company="currentCompany" :is-showing="isFormShowing" @stop-showing="hideForm" />
  </div>
</template>
