import { describe, expect, test } from 'vitest'
import Component from '@/components/Mp3000ConfirmModal.vue'
import { mount } from '@vue/test-utils'

describe('BootstrapModal.vue', () => {
  test('is not showing', () => {
    const wrapper = mount(Component, {
      props: {
        confirmConfig: {
          message: 'test message',
          title: 'test title',
          confirmButtonClass: 'confirm-class',
          confirmButtonMessage: 'confirm message',
          cancelButtonClass: 'cancel-class',
          cancelButtonMessage: 'cancel message'
        },
        isShowing: true
      }
    })
    expect(wrapper.find('.modal').exists()).toBeTruthy()

    expect(wrapper.find('h5').text()).toBe('test title')
    expect(wrapper.find('.modal-body').text()).toBe('test message')

    const confirm = wrapper.find('.confirm-class')
    expect(confirm.exists()).toBeTruthy()
    expect(confirm.text()).toBe('confirm message')

    const cancel = wrapper.find('.cancel-class')
    expect(cancel.exists()).toBeTruthy()
    expect(cancel.text()).toBe('cancel message')
  })
})
