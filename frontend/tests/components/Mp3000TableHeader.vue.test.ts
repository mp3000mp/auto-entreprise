import { describe, expect, test } from 'vitest'
import Component from '@/components/Mp3000TableHeader.vue'
import { mount } from '@vue/test-utils'
import FontAwesomeIcon from '@fortawesome/vue-fontawesome'
import {SortConfigTypeEnum, Sorter} from "../../src/misc/sorter";
import {ref} from 'vue'

const stubs = ['font-awesome-icon']

describe('Mp3000TableHeader.vue', () => {
    test('toggles icons', async () => {
        const sorter = new Sorter(
            [{ property: 'property', type: SortConfigTypeEnum.NUMBER }],
            ref([]),
        )
        const wrapper = mount(Component, {
            props: {
                label: 'label',
                property: 'property',
                sorter,
            },
            global: {
                stubs,
            }
        })
        let icon = wrapper.findComponent(FontAwesomeIcon)
        expect(icon.exists()).toBeFalsy()
        let priority = wrapper.find('.sort-priority')
        expect(priority.exists()).toBeFalsy()

        await wrapper.find('th').trigger('click')
        icon = wrapper.findComponent(FontAwesomeIcon)
        // todo why sorter.options loose reactivity ?
        expect(icon.exists()).toBeTruthy()
        // todo check icon
        priority = wrapper.find('.sort-priority')
        expect(priority.exists()).toBeTruthy()
        // todo check classes

        await wrapper.find('th').trigger('click')
        icon = wrapper.findComponent(FontAwesomeIcon)
        expect(icon.exists()).toBeTruthy()
        // todo check icon
        priority = wrapper.find('.sort-priority')
        expect(priority.exists()).toBeTruthy()
        // todo check classes

        await wrapper.find('th').trigger('click')
        icon = wrapper.findComponent(FontAwesomeIcon)
        expect(icon.exists()).toBeFalsy()
        priority = wrapper.find('.sort-priority')
        expect(priority.exists()).toBeFalsy()
    })
})
