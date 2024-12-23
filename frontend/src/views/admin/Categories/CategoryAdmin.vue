<script setup lang="ts">

import axios from "axios";
import {routes} from "@/api.config.js";
import {ref} from "vue";
import Preloader from "@/components/Preloader.vue";

const categories = ref<any | null>(null)
axios.get(routes.categories)
  .then(data => {
    categories.value = data.data
  })
  .catch(error => {
    console.error(error)
  })

</script>

<template>
  <div class="admin-panel__top">
    <h1 class="admin-panel__title title">Категории</h1>
    <RouterLink :to="{name: 'admin_categories_add'}" class="admin-panel__add button">Добавить</RouterLink>
  </div>
  <table v-if="categories" class="admin-panel__table">
    <thead class="admin-panel__header-table">
    <tr>
      <td>ID</td>
      <td>Название</td>
    </tr>
    </thead>
    <tbody>
    <tr v-for="item in categories">
      <td>{{item.id}}</td>
      <td><RouterLink :to="{name: 'admin_categories_edit', params: {id: item.id}}">{{item.name}}</RouterLink></td>
    </tr>
    </tbody>
  </table>
  <Preloader v-else/>
</template>

<style scoped>

</style>
