<script lang="ts" setup>
import { ref } from 'vue'
import Mp3000Icon from "@/components/Mp3000Icon.vue";
import {useCompanyStore} from "@/stores/company";
import type {ListCompany} from "@/stores/company/types";

const companyStore = useCompanyStore()
defineEmits(['show-form'])
const props = defineProps<{
  company: ListCompany;
  isDeletable: boolean;
}>()

const isRemoving = ref(false)

async function remove() {
  isRemoving.value = true
  await companyStore.delete(props.company.id)
  isRemoving.value = false
}
</script>

<template>
  <tr>
    <td>
      <a href="#" @click.prevent="$emit('show-form')" title="Editer">
        <font-awesome-icon :icon="['fa', 'pen-to-square']" />
      </a>
      <mp3000-icon v-if="isDeletable" @click.prevent="remove()" icon="trash" title="Supprimer" :is-loading="isRemoving" />
      <router-link :to="{name: 'company', params: {id: company.id}}">{{ company.name }}</router-link>
    </td>
  </tr>
</template>
