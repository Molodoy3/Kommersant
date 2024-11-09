import './assets/main.scss'
import './assets/app.js'
import VueLazyLoad from 'vue3-lazyload'
import { createMetaManager } from 'vue-meta'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import axios from 'axios'

import App from './App.vue'
import router from './router'

//axios.defaults.baseURL = 'http://147.45.161.249:8080/api';
axios.defaults.baseURL = import.meta.env.VITE_API_URL;

//ОЧЕНЬ ВАЖНО ВКЛЮЧИТЬ ПЕРЕДАЧУ КУКИ ПРИ ЗАПРОСАХ, ТАК КАК ТАМ ХРАНЯТЬСЯ ДАННЫЕ СЕССИИ И ЕСЛИ НЕ ПЕРЕДАВАТЬ, ТО
//СЕССИИ В LARAVEL БУДУТ СОЗДАВАТЬСЯ ПОССТОЯННО НОВЫЕ, ТАК КАК НЕ БУДУТ ПРОХОДИТЬ ПРОВЕРКУ
//В LARAVEL ТАКЖЕ В config/cors для сессий устанавливаем true поле 'supports_credentials' => true,
axios.defaults.withCredentials = true;
/*async function getCsrfToken() {
  try {
    //const response = await axios.get('/csrf-token');
    //return response.data.csrfToken;
  } catch (error) {
    console.error('Ошибка при получении CSRF-токена:', error);
  }
}
// Вызов функции и установка токена в заголовки
getCsrfToken().then(csrfToken => {
  if (csrfToken) {
    //axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
  }
});*/


const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(createMetaManager())
app.use(VueLazyLoad, {
  //preLoad: 1.3,
  //error: '',
  //loading: '/img/icons/preloader.gif',
  //attempt: 1
})


app.mount('#app')
