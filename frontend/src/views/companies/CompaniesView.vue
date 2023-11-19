<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue'
import type { Ref } from 'vue'
import { useCompanyStore } from '@/stores/company'

import CompanyForm from "@/views/companies/CompanyForm.vue";

import type {ListCompany} from '@/stores/company/types'

import Mp3000Icon from "@/components/Mp3000Icon.vue";

const companyStore = useCompanyStore()

const isLoading = ref(false)
const isFormShowing = ref(false)
const isRemoving = ref(false)
const currentCompanyId = ref(null) as Ref<number | null>

const companies = computed(() => companyStore.companies)
const deletableIds = computed(() => companyStore.deletableIds)

function showForm(company: ListCompany | null) {
  isFormShowing.value = true
  currentCompanyId.value = company?.id ?? null
}
async function remove(company: ListCompany) {
  isRemoving.value = false
  await companyStore.delete(company.id)
  isRemoving.value = true
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
            <mp3000-icon v-if="deletableIds.includes(company.id)" @click.prevent="remove(company)" icon="trash" title="Supprimer" :is-loading="isRemoving" />
            <router-link :to="{name: 'company', params: {id: company.id}}">{{ company.name }}</router-link>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
    <button @click.prevent="showForm(null)" class="btn btn-primary">Nouveau</button>
    <company-form :company-id="currentCompanyId" :is-showing="isFormShowing" @stop-showing="hideForm" />
  </div>
</template>
