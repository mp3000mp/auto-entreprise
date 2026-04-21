import { describe, expect, test, vi } from 'vitest'
import { extractMessageFromError, notifyError } from '@/stores/notification/utils'
import { createTestingPinia } from '@pinia/testing'
import { useNotificationStore } from '@/stores/notification'

describe('extractMessageFromError', () => {
  test('extracts message from error with message property', () => {
    expect(extractMessageFromError(new Error('oops'))).toBe('oops')
  })

  test('extracts detail from error with detail property', () => {
    expect(extractMessageFromError({ detail: 'not found' })).toBe('not found')
  })

  test('returns fallback for null', () => {
    expect(extractMessageFromError(null)).toBe('Unexpected error')
  })

  test('returns fallback for string', () => {
    expect(extractMessageFromError('raw string')).toBe('Unexpected error')
  })
})

describe('notifyError', () => {
  test('adds prefixed error to notification store', () => {
    createTestingPinia({ createSpy: vi.fn, stubActions: false })
    const store = useNotificationStore()

    notifyError('Login failed: ', new Error('bad credentials'))

    expect(store.notifications).toContainEqual(
      expect.objectContaining({ content: 'Login failed: bad credentials' })
    )
  })
})
