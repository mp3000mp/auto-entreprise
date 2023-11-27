<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue'
import { useUserStore } from '@/stores/user'
import Mp3000Table from '@/components/Mp3000Table.vue'
import Mp3000TableHeader from '@/components/Mp3000TableHeader.vue'
import UserRow from '@/views/users/UserRow.vue'
import { SortConfigTypeEnum, Sorter } from '@/misc/sorter'

const userStore = useUserStore()

const isLoading = ref(false)

const users = computed(() => userStore.users)

const filterSearch = ref('')
const filteredContacts = computed(() =>
  users.value.filter((user) => {
    if (filterSearch.value.length < 3) {
      return true
    }
    return (user.email + user.username).toLowerCase().includes(filterSearch.value.toLowerCase())
  })
)
const sorter = new Sorter(
  [
    { property: 'id', type: SortConfigTypeEnum.NUMBER },
    { property: 'email', type: SortConfigTypeEnum.STRING },
    { property: 'username', type: SortConfigTypeEnum.STRING },
    { property: 'roles', type: SortConfigTypeEnum.STRING }
  ],
  filteredContacts
)

onMounted(async () => {
  isLoading.value = true
  await userStore.fetch()
  isLoading.value = false
})
</script>

<template>
  <div>
    <h2>Utilisateurs</h2>
    <mp3000-table :is-loading="isLoading">
      <template v-slot:filters>
        <div class="col-auto">
          <div class="form-group">
            <label>Recherche</label>
            <input type="text" class="form-control" v-model="filterSearch" />
          </div>
        </div>
      </template>
      <template v-slot:header>
        <tr>
          <mp3000-table-header property="id" :sorter="sorter" label="#" />
          <mp3000-table-header property="email" :sorter="sorter" label="Email" />
          <mp3000-table-header property="username" :sorter="sorter" label="Username" />
          <mp3000-table-header property="roles" :sorter="sorter" label="Roles" />
        </tr>
      </template>
      <template v-slot:body>
        <user-row v-for="user in sorter.sortedList.value" :key="user.id" :user="user" />
      </template>
    </mp3000-table>
  </div>
</template>
