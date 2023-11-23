<script lang="ts" setup>
import { useOpportunityStore } from '@/stores/opportunity'
import { useTenderStore } from '@/stores/tender'
import { computed, onMounted, ref } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import OpportunityForm from '@/views/opportunities/OpportunityForm.vue'
import Mp3000Icon from '@/components/Mp3000Icon.vue'
import { useRouter } from 'vue-router'
import BootstrapLoader from '@/components/BootstrapLoader.vue'
import TenderForm from '@/views/tenders/TenderForm.vue'

const opportunityStore = useOpportunityStore()
const tenderStore = useTenderStore()
const router = useRouter()
const props = defineProps<{
  opportunityId: number
}>()

const isFormShowing = ref(false)
const isRemoving = ref(false)
const confirmMessage = computed(() => 'Confirmer la suppression de ' + opportunity.value?.ref)

const opportunity = computed(() => opportunityStore.currentOpportunity)
const isDeletable = computed(
  () => null === opportunity.value || opportunity.value.tenders.length === 0
)

function showForm() {
  isFormShowing.value = true
}
function hideForm() {
  isFormShowing.value = false
}

async function remove() {
  isRemoving.value = true
  await opportunityStore.delete(props.opportunityId)
  opportunityStore.resetCurrentOpportunity()
  router.push({ name: 'opportunities' })
}

const isTenderFormShowing = ref(false)

async function showTenderForm() {
  isTenderFormShowing.value = true
  tenderStore.resetCurrentTender()
}
function hideTenderForm() {
  isTenderFormShowing.value = false
  tenderStore.resetCurrentTender()
}

onMounted(async () => {
  await opportunityStore.fetchOne(props.opportunityId)
})
</script>

<template>
  <bootstrap-loader v-if="null === opportunity" />
  <div v-else>
    <h2>
      {{ opportunity.ref }}
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
    <h3>Opportunity</h3>
    <p>
      <router-link :to="{ name: 'company', params: { id: opportunity.company.id } }">{{
        opportunity.company.name
      }}</router-link>
      <br />
      Description: {{ opportunity.description }}<br />
      Contacts:
      {{ opportunity.contacts.map((contact) => contact.firstName + ' ' + contact.lastName) }}<br />
      Statut: {{ opportunity.status.label }}<br />
      Date de besoin: {{ opportunity.trackedAt.format('YYYY-MM-DD') }}<br />
      Date d'achat: {{ opportunity.purchasedAt?.format('YYYY-MM-DD') ?? '-' }}<br />
      Date de rendu prévisionnelle: {{ opportunity.forecastedDelivery?.format('YYYY-MM-DD') ?? '-'
      }}<br />
      Date de rendu: {{ opportunity.deliveredAt?.format('YYYY-MM-DD') ?? '-' }}<br />
      Date de facture: {{ opportunity.billedAt?.format('YYYY-MM-DD') ?? '-' }}<br />
      Date de paiement: {{ opportunity.payedAt?.format('YYYY-MM-DD') ?? '-' }}<br />
      Date d'annulation: {{ opportunity.canceledAt?.format('YYYY-MM-DD') ?? '-' }}<br />
      Ref client: {{ opportunity.customerRef1 ?? '-' }}<br />
      Ref client2: {{ opportunity.customerRef2 ?? '-' }}<br />
      Moyen de paiement: {{ opportunity.meanOfPayment?.label ?? '-' }} Ref de paiement:
      {{ opportunity.paymentRef ?? '-' }}<br />
    </p>

    <h3>Logs du statut</h3>
    <div>todo</div>

    <h3>Devis</h3>
    <button @click.prevent="showTenderForm()" class="btn btn-primary">Nouveau devis</button>
    <tender-form
      :tender="null"
      :opportunity="opportunity"
      :is-showing="isTenderFormShowing"
      @stop-showing="hideTenderForm"
    />
    <div>todo</div>
    <opportunity-form
      :opportunity="opportunity"
      :is-showing="isFormShowing"
      @stop-showing="hideForm"
    />
  </div>
</template>
