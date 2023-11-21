import { describe, expect, test } from 'vitest'
import VueDatePicker from '@vuepic/vue-datepicker'
import Component from '@/components/DatePicker.vue'
import { mount } from '@vue/test-utils'
import dayjs from "@/misc/dayjs";

describe('DatePicker.vue', () => {
    test('uses dayjs', async () => {
        const wrapper = mount(Component, {
            props: {
                modelValue: dayjs('2023-11-15'),
            }
        })
        const dp = wrapper.findComponent(VueDatePicker)
        await dp.setValue(new Date('2023-12-15'))

        expect(wrapper.emitted()['update:modelValue'].length).toBe(1)
        const emittedArg = wrapper.emitted()['update:modelValue'][0][0]
        expect(dayjs.isDayjs(emittedArg)).toBeTruthy()
        expect(emittedArg.format('YYYY-MM-DD')).toEqual('2023-12-15')
    })
})
