<script setup lang="ts">

import axios from "axios";
import {routes} from "@/api.config.js";
import {ref} from "vue";
import router from "@/router";
import {useRoute} from "vue-router";

const errors = ref<any>({})

const route = useRoute();
const id = route.params.id;

const element = ref<any | null>(null);

axios.get(routes.services + '/' + id)
  .then(res => {
    element.value = res.data;
  })
  .catch(error => {
    console.log(error)
  })

const categories = ref<any | null>(null)
axios.get(routes.categories)
  .then(res => {
    categories.value = res.data
  })
  .catch(error => {console.error(error)})

async function submit() {
  const form = document.forms.namedItem('form') as HTMLFormElement | null;
  if (form) {
    const formData = new FormData(form)

    let price = formData.get('price');
    if (price) {
      price = (price as string).replace(/[\s.,]/g, '');
      const numericPrice = parseFloat(price);
      formData.set('price', numericPrice.toString());
    } else {
      console.warn('Price is null');
    }

    formData.append('_method', 'PATCH');

    await axios.post(routes.services + '/' + id, formData)
      .then(() => {
        router.push({name: 'admin_services'})
      })
      .catch(res => {
        errors.value = res.response.data.errors
      })
  }
}

async function deleteElement() {
  if (confirm('Вы уверены, что хотите удалить запись?')) {
    axios.delete(routes.services + '/' + id)
      .then(() => {
        router.push({name: 'admin_services'})
      })
      .catch(() => {
        alert('произошла ошибка. Если вы не удалили связанные услуги, то нельзя удалить категорию')
        router.push({name: 'admin_services'})
      })
  }
}

</script>

<template>
  <h1 class="admin-panel__title title">Редактирование услуги</h1>
  <form v-if="element && categories" @submit.prevent="submit" name="form" action="" class="admin-panel__form form">
    <div class="form__item">
      <label for="title" class="label">Название</label>
      <input v-model="element.title" :class="errors.title ? 'error' : null" name="title" id="title" type="text" class="input"/>
      <div v-if="errors.title" class="form__error">{{ errors.title[0] }}</div>
    </div>
    <div class="form__item">
      <label for="description" class="label">Описание</label>
      <textarea v-model="element.description" :class="errors.description ? 'error' : null" name="description" id="description" type="text" class="input"></textarea>
      <div v-if="errors.description" class="form__error">{{ errors.description[0] }}</div>
    </div>
    <div class="form__item">
      <label for="price" class="label">Цена</label>
      <input v-model="element.price" :class="errors.price ? 'error' : null" name="price" id="price" type="text" class="input"/>
      <div v-if="errors.price" class="form__error">{{ errors.price[0] }}</div>
    </div>
    <div class="form__item">
      <label for="category" class="label">Категория</label>
      <select name="category_id">
        <option v-for="item in categories" :value="item.id" :selected="item.id == element.category_id">
          {{ item.name }}
        </option>
      </select>
      <div v-if="errors.category" class="form__error">{{ errors.category }}</div>
    </div>
    <button class="button" type="submit">Сохранить</button>
    <button @click="deleteElement" class="button admin-panel__delete" type="button" >Удалить</button>
  </form>
</template>

<style scoped>

</style>
