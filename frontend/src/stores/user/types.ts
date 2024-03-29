export type User = {
  id: number
  username: string
  email: string
  roles: string[]
  isTotpAuthenticationEnabled: boolean
}
