<script lang="ts" setup>
import vueFilePond, { setOptions } from 'vue-filepond'
import 'filepond/dist/filepond.min.css'

import { ref } from 'vue'
import type { Ref } from 'vue'

import { tenderFileTypeLabels, TenderFileTypeEnum } from '@/stores/tender/types'

import { useTenderStore } from '@/stores/tender'
import BootstrapModal from '@/components/BootstrapModal.vue'
import Mp3000Button from '@/components/Mp3000Button.vue'

const tenderStore = useTenderStore()
const emit = defineEmits(['stop-showing'])
const props = defineProps<{
  tenderId: number
  isShowing: boolean
}>()

const isSubmitting = ref(false)
const type = ref(null) as Ref<TenderFileTypeEnum | null>

const FilePond = vueFilePond()
setOptions({
  credits: false,
  server: {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    process: async (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
      isSubmitting.value = true
      const formData = new FormData()
      formData.append(fieldName, file, file.name)

      try {
        await tenderStore.addTenderFile(formData, type.value, props.tenderId)
        progress(true, 100, 100)
        load('ok')
      } catch (err: unknown) {
        error(err)
      }

      setTimeout(() => {
        isSubmitting.value = false
        emit('stop-showing')
      }, 800)
    },
    revert: null
  }
})
</script>

<template>
  <bootstrap-modal :is-showing="isShowing" @stop-showing="emit('stop-showing')">
    <template v-slot:header>
      <h5>Nouveau fichier</h5>
    </template>
    <template #body>
      <div class="form-group">
        <label>Type</label>
        <select class="form-select" v-model="type" :disabled="isSubmitting">
          <option
            v-for="(label, fileType) in tenderFileTypeLabels"
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
    <template #footer>
      <mp3000-button
        @click.prevent="emit('stop-showing')"
        :disabled="isSubmitting"
        :outline="true"
        label="Annuler"
      />
    </template>
  </bootstrap-modal>
</template>
