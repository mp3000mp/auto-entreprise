<script lang="ts" setup>
import { useOpportunityStore } from '@/stores/opportunity'
import { useTenderStore } from '@/stores/tender'
import { useCompanyStore } from '@/stores/company'
import { computed, onMounted, ref } from 'vue'
import type { Ref } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import OpportunityForm from '@/views/opportunities/OpportunityForm.vue'
import Mp3000Icon from '@/components/Mp3000Icon.vue'
import { useRouter } from 'vue-router'
import BootstrapLoader from '@/components/BootstrapLoader.vue'
import TenderForm from '@/views/tenders/TenderForm.vue'
import type { Tender } from '@/stores/tender/types'
import Mp3000Table from '@/components/Mp3000Table.vue'
import TenderRow from '@/views/tenders/TenderRow.vue'
import Mp3000TableHeader from '@/components/Mp3000TableHeader.vue'
import { SortConfigTypeEnum, Sorter } from '@/misc/sorter'
import BootstrapModal from '@/components/BootstrapModal.vue'
import Mp3000Button from '@/components/Mp3000Button.vue'
import OpportunityFileForm from '@/views/opportunities/OpportunityFileForm.vue'
import config from '@/misc/config'
import { opportunityFileTypeLabels } from '@/stores/opportunity/types'
import { getFileIcon } from '@/misc/utils'
import WorkedTimeForm from '@/views/workedTimes/WorkedTimeForm.vue'
import WorkedTimeRow from '@/views/workedTimes/WorkedTimeRow.vue'
import type { WorkedTime } from '@/stores/workedTime/types'

const opportunityStore = useOpportunityStore()
const tenderStore = useTenderStore()
const companyStore = useCompanyStore()
const router = useRouter()
const props = defineProps<{
  opportunityId: number
}>()

const isFormShowing = ref(false)
const isRemoving = ref(false)
const confirmMessage = computed(() => 'Confirmer la suppression de ' + opportunity.value?.ref)
const companyContacts = computed(() => companyStore.currentCompany?.contacts ?? [])
const opportunityContactIds = computed(() =>
  opportunity.value.contacts.map((contact) => contact.id)
)
const notLinkedContacts = computed(() =>
  companyContacts.value.filter((contact) => !opportunityContactIds.value.includes(contact.id))
)
const isAddContactFormShowing = ref(false)
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

const isTenderFormShowing = ref(false)
const currentTender = ref(null) as Ref<Tender | null>
const tenderFilterSearch = ref('')
const deletableTenderIds = tenderStore.deletableIds
const filteredTenders = computed(() =>
  opportunity.value.tenders.filter((tender) => {
    if (tenderFilterSearch.value.length < 1) {
      return true
    }
    return tender.opportunity.ref.toLowerCase().includes(tenderFilterSearch.value.toLowerCase())
  })
)
const tendersSorter = new Sorter(
  [
    { property: 'version', type: SortConfigTypeEnum.NUMBER },
    {
      property: 'status',
      type: SortConfigTypeEnum.CUSTOM,
      customCompare: (a: Tender, b: Tender) => a.status.label.localeCompare(b.status.label)
    },
    { property: 'soldDays', type: SortConfigTypeEnum.NUMBER },
    {
      property: 'amount',
      type: SortConfigTypeEnum.CUSTOM,
      customCompare: (a: Tender, b: Tender) =>
        a.soldDays * a.averageDailyRate - b.soldDays * b.averageDailyRate
    },
    { property: 'createdAt', type: SortConfigTypeEnum.DATE }
  ],
  filteredTenders
)

function showTenderForm(tender: Tender | null) {
  currentTender.value = tender
  isTenderFormShowing.value = true
}
function hideTenderForm() {
  isTenderFormShowing.value = false
  currentTender.value = null
}

async function remove() {
  isRemoving.value = true
  await opportunityStore.delete(props.opportunityId)
  opportunityStore.resetCurrentOpportunity()
  router.push({ name: 'opportunities' })
}

async function removeContact(contactId: number) {
  await opportunityStore.unlinkContact(props.opportunityId, contactId)
}
async function showAddContactForm() {
  isAddContactFormShowing.value = true
}
async function hideAddContactForm() {
  isAddContactFormShowing.value = false
}
async function addContact(contactId: number) {
  await opportunityStore.linkContact(props.opportunityId, contactId)
  isAddContactFormShowing.value = false
}

const isStatusLogsShowing = ref(false)
function showStatusLogsPopin() {
  isStatusLogsShowing.value = true
}
function hideStatusLogsPopin() {
  isStatusLogsShowing.value = false
}

const isFileFormShowing = ref(false)
function showFileForm() {
  isFileFormShowing.value = true
}
function hideFileForm() {
  isFileFormShowing.value = false
}
async function removeFile(fileId: number) {
  await opportunityStore.removeOpportunityFile(fileId)
}

const isWorkedTimeHistoryShowing = ref(false)
function showWorkedTimeHistory() {
  isWorkedTimeHistoryShowing.value = true
}
function hideWorkedTimeHistory() {
  isWorkedTimeHistoryShowing.value = false
}

const totalWorkedDays = computed(() =>
  opportunity.value.workedTimes.reduce((acc, workedTime) => acc + workedTime.workedDays, 0)
)

const isWorkedTimeFormShowing = ref(false)
const currentWorkedTime = ref(null) as Ref<WorkedTime | null>
function showWorkedTimeForm(workedTime: WorkedTime | null) {
  currentWorkedTime.value = workedTime
  isWorkedTimeFormShowing.value = true
}
function hideWorkedTimeForm() {
  isWorkedTimeFormShowing.value = false
  currentWorkedTime.value = null
}

onMounted(async () => {
  tendersSorter.addSort('version', false)
  await Promise.all([opportunityStore.fetchOne(props.opportunityId), tenderStore.fetchDeletables()])
  await companyStore.fetchOne(opportunity.value.company.id)
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
      <span class="me-1" v-for="contact in opportunity.contacts" :key="contact.id"
        >{{ contact.firstName }} {{ contact.lastName }}
        <mp3000-icon icon="trash" title="Supprimer" @click="removeContact(contact.id)"
      /></span>
      <mp3000-icon icon="plus" title="Ajouter" @click="showAddContactForm" />
      <br />
      Statut: {{ opportunity.status.label }}
      <mp3000-icon icon="circle-info" title="Historique" @click="showStatusLogsPopin" /><br />
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
      Moyen de paiement: {{ opportunity.meanOfPayment?.label ?? '-' }}<br />
      Ref de paiement: {{ opportunity.paymentRef ?? '-' }}<br />
      Jours travaillés: {{ totalWorkedDays }}
      <mp3000-icon icon="circle-info" class="me-1" title="Voir" @click="showWorkedTimeHistory" />
      <mp3000-icon icon="plus" title="Ajouter" @click="showWorkedTimeForm(null)" /><br />
      Commentaires: {{ opportunity.comments }}<br />
    </p>
    <worked-time-form
      :is-showing="isWorkedTimeFormShowing"
      :opportunity="opportunity"
      :worked-time="currentWorkedTime"
      @stop-showing="hideWorkedTimeForm"
    />
    <bootstrap-modal :is-showing="isWorkedTimeHistoryShowing" @stop-showing="hideWorkedTimeHistory">
      <template #header>
        <h5>Temps</h5>
      </template>
      <template #body>
        <worked-time-row
          v-for="workedTime in opportunity.workedTimes"
          :key="workedTime.id"
          :worked-time="workedTime"
          @show-form="showWorkedTimeForm(workedTime)"
        />
        <div>Total: {{ totalWorkedDays }}</div>
      </template>
      <template #footer>
        <mp3000-button @click.prevent="hideWorkedTimeHistory" :outline="true" label="Fermer" />
      </template>
    </bootstrap-modal>
    <bootstrap-modal :is-showing="isStatusLogsShowing" @stop-showing="hideStatusLogsPopin">
      <template #header>
        <h5>Status logs</h5>
      </template>
      <template #body>
        <div v-for="log in opportunity.statusLogs" :key="log.id">
          {{ log.createdAt.format('YYYY-MM-DD HH:mm') }} - {{ log.status.label }}
        </div>
      </template>
      <template #footer>
        <mp3000-button @click.prevent="hideStatusLogsPopin" :outline="true" label="Fermer" />
      </template>
    </bootstrap-modal>
    <bootstrap-modal :is-showing="isAddContactFormShowing" @stop-showing="hideAddContactForm">
      <template #header>
        <h5>Ajouter un contact</h5>
      </template>
      <template #body>
        <mp3000-button
          class="me-1"
          v-for="contact in notLinkedContacts"
          :key="contact.id"
          @click.prevent="addContact(contact.id)"
          :outline="true"
          :label="contact.firstName + ' ' + contact.lastName"
        />
      </template>
      <template #footer>
        <mp3000-button @click.prevent="hideAddContactForm" :outline="true" label="Annuler" />
      </template>
    </bootstrap-modal>
    <bootstrap-modal :is-showing="isStatusLogsShowing" @stop-showing="hideStatusLogsPopin">
      <template #header>
        <h5>Status logs</h5>
      </template>
      <template #body>
        <div v-for="log in opportunity.statusLogs" :key="log.id">
          {{ log.createdAt.format('YYYY-MM-DD HH:mm') }} - {{ log.status.label }}
        </div>
      </template>
      <template #footer>
        <mp3000-button @click.prevent="hideStatusLogsPopin" :outline="true" label="Fermer" />
      </template>
    </bootstrap-modal>

    <h3>Fichiers</h3>
    <div>
      <template v-for="file in opportunity.opportunityFiles" :key="file.id">
        <a
          :href="config.backendBaseUrl + '/api/opportunity_files/' + file.id"
          title="Télécharger"
          target="_blank"
          class="me-1"
        >
          <font-awesome-icon :icon="['fa', getFileIcon(file.extension)]" />
          [{{ opportunityFileTypeLabels[file.type] }}] {{ file.name }}
        </a>
        <mp3000-icon class="me-1" icon="trash" @click="removeFile(file.id)" />
      </template>
      <mp3000-icon icon="plus" title="Ajouter" @click="showFileForm" />
      <opportunity-file-form
        :opportunity-id="opportunityId"
        :is-showing="isFileFormShowing"
        @stop-showing="hideFileForm"
      />
    </div>

    <h3>Devis</h3>
    <mp3000-table>
      <template #filters>
        <div class="col-auto">
          <button @click.prevent="showTenderForm(null)" class="btn btn-primary mt-4">
            Nouveau devis
          </button>
        </div>
        <div class="col-auto">
          <div class="form-group">
            <label>Recherche</label>
            <input type="text" class="form-control" v-model="tenderFilterSearch" />
          </div>
        </div>
      </template>
      <template #header>
        <mp3000-table-header property="version" :sorter="tendersSorter" label="Version" />
        <mp3000-table-header property="status" :sorter="tendersSorter" label="Statut" />
        <mp3000-table-header property="soldDays" :sorter="tendersSorter" label="Jours vendus" />
        <mp3000-table-header property="amount" :sorter="tendersSorter" label="Montant" />
      </template>
      <template #body>
        <tr v-if="tendersSorter.sortedList.value.length === 0">
          <td colspan="100">Aucun devis</td>
        </tr>
        <tender-row
          v-else
          v-for="tender in tendersSorter.sortedList.value"
          :key="tender.id"
          :tender="tender"
          :opportunity="opportunity"
          :with-details="false"
          :is-deletable="deletableTenderIds.includes(tender.id)"
          @show-form="showTenderForm(tender)"
        />
      </template>
    </mp3000-table>
    <tender-form
      :tender="currentTender"
      :opportunity="opportunity"
      :is-showing="isTenderFormShowing"
      @stop-showing="hideTenderForm"
    />

    <opportunity-form
      :opportunity="opportunity"
      :is-showing="isFormShowing"
      @stop-showing="hideForm"
    />
  </div>
</template>
