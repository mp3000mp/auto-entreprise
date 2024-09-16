<script lang="ts" setup>
import type { Ref } from 'vue'
import { computed, nextTick, onMounted, ref } from 'vue'
import { useSecurityStore } from '@/stores/security'
import { useRouter } from 'vue-router'

import Mp3000Button from '@/components/Mp3000Button.vue'

const securityStore = useSecurityStore()
const router = useRouter()

const isLoading = ref(false)
const userName = ref('')
const password = ref('')
const userNameInput = ref(null) as Ref<HTMLInputElement | null>

async function focus() {
  await nextTick()
  if (twoFactorAuthRequired.value) {
    twoFactorAuthInput.value?.focus()
  } else {
    userNameInput.value?.focus()
  }
}

function redirectAfterLogin() {
  router.push({ path: router.currentRoute.value.query?.redirect ?? '/' })
}

async function connect() {
  if (userName.value === '' || password.value === '') {
    return
  }
  isLoading.value = true
  try {
    await securityStore.login(userName.value, password.value)
    if (!twoFactorAuthRequired.value) {
      redirectAfterLogin()
    }
  } finally {
    isLoading.value = false
    await focus()
  }
}

const twoFactorAuthRequired = computed(() => securityStore.twoFactorAuthRequired)
const twoFactorAuthCode = ref('')
const twoFactorAuthInput = ref(null) as Ref<HTMLInputElement | null>

async function twoFactorAuth() {
  if (twoFactorAuthCode.value === '') {
    return
  }
  isLoading.value = true
  try {
    await securityStore.twoFactorAuth(twoFactorAuthCode.value)
    redirectAfterLogin()
  } catch (err: unknown) {
    isLoading.value = false
  }
}

onMounted(async () => {
  await focus()
})
</script>

<template>
  <div class="login-content">
    <div class="login-input">
      <template v-if="twoFactorAuthRequired">
        <input
          v-model="twoFactorAuthCode"
          @keyup.enter="twoFactorAuth"
          class="form-control"
          type="text"
          placeholder="Code"
          :disabled="isLoading"
        />
        <mp3000-button
          @click.prevent="twoFactorAuth"
          class="btn-outline-primary ms-2"
          :is-loading="isLoading"
          label="OK"
        />
      </template>
      <template v-else>
        <input
          v-model="userName"
          ref="userNameInput"
          @keyup.enter="connect"
          class="form-control"
          type="text"
          placeholder="Username"
          :disabled="isLoading"
        />
        <input
          v-model="password"
          @keyup.enter="connect"
          class="form-control ms-2"
          type="password"
          placeholder="Password"
          :disabled="isLoading"
        />
        <mp3000-button
          @click.prevent="connect"
          class="btn-outline-primary ms-2"
          :is-loading="isLoading"
          label="OK"
        />
      </template>
    </div>
  </div>
</template>

<style lang="scss">
.login-content {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
}
.login-input {
  width: 500px;
  max-width: 95%;
  padding: 10px;
  margin: auto;
  display: flex;
}
</style>
