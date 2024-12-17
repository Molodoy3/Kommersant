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
import NewsAdmin from "@/views/admin/News/NewsAdmin.vue";
import NewsEdit from "@/views/admin/News/NewsEdit.vue";
import NewAdd from "@/views/admin/News/NewAdd.vue";
import PropertiesAdmin from "@/views/admin/Properties/PropertiesAdmin.vue";
import PropertyAdd from "@/views/admin/Properties/PropertyAdd.vue";
import PropertyEdit from "@/views/admin/Properties/PropertyEdit.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    // все общедоступное
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
    // 404 ошибка
    { path: "/:pathMatch(.*)*",  name: '404', component: NotFound },
    // вход в админ панель
    { path: "/admin/login", name: "admin_login", component: Login, meta: { requiresAuth: false } },
    // все админское
    {
      path: '/admin/',
      component: AdminPanel,
      children: [
        {path: 'home', name: "admin_home", component: HomeAdmin, meta: {title: 'Домашняя страница'}},

        {path: 'news', name: "admin_news", component: NewsAdmin, meta: {title: 'Новости'}},
        {path: 'news/edit/:id', name: "admin_news_edit", component: NewsEdit},
        {path: 'news/add', name: "admin_news_add", component: NewAdd},

        {path: 'properties', name: "admin_properties", component: PropertiesAdmin, meta: {title: 'Объекты'}},
        {path: 'properties/edit/:id', name: "admin_properties_edit", component: PropertyEdit},
        {path: 'properties/add', name: "admin_properties_add", component: PropertyAdd},
      ],
      meta: { requiresAuth: true }
    }
  ]
})

//на каждый маршрут накладываем проверку
router.beforeEach(async (to, from, next) => {
  //проверяем есть ли мета тег проверки авторизации
  if (to.meta.requiresAuth) {
    const token = localStorage.getItem('authToken')
    if (token) {
      //axios.defaults.headers.common['X-CSRF-TOKEN'] = await axios.get(routes.csrf).then(res => res.data)
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    }

    const check = await isAuthenticated()
    //проверяем токен авторизации. Если не прошел проверку, на страницу авторизации
    if (!check) {
      // для дальнейшей переадресации сохраняем путь
      localStorage.setItem('redirectFrom', to.fullPath || '/admin/home');
      next({name: 'admin_login'})
    }
    else
      next()
  }
  //если мы на странице авторизации и мы уже авторизованы, перенаправляем на домашнюю страницу
  else if (to.name === 'admin_login') {
    const token = localStorage.getItem('authToken')
    if (token)
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    const check = await isAuthenticated()
    if (check)
      next({name: 'admin_home'})
    else
      next()
  }
  //во всех других случаях ничего не делаем
  else
    next()
});
async function isAuthenticated(): Promise<boolean> {
  try {
    //axios.defaults.headers.common['X-CSRF-TOKEN'] = await axios.get(routes.csrf).then(res => res.data)
    const response = await axios.get(routes.api_token);
    return response.data.authenticated;
  } catch (error) {
    console.error("Ошибка проверки аутентификации:", error);
    return false;
  }
}

export default router
