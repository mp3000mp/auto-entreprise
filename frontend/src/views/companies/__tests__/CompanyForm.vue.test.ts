import { describe, expect, test } from 'vitest'
import Component from '@/views/companies/CompanyForm.vue'
import { mount } from '@vue/test-utils'
import { createTestingPinia } from '@pinia/testing'
import { vi } from 'vitest'
import Mp3000Button from '@/components/Mp3000Button.vue'
import { useCompanyStore } from '@/stores/company'

const stubs = ['font-awesome-icon']

describe('CompanyForm.vue', () => {
  test('triggers submit event', async () => {
    const wrapper = mount(Component, {
      props: {
        company: null,
        isShowing: true
      },
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {}
          })
        ],
        stubs
      }
    })
    const submit = wrapper.findAllComponents(Mp3000Button)[1].find('button')
    await submit.trigger('click')

    expect(wrapper.find('.text-danger').text()).toEqual('Nom non valide')

    await wrapper.findAll('input[type=text]')[0].setValue('MP3000')
    await submit.trigger('click')

    const store = useCompanyStore()
    expect(store.add).toHaveBeenCalledTimes(1)
    const arg = store.add.mock.calls[0][0]
    expect(arg).toEqual({
      name: 'MP3000',
      street1: '',
      street2: '',
      city: '',
      postCode: ''
    })
  })
})
