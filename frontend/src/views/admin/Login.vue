<script setup lang="ts">

import {ref} from "vue";
import axios from "axios";
import {routes} from "@/api.config.js";

const errors = ref<any>({})

async function submit() {
  const form = document.forms.namedItem('login') as HTMLFormElement | null;
  if (form) {
    const formData = new FormData(form);

    axios.defaults.headers.common['X-CSRF-TOKEN'] = await axios.get(routes.csrf).then(res => res.data)


    await axios.post(routes.admin_login, formData)
      .then((data) => {
        //console.log(data)
        //console.log(data.data.token)
        localStorage.setItem('authToken', data.data.token);
      })
      .catch(error => {
        errors.value = error.response.data.errors
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
          <input name="name" id="login" type="text" placeholder="Введите логин" class="input" />
        </div>
        <div class="form__item">
          <label for="password" class="label">Пароль</label>
          <input name="password" :class="errors && errors.password ? 'error' : null" id="password" type="password" placeholder="Введите пароль"
            class="input" />
          <div v-if="errors && errors.password" class="form__error">{{ errors.password[0] }}</div>
        </div>
        <button class="button" type="submit">Отправить</button>
      </form>
    </div>
  </section>
</template>

<style scoped></style>
