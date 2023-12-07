import { describe, expect, test } from 'vitest'
import Component from '@/views/security/AccountView.vue'
import { mount } from '@vue/test-utils'
import { createTestingPinia } from '@pinia/testing'
import { vi } from 'vitest'
import { useSecurityStore } from '@/stores/security'
import { initUsers } from '@tests/data/user'
import Mp3000Button from '../../../components/Mp3000Button.vue'

const stubs = ['font-awesome-icon']

describe('AccountView.vue', () => {
  test('triggers edit password', async () => {
    const wrapper = mount(Component, {
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: true,
            initialState: {
              security: {
                currentUser: initUsers()[0]
              }
            }
          })
        ],
        stubs
      }
    })

    const btn = wrapper.find('button')
    await btn.trigger('click')
    const submit = wrapper.findAllComponents(Mp3000Button)[1]

    await submit.trigger('click')
    expect(wrapper.find('.text-danger').text()).toEqual('Veuillez saisir votre mot de passe actuel')

    await wrapper.findAll('input[type=password]')[0].setValue('goodPassword')
    await submit.trigger('click')
    expect(wrapper.find('.text-danger').text()).toEqual(
      'Le mot de passe doit contenir au moins 8 caractères'
    )

    await wrapper.findAll('input[type=password]')[1].setValue('goodPassword')
    await submit.trigger('click')
    expect(wrapper.find('.text-danger').text()).toEqual(
      'Les deux mots de passe saisis ne correspondent pas'
    )

    await wrapper.findAll('input[type=password]')[2].setValue('goodPassword')
    await submit.trigger('click')
    expect(wrapper.find('.text-danger').exists()).toBeFalsy()

    const store = useSecurityStore()
    expect(store.editPassword).toHaveBeenCalledTimes(1)
    expect(store.editPassword).toHaveBeenLastCalledWith({
      currentPassword: 'goodPassword',
      newPassword: 'goodPassword'
    })
  })
})
