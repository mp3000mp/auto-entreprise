import { describe, expect, test, vi } from 'vitest'
import Component from '@/components/Mp3000TableHeader.vue'
import { mount } from '@vue/test-utils'
import FontAwesomeIcon from '@fortawesome/vue-fontawesome'

const stubs = ['font-awesome-icon']

describe('Mp3000TableHeader.vue', () => {
  test('toggles icons', async () => {
    const sorter = {
      getPriority: vi.fn(),
      isAsc: vi.fn().mockReturnValueOnce(null).mockReturnValueOnce(true).mockReturnValueOnce(false),
      addSort: vi.fn(),
      removeSort: vi.fn()
    }
    const wrapper = mount(Component, {
      props: {
        label: 'label',
        property: 'property',
        sorter
      },
      global: {
        stubs
      }
    })
    const icon = wrapper.findComponent(FontAwesomeIcon)
    expect(icon.exists()).toBeFalsy()
    const priority = wrapper.find('.sort-priority')
    expect(priority.exists()).toBeFalsy()

    await wrapper.find('th').trigger('click')
    expect(sorter.addSort.mock.calls.length).toBe(1)
    expect(sorter.removeSort.mock.calls.length).toBe(0)
    expect(sorter.addSort.mock.calls[0]).toEqual(['property'])

    await wrapper.find('th').trigger('click')
    expect(sorter.addSort.mock.calls.length).toBe(2)
    expect(sorter.removeSort.mock.calls.length).toBe(0)
    expect(sorter.addSort.mock.calls[0]).toEqual(['property'])

    await wrapper.find('th').trigger('click')
    expect(sorter.addSort.mock.calls.length).toBe(2)
    expect(sorter.removeSort.mock.calls.length).toBe(1)
    expect(sorter.removeSort.mock.calls[0]).toEqual(['property'])
  })
})
