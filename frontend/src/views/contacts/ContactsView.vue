<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue'
import type { Ref } from 'vue'
import { useContactStore } from '@/stores/contact'

import ContactForm from '@/views/contacts/ContactForm.vue'
import type { ListContact} from '@/stores/contact/types'
import Mp3000Icon from "@/components/Mp3000Icon.vue";
import ContactRow from "@/views/contacts/ContactRow.vue";
import Mp3000Table from "@/components/Mp3000Table.vue";
import CostRow from "@/views/costs/CostRow.vue";

const contactStore = useContactStore()

const isLoading = ref(false)
const isFormShowing = ref(false)
const currentContactId = ref(null) as Ref<number | null>

const contacts = computed(() => contactStore.contacts)
const deletableIds = computed(() => contactStore.deletableIds)

const filerSearch = ref('')
const filteredContacts = computed(() => contacts.value.filter(contact => {
  if (filerSearch.value.length < 3) {
    return true
  }
  return (contact.firstName+contact.lastName+contact.email).toLowerCase().includes(filerSearch.value.toLowerCase())
}))

function showForm(contact: ListContact | null) {
  isFormShowing.value = true
  currentContactId.value = contact?.id ?? null
}
function hideForm() {
  isFormShowing.value = false
  currentContactId.value = null
}

onMounted(async () => {
  contactStore.fetchDeletables()
  isLoading.value = true
  await contactStore.fetch()
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
            <input
                type="text"
                class="form-control"
                v-model="filerSearch"
            />
          </div>
        </div>
      </template>
      <template v-slot:header>
        <tr>
          <th>Nom</th>
          <th>Client</th>
          <th>Email</th>
          <th>Téléphone</th>
        </tr>
      </template>
      <template v-slot:body>
        <contact-row v-for="contact in filteredContacts" :key="contact.id" :is-deletable="deletableIds.includes(contact.id)" :contact="contact" @show-form="showForm(contact)" />
      </template>
    </mp3000-table>
    <button @click.prevent="showForm(null)" class="btn btn-primary">Nouveau</button>
    <contact-form :contact-id="currentContactId" :is-showing="isFormShowing" @stop-showing="hideForm" />
  </div>
</template>
