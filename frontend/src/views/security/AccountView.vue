<script lang="ts" setup>
import { computed, nextTick, ref } from 'vue'
import type { Ref } from 'vue'
import { useSecurityStore } from '@/stores/security'
import Mp3000Button from '@/components/Mp3000Button.vue'

const securityStore = useSecurityStore()

const isSubmitting = ref(false)
const isPasswordFormShowing = ref(false)
const currentPassword = ref('')
const newPassword = ref('')
const newPasswordConfirm = ref('')
const errorMessage = ref('')
const currentPasswordEl = ref(null) as Ref<HTMLInputElement | null>

const currentUser = computed(() => securityStore.currentUser)

function validate(): string {
  if (currentPassword.value === '') {
    return 'Veuillez saisir votre mot de passe actuel'
  }
  if (newPassword.value.length < 8) {
    return 'Le mot de passe doit contenir au moins 8 caractères'
  }
  if (newPassword.value !== newPasswordConfirm.value) {
    return 'Les deux mots de passe saisis ne correspondent pas'
  }
  return ''
}

async function showPasswordForm() {
  isPasswordFormShowing.value = true
  await nextTick()
  currentPasswordEl.value?.focus()
}
function hidePasswordForm() {
  isPasswordFormShowing.value = false
}

async function updatePassword() {
  errorMessage.value = validate()
  if (errorMessage.value !== '') {
    return
  }
  isSubmitting.value = true
  await securityStore.editPassword({
    currentPassword: currentPassword.value,
    newPassword: newPassword.value
  })
  isSubmitting.value = false
  hidePasswordForm()
  currentPassword.value = ''
  newPassword.value = ''
  newPasswordConfirm.value = ''
}
</script>

<template>
  <div>
    <h2>Mon compte</h2>
    <h3>{{ currentUser.username }}</h3>
    <p>
      Email: {{ currentUser.email }}<br />
      Roles: {{ currentUser.roles }}
    </p>
    <button
      @click.prevent="showPasswordForm"
      class="btn btn-primary mt-4"
      v-if="!isPasswordFormShowing"
    >
      Changer le mot de passe
    </button>
    <div class="row" v-if="isPasswordFormShowing">
      <div class="col-auto">
        <div class="form-group">
          <label>Mot de passe actuel</label>
          <input
            type="password"
            class="form-control"
            v-model="currentPassword"
            :disabled="isSubmitting"
            ref="currentPasswordEl"
            @keyup.enter="updatePassword"
          />
        </div>
      </div>
      <div class="col-auto">
        <div class="form-group">
          <label>Nouveau mot de passe</label>
          <input
            type="password"
            class="form-control"
            v-model="newPassword"
            :disabled="isSubmitting"
            @keyup.enter="updatePassword"
          />
        </div>
      </div>
      <div class="col-auto">
        <div class="form-group">
          <label>Confirmer le mot de passe</label>
          <input
            type="password"
            class="form-control"
            v-model="newPasswordConfirm"
            :disabled="isSubmitting"
            @keyup.enter="updatePassword"
          />
        </div>
      </div>
      <span class="text-danger">{{ errorMessage }}</span>
      <div class="col-12 mt-2">
        <mp3000-button
          @click.prevent="hidePasswordForm"
          :disabled="isSubmitting"
          :outline="true"
          label="Annuler"
          class="me-3"
        />
        <mp3000-button @click.prevent="updatePassword" :is-loading="isSubmitting" label="Valider" />
      </div>
    </div>
  </div>
</template>
