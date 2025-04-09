import { describe, expect, test, vi } from 'vitest'
import Component from '@/components/Mp3000TableHeader.vue'
import { mount } from '@vue/test-utils'
import FontAwesomeIcon from '@fortawesome/vue-fontawesome'

const stubs = ['font-awesome-icon']

describe('Mp3000TableHeader.vue', () => {
  test('toggles icons', async () => {
    const wrapper = mount(Component, {
      props: {
        label: 'label',
        asc: null,
        priority: 0,
      },
      global: {
        stubs
      }
    })
    let icon = wrapper.find('font-awesome-icon-stub')
    expect(icon.exists()).toBeFalsy()
    let priority = wrapper.find('.sort-priority')
    expect(priority.exists()).toBeFalsy()

    await wrapper.setProps({ asc: true, priority: 1 })
    icon = wrapper.find('font-awesome-icon-stub')
    expect(icon.exists()).toBeTruthy()
    priority = wrapper.find('.sort-priority')
    expect(priority.exists()).toBeTruthy()
  })
})
