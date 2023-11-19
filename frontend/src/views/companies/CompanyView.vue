<script lang="ts" setup>
import {useCompanyStore} from "@/stores/company";
import type {Company} from "@/stores/company/types";
import {computed, onMounted} from "vue";

const companyStore = useCompanyStore()
const props = defineProps<{
  companyId: number
}>()

const company = computed(() => companyStore.currentCompany)

onMounted(() => {
  companyStore.fetchOne(props.companyId)
})
</script>

<template>
  <div class="text-center my-5" v-if="null === company">
    <div class="spinner-border">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
  <div v-else>
    <h2>{{ company.name }}</h2>
    <h3>Client</h3>
    <p>
      {{ company.street1 }}<br />
      {{ company.street2 }}<br />
      {{ company.city }}<br />
      {{ company.postCode }}
    </p>

    <h3>Opportunités</h3>
    <div>todo</div>

    <h3>Contacts</h3>
    <div class="table-responsive">
      <table class="table table-hover">
        <thead>
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th>Téléphone</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="contact in company.contacts" :key="contact.id">
          <td><router-link :to="{name: 'contact', params: {id: contact.id}}">{{ contact.firstName }} {{ contact.lastName }}</router-link></td>
          <td>{{ contact.email }}</td>
          <td>{{ contact.phone }}</td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
