<script setup lang="ts">

import axios from "axios";
import {routes} from "@/api.config.js";
import {ref} from "vue";
import {useRoute} from "vue-router";
import Preloader from "@/components/Preloader.vue";
import Pagination from "@/components/Pagination.vue";

const route = useRoute();
const currentDefaultPage = route.query.page || 1;

const elements = ref<any | null>(null)
axios.get(routes.properties, {params: {page: currentDefaultPage, path: route.path}})
  .then(data => {
    elements.value = data.data
    updateButtonLinks()
  })
  .catch(error => {
    console.error(error)
  })

function updateButtonLinks() {
  if (elements.value && elements.value.links) {
    elements.value.links[0].label = 'Назад'
    elements.value.links[elements.value.links.length - 1].label = 'Вперед'
  }
}

</script>

<template>
  <div class="admin-panel__top">
    <h1 class="admin-panel__title title">{{route.meta.title}}</h1>
    <RouterLink :to="{name: 'admin_properties_add'}" class="admin-panel__add button">Добавить</RouterLink>
  </div>
  <div v-if="elements && elements.data" class="admin-panel__wrap">
    <table class="admin-panel__table">
      <thead class="admin-panel__header-table">
      <tr>
        <td>ID</td>
        <td>Название</td>
        <td>Описание</td>
        <td>Цена</td>
        <td>Адрес</td>
        <td>Тип объекта</td>
        <td>Площадь</td>
        <td>Ссылка</td>
        <td>Координаты</td>
        <td>Надписи</td>
        <td>Тип продажи</td>
        <td>Картинка</td>
        <td>Дата</td>
      </tr>
      </thead>
      <tbody>
      <tr v-for="item in elements.data">
        <td>{{item.id}}</td>
        <td><RouterLink :to="{name: 'admin_properties_edit', params: {id: item.id}}">{{item.name}}</RouterLink></td>
        <td>{{item.description}}</td>
        <td>{{item.prise}}</td>
        <td>{{item.address}}</td>
        <td>{{item.type.name}}</td>
        <td>{{item.square}}</td>
        <td>{{item.link}}</td>
        <td>{{item.latitude}}, {{item.longitude}}</td>
        <td>
          <div class="properties__labels">
            <div :style="'background-color: #' + label.color" v-for="label in item.labels"
                 class="properties__label">{{ label.name }}</div>
          </div>
        </td>
        <td>{{item.transaction_type.name}}</td>
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
  </div>
  <Preloader v-else/>
  <Pagination v-if="elements && elements.links" :links="elements.links"/>
</template>

<style scoped>

</style>
