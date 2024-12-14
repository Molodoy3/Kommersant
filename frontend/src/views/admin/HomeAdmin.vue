<script setup lang="ts">

import axios from "axios";

axios.get('/admin/home')
  .then(response => {
    console.log('Данные пользователя:', response.data);
  })
  .catch(error => {
    console.error('Ошибка запроса:', error);
    if (error.response.status === 401) {
      //removeToken(); // Удалить токен при ошибке 401 (Unauthorized)
      // Перенаправить на страницу входа или выполнить другие действия
      console.log('Сессия истекла. Перенаправление на страницу входа...');
    }
  });

</script>

<template>
  <h1 class="title">Вы находитесь в администравной панели</h1>
  <p>для редактирования контента перейдите на вкладку справа</p>
</template>

<style scoped>

</style>
