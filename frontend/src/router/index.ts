import {createRouter, createWebHistory} from 'vue-router'
import Home from '../views/Home.vue'
import Default from "../layouts/Default.vue";
import News from "@/views/News.vue";
import New from "@/views/New.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: Default, // Используем DefaultLayout как родительский
      children: [
        { path: '', name: 'home', component: Home },
        { path: '/news', name: 'news', component: News },
        { path: '/news/:id', name: 'new', component: New },
      ]
    },
  ]
})

export default router
