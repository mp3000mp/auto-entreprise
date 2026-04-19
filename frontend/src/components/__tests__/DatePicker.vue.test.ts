import { describe, expect, test } from 'vitest'
import VueDatePicker from '@vuepic/vue-datepicker'
import Component from '@/components/DatePicker.vue'
import { mount } from '@vue/test-utils'
import dayjs from '@/misc/dayjs'

describe('DatePicker.vue', () => {
  test('uses dayjs', async () => {
    const wrapper = mount(Component, {
      props: {
        modelValue: dayjs('2023-11-15')
      }
    })
    const dp = wrapper.findComponent(VueDatePicker)
    await dp.vm.$emit('update:model-value', new Date('2023-12-15'))

    const emitted = wrapper.emitted('update:modelValue')
    expect(emitted).toBeTruthy()
    expect(emitted!.length).toBe(1)
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    const emittedArg = (emitted![0] as any[])[0]
    expect(dayjs.isDayjs(emittedArg)).toBeTruthy()
    expect(emittedArg.format('YYYY-MM-DD')).toEqual('2023-12-15')
  })
})
