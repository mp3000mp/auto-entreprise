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
const successMessage = ref('')
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

const isTwoFactorAuthFormShowing = ref(false)
const qrCodeUrl = computed(() => securityStore.qrCodeUrl)
const twoFactorAuthCode = ref('')
async function showTwoFactorAuthForm() {
  isTwoFactorAuthFormShowing.value = true
}
function hideTwoFactorAuthForm() {
  isTwoFactorAuthFormShowing.value = false
}

async function toggleTwoFactorAuth() {
  try {
    if (currentUser.value?.isTotpAuthenticationEnabled) {
      await securityStore.getTwoFactorAuthDisable()
      hideTwoFactorAuthForm()
    } else {
      await securityStore.getTwoFactorAuthEnable()
      showTwoFactorAuthForm()
    }
  } catch (err: unknown) {
    errorMessage.value = String(err)
  }
}
async function checkTwoFactorCheck() {
  isSubmitting.value = true
  const response = await securityStore.checkTwoFactorAuth(twoFactorAuthCode.value)
  if (response.success) {
    errorMessage.value = ''
    successMessage.value = response.message
  } else {
    errorMessage.value = response.message
    successMessage.value = ''
  }
  isSubmitting.value = false
}
</script>

<template>
  <div>
    <h2>Mon compte</h2>
    <h3>{{ currentUser.username }}</h3>
    <p>
      Email: {{ currentUser.email }}<br />
      Roles: {{ currentUser.roles.join(',') }}
    </p>
    <h2>Mot de passe</h2>
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
      <span v-if="errorMessage" class="text-danger">{{ errorMessage }}</span>
      <div class="col-12 mt-2">
        <mp3000-button
          @click.prevent="hidePasswordForm"
          class="btn-outline-primary me-3"
          :disabled="isSubmitting"
          label="Annuler"
        />
        <mp3000-button
          @click.prevent="updatePassword"
          :is-loading="isSubmitting"
          class="btn-primary"
          label="Valider"
        />
      </div>
    </div>
    <h2>Double authentification</h2>
    <button @click.prevent="toggleTwoFactorAuth" class="btn btn-primary mt-4">
      {{ currentUser.isTotpAuthenticationEnabled ? 'Désactiver' : 'Activer' }} la double
      authentification
    </button>
    <button
      @click.prevent="showTwoFactorAuthForm"
      class="btn btn-primary mt-4 ms-3"
      v-if="!isTwoFactorAuthFormShowing && currentUser.isTotpAuthenticationEnabled"
    >
      Afficher QR code
    </button>
    <span v-if="errorMessage" class="text-danger">{{ errorMessage }}</span>
    <span v-if="successMessage" class="text-success">{{ successMessage }}</span>
    <div class="row" v-if="isTwoFactorAuthFormShowing">
      <div class="col-auto">
        <img alt="qr code" :src="qrCodeUrl" class="qr-code mt-2" />
      </div>
      <div class="col-auto">
        <div class="form-group">
          <label>Code</label>
          <input
            type="string"
            class="form-control"
            v-model="twoFactorAuthCode"
            :disabled="isSubmitting"
            @keyup.enter="checkTwoFactorCheck"
          />
        </div>
      </div>
      <div class="col-12 mt-2">
        <mp3000-button
          @click.prevent="hideTwoFactorAuthForm"
          :disabled="isSubmitting"
          label="Masquer"
          class="btn-outline-primary me-3"
        />
        <mp3000-button
          @click.prevent="checkTwoFactorCheck"
          class="btn-primary"
          :is-loading="isSubmitting"
          label="Checker le code"
        />
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.qr-code {
  width: 250px;
}
</style>
