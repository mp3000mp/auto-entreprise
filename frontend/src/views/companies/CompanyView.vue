<script lang="ts" setup>
import { useCompanyStore } from '@/stores/company'
import { useOpportunityStore } from '@/stores/opportunity'
import { useContactStore } from '@/stores/contact'
import { computed, onMounted, ref } from 'vue'
import type { Ref } from 'vue'
import Mp3000Icon from '@/components/Mp3000Icon.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import CompanyForm from '@/views/companies/CompanyForm.vue'
import { useRouter } from 'vue-router'
import BootstrapLoader from '@/components/BootstrapLoader.vue'
import type { Opportunity } from '@/stores/opportunity/types'
import Mp3000Table from '@/components/Mp3000Table.vue'
import OpportunityRow from '@/views/opportunities/OpportunityRow.vue'
import Mp3000TableHeader from '@/components/Mp3000TableHeader.vue'
import OpportunityForm from '@/views/opportunities/OpportunityForm.vue'
import { SortConfigTypeEnum, Sorter } from '@/misc/sorter'
import type { Contact } from '@/stores/contact/types'
import ContactRow from '@/views/contacts/ContactRow.vue'
import ContactForm from '@/views/contacts/ContactForm.vue'

const companyStore = useCompanyStore()
const opportunityStore = useOpportunityStore()
const contactStore = useContactStore()
const router = useRouter()
const props = defineProps<{
  companyId: number
}>()

const isFormShowing = ref(false)
const isRemoving = ref(false)
const confirmMessage = computed(() => 'Confirmer la suppression de ' + company.value?.name)

const company = computed(() => companyStore.currentCompany)
const isDeletable = computed(
  () =>
    null === company.value ||
    (company.value.contacts.length === 0 && company.value.opportunities.length === 0)
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
  return (company.value?.opportunities ?? []).filter((opportunity) => {
    if (opportunityFilterSearch.value.length < 1) {
      return true
    }
    return opportunity.ref.toLowerCase().includes(opportunityFilterSearch.value.toLowerCase())
  })
})
const opportunitiesSorter = new Sorter(
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
        (a.lastTender?.soldDays ?? 0) * (a.lastTender.averageDailyRate ?? 0) -
        (b.lastTender?.soldDays ?? 0) * (b.lastTender.averageDailyRate ?? 0)
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

const isContactFormShowing = ref(false)
const currentContact = ref(null) as Ref<Contact | null>
const deletableContactIds = contactStore.deletableIds
const contactFilterSearch = ref('')
const filteredContacts = computed(() => {
  return (company.value?.contacts ?? []).filter((contact) => {
    if (contactFilterSearch.value.length < 1) {
      return true
    }
    return (contact.firstName + contact.lastName + contact.email)
      .toLowerCase()
      .includes(contactFilterSearch.value.toLowerCase())
  })
})
const contactsSorter = new Sorter(
  [
    {
      property: 'name',
      type: SortConfigTypeEnum.CUSTOM,
      customCompare: (a: Contact, b: Contact) =>
        (a.firstName + a.lastName).localeCompare(b.firstName + b.lastName)
    },
    { property: 'email', type: SortConfigTypeEnum.STRING },
    { property: 'phone', type: SortConfigTypeEnum.STRING },
    { property: 'comments', type: SortConfigTypeEnum.STRING }
  ],
  filteredContacts
)

function showContactForm(contact: Contact | null) {
  currentContact.value = contact
  isContactFormShowing.value = true
}
function hideContactForm() {
  isContactFormShowing.value = false
  currentContact.value = null
}
async function remove() {
  isRemoving.value = true
  await companyStore.delete(props.companyId)
  companyStore.resetCurrentCompany()
  router.push({ name: 'companies' })
}

onMounted(async () => {
  opportunitiesSorter.addSort('createdAt', false)
  await Promise.all([
    companyStore.fetchOne(props.companyId),
    opportunityStore.fetchDeletables(),
    contactStore.fetchDeletables()
  ])
})
</script>

<template>
  <bootstrap-loader v-if="null === company" />
  <div v-else>
    <h2>
      {{ company.name }}
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
    <h3>Client</h3>
    <p>
      {{ company.street1 }}<br />
      {{ company.street2 ?? '-' }}<br />
      {{ company.city }}<br />
      {{ company.postCode }}
    </p>

    <div>
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
          <mp3000-table-header property="ref" :sorter="opportunitiesSorter" label="Ref" />
          <mp3000-table-header property="status" :sorter="opportunitiesSorter" label="Statut" />
          <mp3000-table-header property="amount" :sorter="opportunitiesSorter" label="Montant" />
          <mp3000-table-header
            property="soldDays"
            :sorter="opportunitiesSorter"
            label="Jours vendus"
          />
          <mp3000-table-header
            property="workedDays"
            :sorter="opportunitiesSorter"
            label="Jours travaillés"
          />
          <mp3000-table-header
            property="createdAt"
            :sorter="opportunitiesSorter"
            label="Création"
          />
        </template>
        <template #body>
          <tr v-if="opportunitiesSorter.sortedList.value.length === 0">
            <td colspan="100">Aucune opportunité</td>
          </tr>
          <opportunity-row
            v-else
            v-for="opportunity in opportunitiesSorter.sortedList.value"
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
        :company="company"
        @stop-showing="hideOpportunityForm"
      />
    </div>

    <div>
      <h3>Contacts</h3>
      <mp3000-table>
        <template v-slot:filters>
          <div class="col-auto">
            <button @click.prevent="showContactForm(null)" class="btn btn-primary mt-4">
              Nouveau contact
            </button>
          </div>
          <div class="col-auto">
            <div class="form-group">
              <label>Recherche</label>
              <input type="text" class="form-control" v-model="contactFilterSearch" />
            </div>
          </div>
        </template>
        <template v-slot:header>
          <tr>
            <mp3000-table-header property="name" :sorter="contactsSorter" label="Nom" />
            <mp3000-table-header property="email" :sorter="contactsSorter" label="Email" />
            <mp3000-table-header property="phone" :sorter="contactsSorter" label="Téléphone" />
            <mp3000-table-header
              property="comments"
              :sorter="contactsSorter"
              label="Commentaires"
            />
          </tr>
        </template>
        <template v-slot:body>
          <tr v-if="contactsSorter.sortedList.value.length === 0">
            <td colspan="100">Aucun contact</td>
          </tr>
          <contact-row
            v-else
            v-for="contact in contactsSorter.sortedList.value"
            :key="contact.id"
            :is-deletable="deletableContactIds.includes(contact.id)"
            :contact="contact"
            :with-details="false"
            @show-form="showContactForm(contact)"
          />
        </template>
      </mp3000-table>
      <contact-form
        :is-showing="isContactFormShowing"
        :contact="currentContact"
        :company="company"
        @stop-showing="hideContactForm"
      />
    </div>

    <company-form :company="company" :is-showing="isFormShowing" @stop-showing="hideForm" />
  </div>
</template>
