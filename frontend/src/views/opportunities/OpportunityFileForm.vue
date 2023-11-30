<script lang="ts" setup>
import vueFilePond, { setOptions } from 'vue-filepond'
import 'filepond/dist/filepond.min.css'

import { ref } from 'vue'
import type { Ref } from 'vue'

import { opportunityFileTypeLabels, OpportunityFileTypeEnum } from '@/stores/opportunity/types'

import { useOpportunityStore } from '@/stores/opportunity'

const opportunityStore = useOpportunityStore()
const props = defineProps<{
  opportunityId: number
}>()

const isSubmitting = ref(false)
const type = ref(null) as Ref<OpportunityFileTypeEnum | null>

const FilePond = vueFilePond()
setOptions({
  server: {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    process: async (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
      isSubmitting.value = true
      const formData = new FormData()
      formData.append(fieldName, file, file.name)

      try {
        await opportunityStore.addOpportunityFile(formData, type.value, props.opportunityId)
        load('ok')
      } catch (err: unknown) {
        error(err)
      }

      isSubmitting.value = false
    },
    revert: null
  }
})
</script>

<template>
  <h4>Nouveau fichier</h4>
  <div class="form-group">
    <label>Type</label>
    <select class="form-select" v-model="type" :disabled="isSubmitting">
      <option
        v-for="(label, fileType) in opportunityFileTypeLabels"
        :key="fileType"
        :value="fileType"
      >
        {{ label }}
      </option>
    </select>
  </div>
  <file-pond
    v-if="type !== null"
    name="file"
    ref="pond"
    label-idle="Drop files here..."
    allow-multiple="false"
    accepted-file-types="*/*"
  />
</template>
