<script setup lang="ts">

import {RouterView, useRoute} from "vue-router";
import {computed} from "vue";
import axios from "axios";
import {routes} from "@/api.config.js";
import router from "@/router";

//берем ссылки для меню админ панели из маршрутов
const route = useRoute();
const adminRoutes = computed(() => {
  // Находим родительский маршрут /admin/
  const adminRoute = route.matched.find(r => r.path === '/admin/');
  // Возвращаем массив дочерних маршрутов, если родительский найден
  return adminRoute ? adminRoute.children : [];
});

async function logout() {
  if (confirm('Вы уверены что хотите выйти?')) {
    await axios.post(routes.admin_logout).then(() => {
      localStorage.removeItem('authToken')
      router.push(routes.admin_login)
    })
  }
}

</script>

<template>
  <main class="admin-panel">
    <div class="admin-panel__container">
      <aside class="admin-panel__menu">
        <ul class="admin-panel__list">
          <li v-for="route in adminRoutes" :key="route.name">
            <router-link v-if="route.meta && route.meta.title" :to="{ name: route.name }">{{ route.meta.title }}</router-link>
          </li>
        </ul>
        <button type="button" @click="logout" class="admin-panel__button button">Выйти</button>
      </aside>
      <div class="admin-panel__content">
        <RouterView />
      </div>
    </div>
  </main>
</template>

<style scoped></style>
