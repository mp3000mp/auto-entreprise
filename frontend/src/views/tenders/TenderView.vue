<script lang="ts" setup>
import { useTenderStore } from '@/stores/tender'
import { computed, onMounted, ref } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import TenderForm from '@/views/tenders/TenderForm.vue'
import Mp3000Icon from '@/components/Mp3000Icon.vue'
import { useRouter } from 'vue-router'
import BootstrapLoader from '@/components/BootstrapLoader.vue'

const tenderStore = useTenderStore()
const router = useRouter()
const props = defineProps<{
  tenderId: number
}>()

const isFormShowing = ref(false)
const isRemoving = ref(false)
const confirmMessage = computed(
  () =>
    'Confirmer la suppression de ' +
    tender.value?.opportunity.ref +
    ' version ' +
    tender.value?.version
)

const tender = computed(() => tenderStore.currentTender)
const isDeletable = computed(() => null === tender.value || tender.value.tenderRows.length === 0)

function showForm() {
  isFormShowing.value = true
}
function hideForm() {
  isFormShowing.value = false
}

async function remove() {
  isRemoving.value = true
  await tenderStore.delete(props.tenderId)
  tenderStore.resetCurrentTender()
  router.push({ name: 'tenders' })
}

onMounted(async () => {
  await tenderStore.fetchOne(props.tenderId)
})
</script>

<template>
  <bootstrap-loader v-if="null === tender" />
  <div v-else>
    <h2>
      <router-link :to="{ name: 'opportunity', params: { id: tender.opportunity.id } }">{{
        tender.opportunity.ref
      }}</router-link>
      {{ tender.version }}
      <a href="#" @click.prevent="showForm()" title="Editer" class="me-1">
        <font-awesome-icon :icon="['fa', 'pen-to-square']" />
      </a>
      <mp3000-icon
        v-if="isDeletable"
        :confirm-message="confirmMessage"
        @click="remove()"
        icon="trash"
        title="Supprimer"
        :is-loading="isRemoving"
      />
    </h2>
    <h3>Tender</h3>
    <p>
      <router-link :to="{ name: 'company', params: { id: tender.opportunity.company.id } }">{{
        tender.opportunity.company.name
      }}</router-link>
      <br />
      Statut: {{ tender.status.label }}<br />
      Date d'envoi: {{ tender.sentAt?.format('YYYY-MM-DD') ?? '-' }}<br />
      Date de commande': {{ tender.acceptedAt?.format('YYYY-MM-DD') ?? '-' }}<br />
      Date de refus: {{ tender.refusedAt?.format('YYYY-MM-DD') ?? '-' }}<br />
      Date d'annulation: {{ tender.canceledAt?.format('YYYY-MM-DD') ?? '-' }}<br />
    </p>
    <h3>Lignes</h3>
    <div>todo</div>
    <tender-form :tender="tender" :is-showing="isFormShowing" @stop-showing="hideForm" />
  </div>
</template>
