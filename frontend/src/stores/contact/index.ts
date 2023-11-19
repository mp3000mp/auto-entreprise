import { defineStore } from 'pinia'
import type {Contact, ListContact, NewContact} from '@/stores/contact/types'
import ApiClient, { HttpMethodEnum } from '@/misc/api-client'
import {convertContactOut} from '@/stores/contact/dto'
import {notifyError} from "@/stores/notification/utils";

const urlPrefix = '/api/contacts'
export const useContactStore = defineStore('contact', {
    state: () => ({
        contacts: [] as ListContact[],
        currentContact: null as Contact|null,
        deletableIds: [] as number[],
    }),
    actions: {
        async fetch() {
            try {
                this.contacts = (await ApiClient.query(HttpMethodEnum.GET, urlPrefix)) as ListContact[]
            } catch (err: unknown) {
                notifyError('Error while fetching contacts: ', err)
            }
        },
        async fetchOne(id: number) {
            try {
                this.currentContact = await ApiClient.query(HttpMethodEnum.GET, urlPrefix+'/'+id)
            } catch (err: unknown) {
                notifyError('Error while fetching contact: ', err)
            }
        },
        async add(contact: NewContact) {
            try {
                const rawContact = await ApiClient.query(HttpMethodEnum.POST, urlPrefix, convertContactOut(contact))
                this.contacts.push(rawContact)
            } catch (err: unknown) {
                notifyError('Error while adding contact: ', err)
            }
        },
        async edit(contact: Contact) {
            try {
                const rawContact = await ApiClient.query(HttpMethodEnum.PUT, urlPrefix+'/'+contact.id, convertContactOut(contact))
                const contactIdx = this.contacts.findIndex(c => c.id === contact.id)
                this.contacts.splice(contactIdx, 1, rawContact)
            } catch (err: unknown) {
                notifyError('Error while editing contact: ', err)
            }
        },
        async fetchDeletables() {
            try {
                this.deletableIds = (await ApiClient.query(HttpMethodEnum.GET, urlPrefix+'/deletable')) as number[]
            } catch (err: unknown) {
                notifyError('Error while fetching deletable contacts: ', err)
            }
        },
        async delete(id: number) {
            try {
                await ApiClient.query(HttpMethodEnum.DELETE, urlPrefix+'/'+id)
                const idx = this.contacts.findIndex(contact => contact.id === id)
                this.contacts.splice(idx, 1)
            } catch (err: unknown) {
                notifyError('Error while deleting contact: ', err)
            }
        },
    }
})
