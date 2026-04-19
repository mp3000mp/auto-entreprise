import { useNotificationStore } from '@/stores/notification/index'

export function extractMessageFromError(err: unknown): string {
  if (err !== null && typeof err === 'object' && 'message' in err) {
    return String(err.message)
  }
  if (err !== null && typeof err === 'object' && 'detail' in err) {
    return String(err.detail)
  }
  return 'Unexpected error'
}

export function notifyError(messagePrefix: string, err: unknown) {
  const notificationStore = useNotificationStore()
  notificationStore.addError(messagePrefix + extractMessageFromError(err))
}
