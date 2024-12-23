<script setup lang="ts">

import axios from "axios";
import {routes} from "@/api.config.js";
import {ref} from "vue";
import Preloader from "@/components/Preloader.vue";

const services = ref<any | null>(null)
axios.get(routes.services)
  .then(data => {
    services.value = data.data
  })
  .catch(error => {
    console.error(error)
  })

</script>

<template>
  <div class="admin-panel__top">
    <h1 class="admin-panel__title title">Услуги</h1>
    <RouterLink :to="{name: 'admin_categories_add'}" class="admin-panel__add button">Добавить</RouterLink>
  </div>
  <table v-if="services" class="admin-panel__table">
    <thead class="admin-panel__header-table">
    <tr>
      <td>ID</td>
      <td>Название</td>
      <td>Описание</td>
      <td>Цена</td>
      <td>Категория</td>
      <td>Дата добавления</td>
    </tr>
    </thead>
    <tbody>
    <tr v-for="item in services">
      <td>{{item.id}}</td>
      <td><RouterLink :to="{name: 'admin_services_edit', params: {id: item.id}}">{{item.title}}</RouterLink></td>
      <td>{{item.description}}</td>
      <td>{{item.price}}</td>
      <td>{{item.category.name}}</td>
      <td>{{item.created_at}}</td>
    </tr>
    </tbody>
  </table>
  <Preloader v-else/>
</template>

<style scoped>

</style>
