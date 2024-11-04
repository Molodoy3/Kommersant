<script setup lang="ts">

</script>

<template>
  <div class="loading">
    <img src='/img/icons/preloader.gif' alt='анимация загрузки'>
  </div>
</template>

<style scoped lang="scss">
@use 'sass:math';
@import '../assets/scss/functions';

.loading {
  display: flex;
  justify-content: center;
  margin-top: rem(30);
  margin-bottom: rem(20);
  img {
    width: rem(60);
  }
}

</style>
