<script lang="ts" setup>
import type { Ref } from 'vue'
import { nextTick, onMounted, ref } from 'vue'
import { useSecurityStore } from '@/stores/security'
import { useRouter } from 'vue-router'

import Mp3000Button from '@/components/Mp3000Button.vue'

const securityStore = useSecurityStore()
const router = useRouter()

const isLoading = ref(false)
const userName = ref('')
const password = ref('')
const userNameInput = ref(null) as Ref<HTMLInputElement | null>

async function connect() {
  if (userName.value === '' || password.value === '') {
    return
  }
  isLoading.value = true
  try {
    await securityStore.login(userName.value, password.value)
    router.push({ name: 'home' })
  } catch (err: unknown) {
    isLoading.value = false
  }
}

onMounted(async () => {
  await nextTick()
  userNameInput.value?.focus()
})
</script>

<template>
  <div class="login-content">
    <div class="login-input">
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
        class="form-control"
        type="password"
        placeholder="Password"
        :disabled="isLoading"
      />
      <mp3000-button
        @click.prevent="connect"
        :is-loading="isLoading"
        :outline="true"
        label="OK"
        class="ms-2"
      />
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
