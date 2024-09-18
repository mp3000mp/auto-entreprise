<script lang="ts" setup>
import type { Ref } from 'vue'
import { computed, onMounted, ref } from 'vue'
import { useCostStore } from '@/stores/cost'

import CostForm from '@/views/costs/CostForm.vue'
import type { Cost } from '@/stores/cost/types'
import Mp3000Table from '@/components/Mp3000Table.vue'
import CostRow from '@/views/costs/CostRow.vue'
import Mp3000TableHeader from '@/components/Mp3000TableHeader.vue'
import { useSorter, SortConfigTypeEnum } from '@/composables/useSorter'

const costStore = useCostStore()

const isLoading = ref(false)
const isFormShowing = ref(false)
const currentCost = ref(null) as Ref<Cost | null>

const costTypes = computed(() => costStore.costTypes)
const costs = computed(() => costStore.costs)

const filterSearch = ref('')
const filterTypeId = ref(null) as Ref<number | null>
const filteredCosts = computed(() =>
  costs.value.filter((cost) => {
    if (null !== filterTypeId.value && cost.type.id !== filterTypeId.value) {
      return false
    }
    if (filterSearch.value.length < 1) {
      return true
    }
    return cost.description.toLowerCase().includes(filterSearch.value.toLowerCase())
  })
)

const { getAsc, getPriority, sort, sortedList } = useSorter(
  [
    {
      property: 'type',
      type: SortConfigTypeEnum.CUSTOM,
      customCompare: (a: Cost, b: Cost) => a.type.label.localeCompare(b.type.label)
    },
    { property: 'date', type: SortConfigTypeEnum.DATE },
    { property: 'amount', type: SortConfigTypeEnum.NUMBER },
    { property: 'description', type: SortConfigTypeEnum.STRING }
  ],
  filteredCosts
)

function showForm(cost: Cost | null) {
  isFormShowing.value = true
  currentCost.value = cost ? { ...cost } : null
}
function hideForm() {
  isFormShowing.value = false
  currentCost.value = null
}

onMounted(async () => {
  sort('date', false)
  isLoading.value = true
  await Promise.all([costStore.fetchCostTypes(), costStore.fetchCosts()])
  isLoading.value = false
})
</script>

<template>
  <div>
    <h2>Coûts</h2>
    <mp3000-table :is-loading="isLoading">
      <template v-slot:filters>
        <div class="col-auto">
          <button @click.prevent="showForm(null)" class="btn btn-primary mt-4">Nouveau</button>
        </div>
        <div class="col-auto">
          <div class="form-group">
            <label>Recherche</label>
            <input type="text" class="form-control" v-model="filterSearch" />
          </div>
        </div>
        <div class="col-auto">
          <div class="form-group">
            <label>Type</label>
            <select class="form-select" v-model="filterTypeId">
              <option :value="null">-</option>
              <option v-for="costType in costTypes" :key="costType.id" :value="costType.id">
                {{ costType.label }}
              </option>
            </select>
          </div>
        </div>
      </template>
      <template v-slot:header>
        <tr>
          <mp3000-table-header
            :asc="getAsc('type')"
            :priority="getPriority('type')"
            @click="sort('type')"
            label="Type"
          />
          <mp3000-table-header
            :asc="getAsc('date')"
            :priority="getPriority('date')"
            @click="sort('date')"
            label="Date"
          />
          <mp3000-table-header
            :asc="getAsc('amount')"
            :priority="getPriority('amount')"
            @click="sort('amount')"
            label="Amount"
          />
          <mp3000-table-header
            :asc="getAsc('description')"
            :priority="getPriority('description')"
            @click="sort('description')"
            label="Description"
          />
        </tr>
      </template>
      <template v-slot:body>
        <tr v-if="sortedList.length === 0">
          <td colspan="100">Aucun coût</td>
        </tr>
        <cost-row
          v-else
          v-for="cost in sortedList"
          :key="cost.id"
          :cost="cost"
          @show-form="showForm(cost)"
        />
      </template>
    </mp3000-table>
    <cost-form :cost="currentCost" :is-showing="isFormShowing" @stop-showing="hideForm" />
  </div>
</template>
