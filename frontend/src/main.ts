import './assets/main.scss'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import FontAwesomeIcon from "@/misc/font-awesome";

import App from './App.vue'
import router from './router'

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.component('font-awesome-icon', FontAwesomeIcon)

app.mount('#app')
