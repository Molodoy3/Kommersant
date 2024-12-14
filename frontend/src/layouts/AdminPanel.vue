<script setup lang="ts">

import { RouterView } from "vue-router";
</script>

<template>
  <main class="admin-panel">
    <div class="admin-panel__container">
      <aside class="admin-panel__menu">
        <ul class="admin-panel__list">
          <li><a href="">ываыва</a></li>
          <li><a href="">ываыва</a></li>
          <li><a href="">ываыва</a></li>
        </ul>
        <a href="" class="admin-panel__button button">Выйти</a>
      </aside>
      <div class="admin-panel__content">
        <RouterView />
      </div>
    </div>
  </main>
</template>

<style scoped></style>
