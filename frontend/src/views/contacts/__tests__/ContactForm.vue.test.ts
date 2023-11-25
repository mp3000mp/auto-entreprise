import { describe, expect, test } from 'vitest'
import Component from '@/views/contacts/ContactForm.vue'
import { mount } from '@vue/test-utils'
import { createTestingPinia } from '@pinia/testing'
import { vi } from 'vitest'
import Mp3000Button from '@/components/Mp3000Button.vue'
import { useContactStore } from '@/stores/contact'
import { initCompanies } from '@tests/data/company'

const stubs = ['font-awesome-icon']

describe('ContactForm.vue', () => {
  test('triggers submit event', async () => {
    const wrapper = mount(Component, {
      props: {
        contact: null,
        isShowing: true
      },
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {
              company: {
                companies: initCompanies()
              }
            }
          })
        ],
        stubs
      }
    })
    const submit = wrapper.findAllComponents(Mp3000Button)[1]

    await submit.trigger('click')
    expect(wrapper.find('.text-danger').text()).toEqual('Prénom non valide')

    await wrapper.findAll('input[type=text]')[0].setValue('Hugue')
    await submit.trigger('click')
    expect(wrapper.find('.text-danger').text()).toEqual('Nom non valide')

    await wrapper.findAll('input[type=text]')[1].setValue('Aux fraises')
    await submit.trigger('click')
    expect(wrapper.find('.text-danger').text()).toEqual('Client non valide')

    await wrapper.find('.form-select').setValue('2')
    await submit.trigger('click')
    expect(wrapper.find('.text-danger').text()).toEqual('Email non valide')

    await wrapper.findAll('input[type=text]')[2].setValue('hugo.fraise@mp3000.fr')
    await submit.trigger('click')
    expect(wrapper.find('.text-danger').text()).toEqual('')

    const store = useContactStore()
    expect(store.add).toHaveBeenCalledTimes(1)
    const arg = store.add.mock.calls[0][0]
    expect(arg).toEqual({
      firstName: 'Hugue',
      lastName: 'Aux fraises',
      company: { id: 2, name: '' },
      email: 'hugo.fraise@mp3000.fr',
      phone: '',
      comments: ''
    })
  })
})
