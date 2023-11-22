<script lang="ts" setup>
import { useCompanyStore } from '@/stores/company'
import { computed, onMounted, ref } from 'vue'
import Mp3000Icon from '@/components/Mp3000Icon.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import CompanyForm from '@/views/companies/CompanyForm.vue'
import { useRouter } from 'vue-router'
import BootstrapLoader from '@/components/BootstrapLoader.vue'

const companyStore = useCompanyStore()
const router = useRouter()
const props = defineProps<{
  companyId: number
}>()

const isFormShowing = ref(false)
const isRemoving = ref(false)
const confirmMessage = computed(() => 'Confirmer la suppression de ' + company.value?.name)

const company = computed(() => companyStore.currentCompany)
const isDeletable = computed(
  () =>
    null === company.value ||
    (company.value.contacts.length === 0 && company.value.opportunities.length === 0)
)

function showForm() {
  isFormShowing.value = true
}
function hideForm() {
  isFormShowing.value = false
}

async function remove() {
  isRemoving.value = true
  await companyStore.delete(props.companyId)
  router.push({ name: 'companies' })
}

onMounted(async () => {
  await companyStore.fetchOne(props.companyId)
})
</script>

<template>
  <bootstrap-loader v-if="null === company" />
  <div v-else>
    <h2>
      {{ company.name }}
      <a href="#" @click.prevent="showForm()" title="Editer" class="me-1">
        <font-awesome-icon :icon="['fa', 'pen-to-square']" />
      </a>
      <mp3000-icon
        v-if="isDeletable"
        :confirm-message="confirmMessage"
        @click="remove()"
        icon="trash"
        title="Supprimer"
        :is-loading="isRemoving"
      />
    </h2>
    <h3>Client</h3>
    <p>
      {{ company.street1 }}<br />
      {{ company.street2 ?? '-' }}<br />
      {{ company.city }}<br />
      {{ company.postCode }}
    </p>

    <h3>Opportunités</h3>
    <div>todo</div>

    <h3>Contacts</h3>
    <div class="table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Téléphone</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="contact in company.contacts" :key="contact.id">
            <td>
              <router-link :to="{ name: 'contact', params: { id: contact.id } }"
                >{{ contact.firstName }} {{ contact.lastName }}</router-link
              >
            </td>
            <td>{{ contact.email }}</td>
            <td>{{ contact.phone }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <company-form :company="company" :is-showing="isFormShowing" @stop-showing="hideForm" />
  </div>
</template>
