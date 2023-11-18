<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue'
import type { Ref } from 'vue'
import { useCostStore } from '@/stores/cost'

import CostForm from '@/views/forms/CostForm.vue'
import type { Cost } from '@/stores/cost/types'

const costStore = useCostStore()

const isLoading = ref(false)
const isFormShowing = ref(false)
const currentCost = ref(null) as Ref<Cost | null>

const costs = computed(() => costStore.costs)

function showForm(cost: Cost | null) {
  isFormShowing.value = true
  currentCost.value = cost ? { ...cost } : null
}
function hideForm() {
  isFormShowing.value = false
  currentCost.value = null
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
    <div class="text-center my-5" v-if="isLoading">
      <div class="spinner-border">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    <div class="table-responsive" v-else>
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Type</th>
            <th>Date</th>
            <th>Montant</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="cost in costs" :key="cost.id">
            <td>
              <a href="#" @click.prevent="showForm(cost)" title="Editer">
                <font-awesome-icon :icon="['fa', 'pen-to-square']" />
              </a>
              {{ cost.type.label }}
            </td>
            <td>{{ cost.date.format('YYYY-MM-DD') }}</td>
            <td>{{ cost.amount.toFixed(2) }}</td>
            <td>{{ cost.description }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <button @click.prevent="showForm(null)" class="btn btn-primary">Nouveau</button>
    <cost-form :cost="currentCost" :is-showing="isFormShowing" @stop-showing="hideForm" />
  </div>
</template>
