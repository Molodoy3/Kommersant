import './assets/main.scss'
import './assets/app.js'
import VueLazyLoad from 'vue3-lazyload'
import { createMetaManager } from 'vue-meta'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import axios from 'axios'

import App from './App.vue'
import router from './router'

axios.defaults.baseURL = 'http://147.45.161.249:8080/api';

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(createMetaManager())
app.use(VueLazyLoad, {
  preLoad: 1.3,
  //error: '',
  loading: '/img/icons/preloader.gif',
  attempt: 1
})


app.mount('#app')
