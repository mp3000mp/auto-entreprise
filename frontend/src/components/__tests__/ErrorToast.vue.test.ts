import { describe, expect, test } from 'vitest'
import Component from '@/components/ErrorToast.vue'
import { mount } from '@vue/test-utils'
import { createTestingPinia } from '@pinia/testing'
import { NotificationTypeEnum } from '@/stores/notification/types'
import { vi } from 'vitest'
import { useNotificationStore } from '../../src/stores/notification'

describe('ErrorToast.vue', () => {
  test('shows errors', async () => {
    const wrapper = mount(Component, {
      global: {
        plugins: [
          createTestingPinia({
            createSpy: vi.fn,
            stubActions: false,
            initialState: {
              notification: {
                notifications: [
                  {
                    id: 1,
                    type: NotificationTypeEnum.INFO,
                    title: 'title1',
                    subTitle: null,
                    content: 'content1'
                  },
                  {
                    id: 2,
                    type: NotificationTypeEnum.WARNING,
                    title: 'title2',
                    subTitle: 'sub title2',
                    content: 'content2'
                  }
                ]
              }
            }
          })
        ]
      }
    })

    const notifications = wrapper.findAll('.toast')
    expect(notifications.length).toBe(2)
    expect(notifications[0].text()).toBe('title1content1')
    expect(notifications[1].text()).toBe('title2sub title2content2')

    const store = useNotificationStore()
    await notifications[0].find('.btn-close').trigger('click')
    expect(store.removeNotification).toHaveBeenCalledTimes(1)
    expect(store.removeNotification).toHaveBeenLastCalledWith(1)
    expect(wrapper.findAll('.toast').length).toBe(1)
  })
})
