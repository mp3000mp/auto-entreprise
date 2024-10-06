<script lang="ts" setup>
import { useTenderStore } from '@/stores/tender'
import type { Ref } from 'vue'
import { computed, onMounted, ref } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import TenderForm from '@/views/tenders/TenderForm.vue'
import Mp3000Icon from '@/components/Mp3000Icon.vue'
import { useRouter } from 'vue-router'
import BootstrapLoader from '@/components/BootstrapLoader.vue'
import Mp3000Table from '@/components/Mp3000Table.vue'
import TenderRowRow from '@/views/tenders/tenderRows/TenderRowRow.vue'
import TenderRowForm from '@/views/tenders/tenderRows/tenderRowForm.vue'
import type { TenderRow } from '@/stores/tender/types'
import Mp3000TableHeader from '@/components/Mp3000TableHeader.vue'
import type { Tender } from '@/stores/tender/types'
import Mp3000Button from '@/components/Mp3000Button.vue'
import BootstrapModal from '@/components/BootstrapModal.vue'
import config from '@/misc/config'
import { getFileIcon } from '@/misc/utils'
import TenderFileForm from '@/views/tenders/TenderFileForm.vue'
import { tenderFileTypeLabels } from '@/stores/tender/types'
import { useSorter, SortConfigTypeEnum } from '@/composables/useSorter'

const tenderStore = useTenderStore()
const router = useRouter()
const props = defineProps<{
  tenderId: number
}>()

const isFormShowing = ref(false)
const isRemoving = ref(false)
const newTenderRowPosition = computed(() =>
  tender.value.tenderRows.length === 0
    ? 10
    : (Math.ceil(Math.max(...tender.value.tenderRows.map((row) => row.position)) / 10) + 1) * 10
)
const confirmMessage = computed(() => ({
  message:
    'Confirmer la suppression de ' +
    tender.value?.opportunity.ref +
    ' version ' +
    tender.value?.version,
  title: 'Suppression'
}))

const tender = computed(() => tenderStore.currentTender)
const tenderSoldDays = computed(() =>
  tender.value.tenderRows.reduce((acc, tenderRow) => acc + tenderRow.soldDays, 0)
)
const tenderFixedRate = computed(() =>
  tender.value.tenderRows.reduce((acc, tenderRow) => acc + tenderRow.fixedRate, 0)
)
const isDeletable = computed(() => null === tender.value || tender.value.tenderRows.length === 0)
function showForm() {
  isFormShowing.value = true
}
function hideForm() {
  isFormShowing.value = false
}

const isTenderRowFormShowing = ref(false)
const currentTenderRow = ref(null) as Ref<TenderRow | null>
const tenderRowFilterSearch = ref('')
const filteredTenderRows = computed(() => {
  return (tender.value?.tenderRows ?? []).filter((tenderRow) => {
    if (tenderRowFilterSearch.value.length < 1) {
      return true
    }
    return (tenderRow.title + tenderRow.description)
      .toLowerCase()
      .includes(tenderRowFilterSearch.value.toLowerCase())
  })
})
const { getAsc, getPriority, sort, sortedList } = useSorter(
  [
    { property: 'title', type: SortConfigTypeEnum.STRING },
    { property: 'description', type: SortConfigTypeEnum.STRING },
    { property: 'soldDays', type: SortConfigTypeEnum.NUMBER },
    { property: 'fixedRate', type: SortConfigTypeEnum.NUMBER },
    {
      property: 'amount',
      type: SortConfigTypeEnum.CUSTOM,
      customCompare: (a: Tender, b: Tender) => a.totalRate - b.totalRate
    }
  ],
  filteredTenderRows
)

function showTenderRowForm(tenderRow: TenderRow | null) {
  currentTenderRow.value = tenderRow
  isTenderRowFormShowing.value = true
}
function hideTenderRowForm() {
  isTenderRowFormShowing.value = false
  currentTenderRow.value = null
}

async function remove() {
  isRemoving.value = true
  await tenderStore.delete(props.tenderId)
  tenderStore.resetCurrentTender()
  router.push({ name: 'tenders' })
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
  await tenderStore.removeTenderFile(fileId)
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
        :confirm-config="confirmMessage"
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
      Statut: {{ tender.status.label }}
      <mp3000-icon icon="circle-info" title="Historique" @click="showStatusLogsPopin" /><br />
      TJM: {{ tender.averageDailyRate }}<br />
      Jours vendus: {{ tenderSoldDays }}<br />
      Date d'envoi: {{ tender.sentAt?.format('YYYY-MM-DD') ?? '-' }}<br />
      Date de commande: {{ tender.acceptedAt?.format('YYYY-MM-DD') ?? '-' }}<br />
      Date de refus: {{ tender.refusedAt?.format('YYYY-MM-DD') ?? '-' }}<br />
      Date d'annulation: {{ tender.canceledAt?.format('YYYY-MM-DD') ?? '-' }}<br />
      Commentaires: {{ tender.comments }}<br />
    </p>
    <bootstrap-modal :is-showing="isStatusLogsShowing" @stop-showing="hideStatusLogsPopin">
      <template #header>
        <h5>Status logs</h5>
      </template>
      <template #body>
        <div v-for="log in tender.statusLogs" :key="log.id">
          {{ log.createdAt.format('YYYY-MM-DD HH:mm') }} - {{ log.status.label }}
        </div>
      </template>
      <template #footer>
        <mp3000-button
          @click.prevent="hideStatusLogsPopin"
          class="btn-outline-primary"
          label="Fermer"
        />
      </template>
    </bootstrap-modal>

    <h3>Fichiers</h3>
    <div>
      <a
        v-for="file in tender.tenderFiles"
        :key="file.id"
        :href="config.backendBaseUrl + '/api/tender_files/' + file.id"
        title="Télécharger"
        target="_blank"
        class="me-1"
      >
        <font-awesome-icon :icon="['fa', getFileIcon(file.extension)]" />
        [{{ tenderFileTypeLabels[file.type] }}] {{ file.name }}
        <mp3000-icon icon="trash" @click="removeFile(file.id)" />
      </a>
      <mp3000-icon icon="plus" title="Ajouter" @click="showFileForm" />
      <tender-file-form
        :tender-id="tenderId"
        :is-showing="isFileFormShowing"
        @stop-showing="hideFileForm"
      />
    </div>

    <h3>Lignes</h3>
    <mp3000-table>
      <template #filters>
        <div class="col-auto">
          <button @click.prevent="showTenderRowForm(null)" class="btn btn-primary mt-4">
            Nouvelle ligne
          </button>
        </div>
        <div class="col-auto">
          <div class="form-group">
            <label>Recherche</label>
            <input type="text" class="form-control" v-model="tenderRowFilterSearch" />
          </div>
        </div>
      </template>
      <template v-slot:header>
        <mp3000-table-header
          :asc="getAsc('title')"
          :priority="getPriority('title')"
          @click="sort('title')"
          label="Titre"
        />
        <mp3000-table-header
          :asc="getAsc('description')"
          :priority="getPriority('description')"
          @click="sort('description')"
          label="Description"
        />
        <mp3000-table-header
          :asc="getAsc('soldDays')"
          :priority="getPriority('soldDays')"
          @click="sort('soldDays')"
          label="Jours vendus"
        />
        <mp3000-table-header
          :asc="getAsc('fixedRate')"
          :priority="getPriority('fixedRate')"
          @click="sort('fixedRate')"
          label="Coûts fixes"
        />
        <mp3000-table-header
          :asc="getAsc('amount')"
          :priority="getPriority('amount')"
          @click="sort('amount')"
          label="Montant"
        />
      </template>
      <template v-slot:body>
        <tr v-if="sortedList.length === 0">
          <td colspan="100">Aucune ligne</td>
        </tr>
        <template v-else>
          <tender-row-row
            v-for="tenderRow in sortedList"
            :key="tenderRow.id"
            :tender-row="tenderRow"
            :average-daily-rate="tender.averageDailyRate"
            @show-form="showTenderRowForm(tenderRow)"
          />
          <tr class="total">
            <td colspan="2" class="text-end">Total:</td>
            <td>{{ tenderSoldDays }}</td>
            <td>{{ tenderFixedRate }}</td>
            <td>{{ tenderFixedRate + tenderSoldDays * tender.averageDailyRate }}€</td>
          </tr>
        </template>
      </template>
    </mp3000-table>
    <tender-row-form
      :is-showing="isTenderRowFormShowing"
      :tender-row="currentTenderRow"
      :tender="tender"
      :position="newTenderRowPosition"
      @stop-showing="hideTenderRowForm"
    />
    <tender-form
      :tender="tender"
      :opportunity="tender.opportunity"
      :is-showing="isFormShowing"
      @stop-showing="hideForm"
    />
  </div>
</template>

<style lang="scss">
table {
  .total {
    font-weight: bold;
    border-top-width: 3px;
    border-bottom-width: 3px;
  }
}
</style>
