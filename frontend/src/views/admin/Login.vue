<script setup lang="ts">

import {ref} from "vue";
import axios from "axios";
import {routes} from "@/api.config.js";
import router from "@/router";

const error = ref<any>({})

async function submit() {
  const form = document.forms.namedItem('login') as HTMLFormElement | null;
  if (form) {
    const formData = new FormData(form);
    axios.defaults.headers.common['X-CSRF-TOKEN'] = await axios.get(routes.csrf).then(res => res.data)
    await axios.post(routes.admin_login, formData)
      .then((data) => {
        //устанавливаем токен в локальное хранилище
        localStorage.setItem('authToken', data.data.token);
        router.push({name: 'admin_home'});
      })
      .catch(errors => {
        error.value.value = errors.response.data.error
        if (errors.response.status === 429) {
          error.value.value = 'Превышен лимит количества попыток'
        }
      })
  }
}

</script>

<template>
  <section class="login">
    <div class='login__container'>
      <h1 class="login__title title">Вход в административную панель</h1>
      <form @submit.prevent="submit" name="login" action="" class="login__form form">
        <div class="form__item">
          <label for="login" class="label">Логин</label>
          <input :class="error.value ? 'error' : null" name="name" id="login" type="text" placeholder="Введите логин" class="input" />
        </div>
        <div class="form__item">
          <label for="password" class="label">Пароль</label>
          <input name="password" :class="error.value ? 'error' : null" id="password" type="password" placeholder="Введите пароль"
            class="input" />
          <div v-if="error.value" class="form__error">{{ error.value }}</div>
        </div>
        <button class="button" type="submit">Отправить</button>
      </form>
    </div>
  </section>
</template>

<style scoped></style>
