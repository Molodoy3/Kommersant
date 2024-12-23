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

axios.get(routes.categories + '/' + id)
  .then(res => {
    element.value = res.data;
  })
  .catch(error => {
    console.log(error)
  })

async function submit() {
  const form = document.forms.namedItem('form') as HTMLFormElement | null;
  if (form) {
    const formData = new FormData(form)
    formData.append('_method', 'PATCH');

    await axios.post(routes.categories + '/' + id, formData)
      .then(() => {
        router.push({name: 'admin_categories'})
      })
      .catch(res => {
        errors.value = res.response.data.errors
      })
  }
}

async function deleteElement() {
  if (confirm('Вы уверены, что хотите удалить запись?')) {
    axios.delete(routes.categories + '/' + id)
      .then(() => {
        router.push({name: 'admin_categories'})
      })
      .catch(() => {
        alert('произошла ошибка. Если вы не удалили связанные услуги, то нельзя удалить категорию')
        router.push({name: 'admin_categories'})
      })
  }
}

</script>

<template>
  <h1 class="admin-panel__title title">Редактирование категории</h1>
  <form v-if="element" @submit.prevent="submit" name="form" action="" class="admin-panel__form form">
    <div class="form__item">
      <label for="name" class="label">Название</label>
      <input v-model="element.name" :class="errors.name ? 'error' : null" name="name" id="name" type="text" class="input"/>
      <div v-if="errors.name" class="form__error">{{ errors.name[0] }}</div>
    </div>
    <button class="button" type="submit">Сохранить</button>
    <button @click="deleteElement" class="button admin-panel__delete" type="button" >Удалить</button>
  </form>
</template>

<style scoped>

</style>
