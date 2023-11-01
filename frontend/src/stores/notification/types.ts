export enum NotificationTypeEnum {
  DANGER = 'danger',
  INFO = 'info',
  SUCCESS = 'success',
  WARNING = 'warning'
}

export type Notification = {
  id: number
  type: NotificationTypeEnum
  title: string
  subTitle: string | null
  content: string
}
