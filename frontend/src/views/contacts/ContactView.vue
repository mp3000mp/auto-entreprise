<script lang="ts" setup>
import { useContactStore } from '@/stores/contact'
import { useOpportunityStore } from '@/stores/opportunity'
import { computed, onMounted, ref } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import ContactForm from '@/views/contacts/ContactForm.vue'
import Mp3000Icon from '@/components/Mp3000Icon.vue'
import { useRouter } from 'vue-router'
import type { Ref } from 'vue'
import BootstrapLoader from '@/components/BootstrapLoader.vue'
import type { Opportunity } from '@/stores/opportunity/types'
import OpportunityForm from '@/views/opportunities/OpportunityForm.vue'
import Mp3000Table from '@/components/Mp3000Table.vue'
import OpportunityRow from '@/views/opportunities/OpportunityRow.vue'
import Mp3000TableHeader from '@/components/Mp3000TableHeader.vue'
import { useSorter, SortConfigTypeEnum } from '@/composables/useSorter'

const contactStore = useContactStore()
const opportunityStore = useOpportunityStore()
const router = useRouter()
const props = defineProps<{
  contactId: number
}>()

const isFormShowing = ref(false)
const isRemoving = ref(false)
const confirmMessage = computed(() => ({
  message:
    'Confirmer la suppression de ' + contact.value?.firstName + ' ' + contact.value?.lastName,
  title: 'Suppression'
}))

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

const isOpportunityFormShowing = ref(false)
const currentOpportunity = ref(null) as Ref<Opportunity | null>
const deletableOpportuntyIds = opportunityStore.deletableIds
const opportunityFilterSearch = ref('')
const filteredOpportunities = computed(() => {
  return (contact.value?.opportunities ?? []).filter((opportunity) => {
    if (opportunityFilterSearch.value.length < 1) {
      return true
    }
    return opportunity.ref.toLowerCase().includes(opportunityFilterSearch.value.toLowerCase())
  })
})
const {
  getAsc: getOpportunityAsc,
  getPriority: getOpportunityPriority,
  sort: sortOpportunities,
  sortedList: sortedOpportunities
} = useSorter(
  [
    { property: 'ref', type: SortConfigTypeEnum.STRING },
    {
      property: 'status',
      type: SortConfigTypeEnum.CUSTOM,
      customCompare: (a: Opportunity, b: Opportunity) =>
        a.status.label.localeCompare(b.status.label)
    },
    {
      property: 'amount',
      type: SortConfigTypeEnum.CUSTOM,
      customCompare: (a: Opportunity, b: Opportunity) =>
        (a.lastTender?.totalRate ?? 0) - (b.lastTender?.totalRate ?? 0)
    },
    {
      property: 'soldDays',
      type: SortConfigTypeEnum.CUSTOM,
      customCompare: (a: Opportunity, b: Opportunity) =>
        (a.lastTender?.soldDays ?? 0) - (b.lastTender?.soldDays ?? 0)
    },
    {
      property: 'workedDays',
      type: SortConfigTypeEnum.CUSTOM,
      customCompare: (a: Opportunity, b: Opportunity) =>
        (a.lastTender?.workedDays ?? 0) - (b.lastTender?.workedDays ?? 0)
    },
    { property: 'createdAt', type: SortConfigTypeEnum.DATE }
  ],
  filteredOpportunities
)

function showOpportunityForm(opportunity: Opportunity | null) {
  currentOpportunity.value = opportunity
  isOpportunityFormShowing.value = true
}
function hideOpportunityForm() {
  isOpportunityFormShowing.value = false
  currentOpportunity.value = null
}

async function remove() {
  isRemoving.value = true
  await contactStore.delete(props.contactId)
  contactStore.resetCurrentContact()
  router.push({ name: 'contacts' })
}

onMounted(async () => {
  sortOpportunities('createdAt', false)
  await Promise.all([contactStore.fetchOne(props.contactId), opportunityStore.fetchDeletables()])
})
</script>

<template>
  <bootstrap-loader v-if="null === contact" />
  <div v-else>
    <h2>
      {{ contact.firstName }} {{ contact.lastName }}
      <a href="#" @click.prevent="showForm()" title="Editer" class="me-1">
        <font-awesome-icon :icon="['fa', 'pen-to-square']" />
      </a>
      <mp3000-icon
        v-if="isDeletable"
        :confirm-config="confirmMessage"
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
      <br />
      Commentaires: {{ contact.comments }}
    </p>

    <h3>Opportunités</h3>
    <mp3000-table>
      <template #filters>
        <div class="col-auto">
          <button @click.prevent="showOpportunityForm(null)" class="btn btn-primary mt-4">
            Nouvelle opportunité
          </button>
        </div>
        <div class="col-auto">
          <div class="form-group">
            <label>Recherche</label>
            <input type="text" class="form-control" v-model="opportunityFilterSearch" />
          </div>
        </div>
      </template>
      <template #header>
        <mp3000-table-header
          :asc="getOpportunityAsc('ref')"
          :priority="getOpportunityPriority('ref')"
          @click="sortOpportunities('ref')"
          label="Ref"
        />
        <mp3000-table-header
          :asc="getOpportunityAsc('status')"
          :priority="getOpportunityPriority('status')"
          @click="sortOpportunities('status')"
          label="Statut"
        />
        <mp3000-table-header
          :asc="getOpportunityAsc('amount')"
          :priority="getOpportunityPriority('amount')"
          @click="sortOpportunities('amount')"
          label="Montant"
        />
        <mp3000-table-header
          :asc="getOpportunityAsc('soldDays')"
          :priority="getOpportunityPriority('soldDays')"
          @click="sortOpportunities('soldDays')"
          label="Jours vendus"
        />
        <mp3000-table-header
          :asc="getOpportunityAsc('workedDays')"
          :priority="getOpportunityPriority('workedDays')"
          @click="sortOpportunities('workedDays')"
          label="Jours travaillés"
        />
        <mp3000-table-header
          :asc="getOpportunityAsc('createdAt')"
          :priority="getOpportunityPriority('createdAt')"
          @click="sortOpportunities('createdAt')"
          label="Création"
        />
      </template>
      <template #body>
        <tr v-if="sortedOpportunities.length === 0">
          <td colspan="100">Aucune opportunité</td>
        </tr>
        <opportunity-row
          v-else
          v-for="opportunity in sortedOpportunities"
          :key="opportunity.id"
          :is-deletable="deletableOpportuntyIds.includes(opportunity.id)"
          :with-details="false"
          :opportunity="opportunity"
          @show-form="showOpportunityForm(opportunity)"
        />
      </template>
    </mp3000-table>
    <opportunity-form
      :is-showing="isOpportunityFormShowing"
      :opportunity="currentOpportunity"
      :company="contact.company"
      @stop-showing="hideOpportunityForm"
    />

    <contact-form :contact="contact" :is-showing="isFormShowing" @stop-showing="hideForm" />
  </div>
</template>
