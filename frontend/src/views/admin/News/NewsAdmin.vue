<script setup lang="ts">

import axios from "axios";
import {routes} from "@/api.config.js";
import {ref} from "vue";
import {useRoute} from "vue-router";
import Preloader from "@/components/Preloader.vue";
import Pagination from "@/components/Pagination.vue";

const route = useRoute();
const currentDefaultPage = route.query.page || 1;

const articles = ref<any | null>(null)
axios.get(routes.articles, {params: {page: currentDefaultPage, path: route.path}})
  .then(data => {
    articles.value = data.data
    updateButtonLinks()
  })
  .catch(error => {
    console.error(error)
  })

function updateButtonLinks() {
  if (articles.value && articles.value.links) {
    articles.value.links[0].label = 'Назад'
    articles.value.links[articles.value.links.length - 1].label = 'Вперед'
  }
}

</script>

<template>
  <div class="admin-panel__top">
    <h1 class="admin-panel__title title">Новости</h1>
    <RouterLink :to="{name: 'admin_news_add'}" class="admin-panel__add button">Добавить</RouterLink>
  </div>
  <table v-if="articles && articles.data" class="admin-panel__table">
    <thead class="admin-panel__header-table">
    <tr>
      <td>ID</td>
      <td>Название</td>
      <td>Описание</td>
      <td>Картинка</td>
      <td>Дата</td>
    </tr>
    </thead>
    <tbody>
      <tr v-for="item in articles.data">
        <td>{{item.id}}</td>
        <td><RouterLink :to="{name: 'admin_news_edit', params: {id: item.id}}">{{item.title}}</RouterLink></td>
        <td>{{item.description}}</td>
        <td>
          <div class="admin-panel__image">
            <picture>
              <source :srcset='item.image + ".webp"' :type='"image/webp"'>
              <source :srcset='item.image + "." + item.image_extension' :type='"image/" + item.image_extension'>
              <img v-lazy='item.image + ".webp"' alt='объект недвижимости'>
            </picture>
          </div>
        </td>
        <td><span class="date">{{item.created_at}}</span></td>
      </tr>
    </tbody>
  </table>
  <Preloader v-else/>
  <Pagination v-if="articles && articles.links" :links="articles.links"/>
</template>

<style scoped>

</style>
