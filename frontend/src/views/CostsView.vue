<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue'
import { useCostStore } from '@/stores/cost'

const costStore = useCostStore()

const isLoading = ref(false)

const costs = computed(() => costStore.costs)
const costTypes = computed(() => costStore.costTypes)

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
          <td><a href="#" title="Editer"><font-awesome-icon :icon="['fa', 'pen-to-square']" /></a> {{ cost.type.label }}</td>
          <td>{{ cost.date.format('YYYY-MM-DD') }}</td>
          <td>{{ cost.amount.toFixed(2) }}</td>
          <td>{{ cost.description }}</td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
