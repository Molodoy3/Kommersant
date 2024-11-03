import { createApp } from 'vue';
import axios from 'axios';

import App from './components/App.vue'; // Ваш основной компонент

createApp(App).mount('#app');
window.axios = axios;


window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
