<script lang="ts" setup>
import { computed, onMounted, ref, watch } from 'vue'
import type { Ref } from 'vue'
import { useOpportunityStore } from '@/stores/opportunity'
import { useCompanyStore } from '@/stores/company'

import BootstrapModal from '@/components/BootstrapModal.vue'
import type { Opportunity, NewOpportunity } from '@/stores/opportunity/types'

import Mp3000Button from '@/components/Mp3000Button.vue'
import BootstrapLoader from '@/components/BootstrapLoader.vue'
import dayjs from '@/misc/dayjs'
import DatePicker from '@/components/DatePicker.vue'
import type { Company } from '@/stores/company/types'

const opportunityStore = useOpportunityStore()
const companyStore = useCompanyStore()
const emit = defineEmits(['stop-showing'])
const props = withDefaults(
  defineProps<{
    opportunity: Opportunity | null
    company?: Company | null
    isShowing: boolean
    isLoading?: boolean
  }>(),
  {
    company: null,
    isLoading: false
  }
)

const isSubmitting = ref(false)
const areRelationshipsLoading = ref(false)
const currentOpportunity = ref(getEmptyOpportunity()) as Ref<Opportunity | NewOpportunity>
const errorMessage = ref('')

const companies = computed(() => companyStore.companies)
const meanOfPayments = computed(() => opportunityStore.meanOfPayments)
const statuses = computed(() => opportunityStore.statuses)

function getEmptyOpportunity(): NewOpportunity {
  return {
    ref: '',
    description: '',
    status: { id: 0, label: '' },
    company: props.company ? props.company : { id: 0, name: '' },
    meanOfPayment: null,
    trackedAt: dayjs(),
    purchasedAt: null,
    forecastedDelivery: null,
    deliveredAt: null,
    billedAt: null,
    payedAt: null,
    canceledAt: null,
    comments: null,
    customerRef1: null,
    customerRef2: null,
    paymentRef: null
  }
}

function validate(opportunity: Opportunity | NewOpportunity): string {
  if (opportunity.ref === '') {
    return 'Ref non valide'
  }
  if (opportunity.description === '') {
    return 'Description non valide'
  }
  if (opportunity.company.id === 0) {
    return 'Client non valide'
  }
  if (opportunity.status.id === 0) {
    return 'Statut non valide'
  }
  return ''
}

async function submit() {
  errorMessage.value = validate(currentOpportunity.value)
  if (errorMessage.value !== '') {
    return
  }
  isSubmitting.value = true
  await ('id' in currentOpportunity.value
    ? opportunityStore.edit(currentOpportunity.value)
    : opportunityStore.add(currentOpportunity.value))
  emit('stop-showing')
  isSubmitting.value = false
}

function refresh() {
  if (props.opportunity) {
    currentOpportunity.value = { ...props.opportunity }
    delete currentOpportunity.value.contacts
    if (props.company) {
      currentOpportunity.value.company = props.company
    }
  } else {
    currentOpportunity.value = getEmptyOpportunity()
    currentOpportunity.value.status = statuses.value[0]
  }
}

watch(
  () => props.opportunity,
  () => refresh()
)

onMounted(async () => {
  areRelationshipsLoading.value = true
  await Promise.all([
    companyStore.fetch(),
    statuses.value.length ? null : opportunityStore.fetchStatuses(),
    meanOfPayments.value.length ? null : opportunityStore.fetchMeanOfPayments()
  ])
  refresh()
  areRelationshipsLoading.value = false
})
</script>

<template>
  <bootstrap-modal
    :is-showing="isShowing"
    :is-loading="isLoading"
    @stop-showing="emit('stop-showing')"
  >
    <template v-slot:header>
      <h5>{{ opportunity ? 'Edition' : 'Nouvelle' }} opportunité</h5>
    </template>
    <template v-slot:body>
      <div class="form-group">
        <label>Ref</label>
        <input
          type="text"
          class="form-control"
          v-model="currentOpportunity.ref"
          :disabled="isSubmitting"
        />
      </div>
      <div class="form-group">
        <label>Description</label>
        <input
          type="text"
          class="form-control"
          v-model="currentOpportunity.description"
          :disabled="isSubmitting"
        />
      </div>
      <div class="form-group" v-if="null === company">
        <label>Client</label>
        <bootstrap-loader v-if="areRelationshipsLoading" />
        <select
          v-else
          class="form-select"
          v-model="currentOpportunity.company.id"
          :disabled="isSubmitting"
        >
          <option v-for="company in companies" :key="company.id" :value="company.id">
            {{ company.name }}
          </option>
        </select>
      </div>
      <div class="form-group">
        <label>Statut</label>
        <bootstrap-loader v-if="areRelationshipsLoading" />
        <select
          v-else
          class="form-select"
          v-model="currentOpportunity.status.id"
          :disabled="isSubmitting"
        >
          <option v-for="status in statuses" :key="status.id" :value="status.id">
            {{ status.label }}
          </option>
        </select>
      </div>
      <div class="form-group">
        <label>Date besoin</label>
        <date-picker v-model="currentOpportunity.trackedAt" :disabled="isSubmitting" />
      </div>
      <div class="form-group">
        <label>Date achat</label>
        <date-picker v-model="currentOpportunity.purchasedAt" :disabled="isSubmitting" />
      </div>
      <div class="form-group">
        <label>Date rendu prévisionnelle</label>
        <date-picker v-model="currentOpportunity.forecastedDelivery" :disabled="isSubmitting" />
      </div>
      <div class="form-group">
        <label>Date rendu</label>
        <date-picker v-model="currentOpportunity.deliveredAt" :disabled="isSubmitting" />
      </div>
      <div class="form-group">
        <label>Date facture</label>
        <date-picker v-model="currentOpportunity.billedAt" :disabled="isSubmitting" />
      </div>
      <div class="form-group">
        <label>Date paiement</label>
        <date-picker v-model="currentOpportunity.payedAt" :disabled="isSubmitting" />
      </div>
      <div class="form-group" v-if="opportunity">
        <label>Date d'annulation</label>
        <date-picker v-model="currentOpportunity.canceledAt" :disabled="isSubmitting" />
      </div>
      <div class="form-group">
        <label>Commentaires</label>
        <input
          type="text"
          class="form-control"
          v-model="currentOpportunity.comments"
          :disabled="isSubmitting"
        />
      </div>
      <div class="form-group">
        <label>Client ref</label>
        <input
          type="text"
          class="form-control"
          v-model="currentOpportunity.customerRef1"
          :disabled="isSubmitting"
        />
      </div>
      <div class="form-group">
        <label>Client ref2</label>
        <input
          type="text"
          class="form-control"
          v-model="currentOpportunity.customerRef2"
          :disabled="isSubmitting"
        />
      </div>
      <!--      <div class="form-group">-->
      <!--        <label>Moyen de paiement</label>-->
      <!--        <bootstrap-loader v-if="areRelationshipsLoading" />-->
      <!--        <select-->
      <!--            v-else-->
      <!--            class="form-select"-->
      <!--            v-model="currentOpportunity.meanOfPayment.id"-->
      <!--            :disabled="isSubmitting"-->
      <!--        >-->
      <!--          <option v-for="meanOfPayment in meanOfPayments" :key="meanOfPayment.id" :value="meanOfPayment.id">-->
      <!--            {{ meanOfPayment.label }}-->
      <!--          </option>-->
      <!--        </select>-->
      <!--      </div>-->
      <div class="form-group">
        <label>Ref de paiement</label>
        <input
          type="text"
          class="form-control"
          v-model="currentOpportunity.paymentRef"
          :disabled="isSubmitting"
        />
      </div>
    </template>
    <template v-slot:footer>
      <span class="text-danger">{{ errorMessage }}</span>
      <mp3000-button
        @click.prevent="emit('stop-showing')"
        :disabled="isSubmitting"
        :outline="true"
        label="Annuler"
      />
      <mp3000-button @click.prevent="submit" :is-loading="isSubmitting" label="Valider" />
    </template>
  </bootstrap-modal>
</template>
