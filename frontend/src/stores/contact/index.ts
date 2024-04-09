import { defineStore } from 'pinia'
import type { Contact, ListContact, NewContact } from '@/stores/contact/types'
import ApiClient, { HttpMethodEnum } from '@/misc/api-client'
import { convertContactIn, convertContactOut } from '@/stores/contact/dto'
import { notifyError } from '@/stores/notification/utils'
import { useCompanyStore } from '@/stores/company'
import type { ContactDtoIn } from '@/stores/contact/types'

const urlPrefix = '/api/contacts'
export const useContactStore = defineStore('contact', {
  state: () => ({
    contacts: [] as ListContact[],
    currentContact: null as Contact | null,
    deletableIds: [] as number[]
  }),
  actions: {
    async fetch() {
      try {
        this.contacts = await ApiClient.query<ListContact[]>(HttpMethodEnum.GET, urlPrefix)
      } catch (err: unknown) {
        notifyError('Error while fetching contacts: ', err)
      }
    },
    async fetchOne(id: number) {
      try {
        this.currentContact = convertContactIn(
          await ApiClient.query<ContactDtoIn>(HttpMethodEnum.GET, urlPrefix + '/' + id)
        )
      } catch (err: unknown) {
        notifyError('Error while fetching contact: ', err)
      }
    },
    resetCurrentContact() {
      this.currentContact = null
    },
    async add(contact: NewContact) {
      try {
        const rawContact = await ApiClient.query<ContactDtoIn>(
          HttpMethodEnum.POST,
          urlPrefix,
          convertContactOut(contact)
        )
        const newContact = convertContactIn(rawContact)
        this.contacts.push(newContact)

        const companyStore = useCompanyStore()
        if (companyStore.currentCompany) {
          companyStore.currentCompany.contacts.push(newContact)
        }
      } catch (err: unknown) {
        notifyError('Error while adding contact: ', err)
      }
    },
    async edit(contact: Contact) {
      try {
        const rawContact = await ApiClient.query<ContactDtoIn>(
          HttpMethodEnum.PUT,
          urlPrefix + '/' + contact.id,
          convertContactOut(contact)
        )
        const editedContact = convertContactIn(rawContact)
        if (this.currentContact?.id === editedContact.id) {
          this.currentContact = editedContact
        }
        let contactIdx = this.contacts.findIndex((c) => c.id === editedContact.id)
        this.contacts.splice(contactIdx, 1, editedContact)

        const companyStore = useCompanyStore()
        if (companyStore.currentCompany) {
          contactIdx = companyStore.currentCompany.contacts.findIndex(
            (c) => c.id === editedContact.id
          )
          companyStore.currentCompany.contacts.splice(contactIdx, 1, editedContact)
        }
      } catch (err: unknown) {
        notifyError('Error while editing contact: ', err)
      }
    },
    async fetchDeletables() {
      try {
        this.deletableIds = await ApiClient.query<number[]>(
          HttpMethodEnum.GET,
          urlPrefix + '/deletable'
        )
      } catch (err: unknown) {
        notifyError('Error while fetching deletable contacts: ', err)
      }
    },
    async delete(id: number) {
      try {
        await ApiClient.query(HttpMethodEnum.DELETE, urlPrefix + '/' + id)
        let contactIdx = this.contacts.findIndex((contact) => contact.id === id)
        this.contacts.splice(contactIdx, 1)

        const companyStore = useCompanyStore()
        if (companyStore.currentCompany) {
          contactIdx = companyStore.currentCompany.contacts.findIndex((c) => c.id === contactIdx)
          companyStore.currentCompany.contacts.splice(contactIdx, 1)
        }
      } catch (err: unknown) {
        notifyError('Error while deleting contact: ', err)
      }
    }
  }
})
