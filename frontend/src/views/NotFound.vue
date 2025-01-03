<script setup lang="ts">

import Header from "@/components/Header.vue";
import Footer from "@/components/Footer.vue";
</script>

<template>
  <Header />
  <main class="page">
    <section class="error-section">
      <div class="error-section__container">
        <h1 class="error-section__title title">404</h1>
        <div class="error-section__text">Страница не найдена</div>
        <RouterLink :to="{name: 'home'}" class="error-section__button button">Перейти на главную</RouterLink>
      </div>
    </section>
  </main>
  <Footer />
</template>

<style scoped>

</style>
