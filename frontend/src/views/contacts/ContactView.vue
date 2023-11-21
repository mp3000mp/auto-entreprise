<script lang="ts" setup>
import { useContactStore } from '@/stores/contact'
import { computed, onMounted, ref } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import ContactForm from '@/views/contacts/ContactForm.vue'
import Mp3000Icon from '@/components/Mp3000Icon.vue'
import { useRouter } from 'vue-router'

const contactStore = useContactStore()
const router = useRouter()
const props = defineProps<{
  contactId: number
}>()

const isFormShowing = ref(false)
const isRemoving = ref(false)
const confirmMessage = computed(
  () => 'Confirmer la suppression de ' + contact.value?.firstName + ' ' + contact.value?.lastName
)

const contact = computed(() => contactStore.currentContact)
const isDeletable = computed(
  () => null === contact.value || contact.value.opportunities.length === 0
)

function showForm() {
  isFormShowing.value = true
}
function hideForm() {
  isFormShowing.value = false
}

async function remove() {
  isRemoving.value = true
  await contactStore.delete(props.contactId)
  router.push({ name: 'contacts' })
}

onMounted(() => {
  contactStore.fetchOne(props.contactId)
})
</script>

<template>
  <div class="text-center my-5" v-if="null === contact">
    <div class="spinner-border">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
  <div v-else>
    <h2>
      {{ contact.firstName }} {{ contact.lastName }}
      <a href="#" @click.prevent="showForm()" title="Editer">
        <font-awesome-icon :icon="['fa', 'pen-to-square']" />
      </a>
      <mp3000-icon
        class="me-1"
        v-if="isDeletable"
        :confirm-message="confirmMessage"
        @click="remove()"
        icon="trash"
        title="Supprimer"
        :is-loading="isRemoving"
      />
    </h2>
    <h3>Contact</h3>
    <p>
      <router-link :to="{ name: 'company', params: { id: contact.company.id } }">{{
        contact.company.name
      }}</router-link>
      <br />
      <font-awesome-icon :icon="['fa', 'envelope']" />
      {{ contact.email }}
      <br />
      <font-awesome-icon :icon="['fa', 'phone']" />
      {{ contact.phone }}
    </p>
    <h3>Opportunités</h3>
    <div>todo</div>
    <contact-form :contact="contact" :is-showing="isFormShowing" @stop-showing="hideForm" />
  </div>
</template>
