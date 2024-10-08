<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue'
import type { Ref } from 'vue'
import { useContactStore } from '@/stores/contact'
import { useCompanyStore } from '@/stores/company'

import ContactForm from '@/views/contacts/ContactForm.vue'
import type { Contact, ListContact } from '@/stores/contact/types'
import ContactRow from '@/views/contacts/ContactRow.vue'
import Mp3000Table from '@/components/Mp3000Table.vue'
import Mp3000TableHeader from '@/components/Mp3000TableHeader.vue'
import { useSorter, SortConfigTypeEnum } from '@/composables/useSorter'

const contactStore = useContactStore()
const companyStore = useCompanyStore()

const isLoading = ref(false)
const isFormLoading = ref(false)
const isFormShowing = ref(false)

const companies = computed(() => companyStore.companies)
const contacts = computed(() => contactStore.contacts)
const deletableIds = computed(() => contactStore.deletableIds)
const currentContact = computed(() => contactStore.currentContact)

const filterSearch = ref('')
const filterCompanyId = ref(null) as Ref<number | null>
const filteredContacts = computed(() =>
  contacts.value.filter((contact) => {
    if (null !== filterCompanyId.value && contact.company.id !== filterCompanyId.value) {
      return false
    }
    if (filterSearch.value.length < 1) {
      return true
    }
    return (contact.firstName + contact.lastName + contact.email)
      .toLowerCase()
      .includes(filterSearch.value.toLowerCase())
  })
)
const { getAsc, getPriority, sort, sortedList } = useSorter(
  [
    {
      property: 'name',
      type: SortConfigTypeEnum.CUSTOM,
      customCompare: (a: Contact, b: Contact) =>
        (a.firstName + a.lastName).localeCompare(b.firstName + b.lastName)
    },
    {
      property: 'company',
      type: SortConfigTypeEnum.CUSTOM,
      customCompare: (a: Contact, b: Contact) => a.company.name.localeCompare(b.company.name)
    },
    { property: 'email', type: SortConfigTypeEnum.STRING },
    { property: 'phone', type: SortConfigTypeEnum.STRING },
    { property: 'comments', type: SortConfigTypeEnum.STRING }
  ],
  filteredContacts
)

async function showForm(contact: ListContact | null) {
  isFormShowing.value = true
  if (null === contact) {
    contactStore.resetCurrentContact()
    return
  }
  isFormLoading.value = true
  await contactStore.fetchOne(contact.id)
  isFormLoading.value = false
}
function hideForm() {
  isFormShowing.value = false
  contactStore.resetCurrentContact()
}

onMounted(async () => {
  isLoading.value = true
  sort('company')
  await Promise.all([
    contactStore.fetch(),
    contactStore.fetchDeletables(),
    companies.value.length ? null : companyStore.fetch()
  ])
  isLoading.value = false
})
</script>

<template>
  <div>
    <h2>Contacts</h2>
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
      </template>
      <template v-slot:header>
        <tr>
          <mp3000-table-header
            :asc="getAsc('name')"
            :priority="getPriority('name')"
            @click="sort('name')"
            label="Nom"
          />
          <mp3000-table-header
            :asc="getAsc('company')"
            :priority="getPriority('company')"
            @click="sort('company')"
            label="Client"
          />
          <mp3000-table-header
            :asc="getAsc('email')"
            :priority="getPriority('email')"
            @click="sort('email')"
            label="Email"
          />
          <mp3000-table-header
            :asc="getAsc('phone')"
            :priority="getPriority('phone')"
            @click="sort('phone')"
            label="Téléphone"
          />
          <mp3000-table-header
            :asc="getAsc('comments')"
            :priority="getPriority('comments')"
            @click="sort('comments')"
            label="Commentaires"
          />
        </tr>
      </template>
      <template v-slot:body>
        <tr v-if="sortedList.length === 0">
          <td colspan="100">Aucun contact</td>
        </tr>
        <contact-row
          v-else
          v-for="contact in sortedList"
          :key="contact.id"
          :is-deletable="deletableIds.includes(contact.id)"
          :contact="contact"
          :with-details="true"
          @show-form="showForm(contact)"
        />
      </template>
    </mp3000-table>
    <contact-form
      :contact="currentContact"
      :is-showing="isFormShowing"
      @stop-showing="hideForm"
      :is-loading="isFormLoading"
    />
  </div>
</template>
