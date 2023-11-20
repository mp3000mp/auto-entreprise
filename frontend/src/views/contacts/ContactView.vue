<script lang="ts" setup>
import { useContactStore } from '@/stores/contact'
import { computed, onMounted } from 'vue'

const contactStore = useContactStore()
const props = defineProps<{
  contactId: number
}>()

const contact = computed(() => contactStore.currentContact)

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
    <h2>{{ contact.firstName }} {{ contact.lastName }}</h2>
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
  </div>
</template>
