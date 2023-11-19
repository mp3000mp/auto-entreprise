<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue'
import type { Ref } from 'vue'
import { useCostStore } from '@/stores/cost'

import CostForm from '@/views/costs/CostForm.vue'
import type { Cost } from '@/stores/cost/types'
import Mp3000Icon from "@/components/Mp3000Icon.vue";
import Mp3000Table from "@/components/Mp3000Table.vue";
import CostRow from "@/views/costs/CostRow.vue";

const costStore = useCostStore()

const isLoading = ref(false)
const isFormShowing = ref(false)
const isRemoving = ref(false)
const currentCost = ref(null) as Ref<Cost | null>

const costTypes = computed(() => costStore.costTypes)
const costs = computed(() => costStore.costs)

const filterTypeId = ref(null) as Ref<number|null>
const filteredCosts = computed(() => costs.value.filter(cost => {
  if (null === filterTypeId.value) {
    return true
  }
  return cost.type.id === filterTypeId.value
}))

function showForm(cost: Cost | null) {
  isFormShowing.value = true
  currentCost.value = cost ? { ...cost } : null
}
function hideForm() {
  isFormShowing.value = false
  currentCost.value = null
}
async function remove(cost: Cost) {
  isRemoving.value = true
  await costStore.deleteCost(cost.id)
  isRemoving.value = false
}

onMounted(async () => {
  isLoading.value = true
  await costStore.fetchCostTypes()
  await costStore.fetchCosts()
  isLoading.value = false
})
</script>

<template>
  <div>
    <mp3000-table :is-loading="isLoading">
      <template v-slot:filters>
        <div class="col-auto">
          <div class="form-group">
            <label>Type</label>
            <select class="form-select" v-model="filterTypeId">
              <option v-for="costType in costTypes" :key="costType.id" :value="costType.id">
                {{ costType.label }}
              </option>
            </select>
          </div>
        </div>
      </template>
      <template v-slot:header>
        <tr>
          <th>Type</th>
          <th>Date</th>
          <th>Montant</th>
          <th>Description</th>
        </tr>
      </template>
      <template v-slot:body>
        <cost-row v-for="cost in filteredCosts" :key="cost.id" :cost="cost" @show-form="showForm(cost)" />
      </template>
    </mp3000-table>
    <button @click.prevent="showForm(null)" class="btn btn-primary">Nouveau</button>
    <cost-form :cost="currentCost" :is-showing="isFormShowing" @stop-showing="hideForm" />
  </div>
</template>
