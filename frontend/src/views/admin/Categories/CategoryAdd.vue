<script setup lang="ts">

import axios from "axios";
import {routes} from "@/api.config.js";
import {ref} from "vue";
import router from "@/router";

const errors = ref<any>({})

async function submit() {
  const form = document.forms.namedItem('form') as HTMLFormElement | null;
  if (form) {
    const formData = new FormData(form)
    await axios.post(routes.categories, formData)
      .then(() => {
        router.push({name: 'admin_categories'})
      })
      .catch(res => {
        errors.value = res.response.data.errors
      })
  }
}

</script>

<template>
  <h1 class="admin-panel__title title">Добавление новой категории</h1>
  <form @submit.prevent="submit" name="form" action="" class="admin-panel__form form" enctype="multipart/form-data">
    <div class="form__item">
      <label for="name" class="label">Название</label>
      <input :class="errors.name ? 'error' : null" name="name" id="name" type="text" class="input"/>
      <div v-if="errors.name" class="form__error">{{ errors.name[0] }}</div>
    </div>
    <button class="button" type="submit">Сохранить</button>
  </form>
</template>

<style scoped>

</style>
