import './assets/style/main.scss'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import FontAwesomeIcon from '@/misc/font-awesome'

import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'

import App from './App.vue'
import router from './router'

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.component('font-awesome-icon', FontAwesomeIcon)
app.component('vue-date-picker', VueDatePicker)

app.mount('#app')
