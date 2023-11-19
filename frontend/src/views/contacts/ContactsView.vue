<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue'
import type { Ref } from 'vue'
import { useContactStore } from '@/stores/contact'

import ContactForm from '@/views/contacts/ContactForm.vue'
import type { ListContact} from '@/stores/contact/types'
import Mp3000Icon from "@/components/Mp3000Icon.vue";

const contactStore = useContactStore()

const isLoading = ref(false)
const isFormShowing = ref(false)
const isRemoving = ref(false)
const currentContactId = ref(null) as Ref<number | null>

const contacts = computed(() => contactStore.contacts)
const deletableIds = computed(() => contactStore.deletableIds)

function showForm(contact: ListContact | null) {
  isFormShowing.value = true
  currentContactId.value = contact?.id ?? null
}
async function remove(contact: ListContact) {
  isRemoving.value = false
  await contactStore.delete(contact.id)
  isRemoving.value = true
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
          <th>Client</th>
          <th>Email</th>
          <th>Téléphone</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="contact in contacts" :key="contact.id">
          <td>
            <a href="#" @click.prevent="showForm(contact)" title="Editer">
              <font-awesome-icon :icon="['fa', 'pen-to-square']" />
            </a>
            <mp3000-icon v-if="deletableIds.includes(contact.id)" @click.prevent="remove(contact)" icon="trash" title="Supprimer" :is-loading="isRemoving" />
            <router-link :to="{name: 'contact', params: {id: contact.id}}">{{ contact.firstName }} {{ contact.lastName }}</router-link>
          </td>
          <td>
            <router-link :to="{name: 'company', params: {id: contact.company.id}}">{{ contact.company.name }}</router-link>
          </td>
          <td>{{ contact.email }}</td>
          <td>{{ contact.phone }}</td>
        </tr>
        </tbody>
      </table>
    </div>
    <button @click.prevent="showForm(null)" class="btn btn-primary">Nouveau</button>
    <contact-form :contact-id="currentContactId" :is-showing="isFormShowing" @stop-showing="hideForm" />
  </div>
</template>
