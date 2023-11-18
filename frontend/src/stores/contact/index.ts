import { defineStore } from 'pinia'
import type { BaseContact } from '@/stores/contact/types'
import ApiClient, { ApiError, HttpMethodEnum } from '@/misc/api-client'
import { useNotificationStore } from '@/stores/notification'
import {convertContactOut} from '@/stores/contact/dto'
import type {Contact} from "@/stores/contact/types";

const urlPrefix = '/api/contacts'
export const useContactStore = defineStore('contact', {
    state: () => ({
        contacts: [] as BaseContact[]
    }),
    actions: {
        async fetchContacts() {
            try {
                this.contacts = (await ApiClient.query(HttpMethodEnum.GET, urlPrefix)) as BaseContact[]
            } catch (err: unknown) {
                if (err instanceof ApiError) {
                    const notificationStore = useNotificationStore()
                    notificationStore.addError('Error while loading contacts: ' + err.message)
                }
            }
        },
        async addContact(contact: Contact) {
            try {
                const rawContact = await ApiClient.query(HttpMethodEnum.POST, urlPrefix, convertContactOut(contact))
                this.contacts.push(rawContact)
            } catch (err: unknown) {
                if (err instanceof ApiError) {
                    const notificationStore = useNotificationStore()
                    notificationStore.addError('Error while adding contact: ' + err.message)
                }
            }
        },
        async editContact(contact: Contact) {
            try {
                const rawContact = await ApiClient.query(HttpMethodEnum.PUT, urlPrefix+'/'+contact.id, convertContactOut(contact))
                const contactIdx = this.contacts.findIndex(c => c.id === contact.id)
                this.contacts.splice(contactIdx, 1, rawContact)
            } catch (err: unknown) {
                if (err instanceof ApiError) {
                    const notificationStore = useNotificationStore()
                    notificationStore.addError('Error while editing contact: ' + err.message)
                }
            }
        },
    }
})
