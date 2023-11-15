import type { User } from '@/stores/user/types'

export function initCurrentUser(): User | null {
  const json = sessionStorage.getItem('me')
  return json === null ? null : JSON.parse(json)
}

export function persistCurrentUser(user: User | null) {
  if (user === null) {
    sessionStorage.removeItem('me')
    return
  }
  sessionStorage.setItem('me', JSON.stringify(user))
}
