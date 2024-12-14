import {createRouter, createWebHistory} from 'vue-router'
import Home from '../views/Home.vue'
import Default from "../layouts/Default.vue";
import News from "@/views/News.vue";
import New from "@/views/New.vue";
import Properties from "@/views/Properties.vue";
import Property from "@/views/Property.vue";
import NotFound from "@/views/NotFound.vue";
import Login from "@/views/admin/Login.vue";
import axios from "axios";
import {routes} from "@/api.config.js";
import AdminPanel from "@/layouts/AdminPanel.vue";
import HomeAdmin from "@/views/admin/HomeAdmin.vue";
import NewsAdmin from "@/views/admin/NewsAdmin.vue";

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

        { path: '/properties', name: 'properties', component: Properties },
        { path: '/property/:id', name: 'property', component: Property }
      ]
    },
    {
      path: "/:pathMatch(.*)*",
      name: '404',
      component: NotFound
    },
    {
      path: "/admin/login",
      name: "admin_login",
      component: Login
    },
    {
      path: '/admin/',
      component: AdminPanel,
      children: [
        {path: 'home', name: "admin_home", component: HomeAdmin},
        {path: 'news', name: "admin_news", component: NewsAdmin},
      ],
      meta: { requiresAuth: true }
    }
  ]
})

//на каждый маршрут накладываем проверку
router.beforeEach(async (to, from, next) => {

  if (to.meta.requiresAuth) {
    const token = localStorage.getItem('authToken')
    if (token)
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

    const check = await isAuthenticated()
    if (!check)
      next('/admin/login')
    else
      next()
  } else
    next()
});
async function isAuthenticated(): Promise<boolean> {
  try {
    const response = await axios.get(routes.api_token);
    return response.data.authenticated;
  } catch (error) {
    console.error("Ошибка проверки аутентификации:", error);
    return false;
  }
}

export default router
