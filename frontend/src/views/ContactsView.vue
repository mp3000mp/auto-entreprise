<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue'
import type { Ref } from 'vue'
import { useContactStore } from '@/stores/contact'

import ContactForm from '@/views/forms/ContactForm.vue'
import type { Contact } from '@/stores/contact/types'

const contactStore = useContactStore()

const isLoading = ref(false)
const isFormShowing = ref(false)
const currentContact = ref(null) as Ref<Contact | null>

const contacts = computed(() => contactStore.contacts)

function showForm(contact: Contact | null) {
  isFormShowing.value = true
  currentContact.value = contact ? { ...contact } : null
}
function hideForm() {
  isFormShowing.value = false
  currentContact.value = null
}

onMounted(async () => {
  isLoading.value = true
  await contactStore.fetchContacts()
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
        <tr v-for="contact in contacts" :key="contact.id">
          <td>
            <a href="#" @click.prevent="showForm(contact)" title="Editer">
              <font-awesome-icon :icon="['fa', 'pen-to-square']" />
            </a>
            {{ contact.firstName }} {{ contact.lastName }}
          </td>
        </tr>
        </tbody>
      </table>
    </div>
    <button @click.prevent="showForm(null)" class="btn btn-primary">Nouveau</button>
    <contact-form :contact="currentContact" :is-showing="isFormShowing" @stop-showing="hideForm" />
  </div>
</template>
