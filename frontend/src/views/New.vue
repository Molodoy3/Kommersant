<script setup lang="ts">

import { useRoute } from "vue-router";
import { ref } from "vue";
import axios from "axios";
import {routes} from "@/api.config.js";

const article = ref<any | null>(null)

const idNew = useRoute().params.id;
axios.get(routes.articles + idNew)
  .then(res => {
    article.value = res.data
  })

</script>

<template>
  <section v-if="article" class="new">
    <div class='new__container'>
      <div class="new__header">
        <div class="new__image">
          <picture>
            <source :srcset='article.image + ".webp"' :type='"image/webp"'>
            <source :srcset='article.image + "." + article.image_extension' :type='"image/" + article.image_extension'>
            <img v-lazy='article.image + ".webp"'>
          </picture>
        </div>
        <div class="new__info">
          <h1 class="new__title title">{{ article.title }}</h1>
          <div class="new__date">{{ article.created_at }}</div>
        </div>
      </div>
      <div class="new__text">
        {{ article.description }}
      </div>
    </div>
  </section>
</template>

<style scoped lang="scss">
@use 'sass:math';
@import '../assets/scss/functions';

.new {
  @include adaptiv-value('padding-top', 60, 25, 1);
  &__container {
    background-color: var(--white);
    @include adaptiv-value('padding-top', 30, 20, 1);
    @include adaptiv-value('padding-bottom', 30, 20, 1);
    @include adaptiv-value('padding-left', 20, 10, 1);
    @include adaptiv-value('padding-right', 20, 10, 1);
    @include adaptiv-value('border-radius', 15, 10, 1);
  }

  &__header {
    @media (min-width: 600px){
      display: flex;
      justify-content: space-between;
      column-gap: rem(20);
    }
    @include adaptiv-value('margin-bottom', 30, 15, 1);
  }

  &__image {
    flex: 0 0 rem(345);
    width: rem(345);
    position: relative;
    min-height: rem(200);
    @include adaptiv-value('border-radius', 18, 12, 1);
    overflow: hidden;
    @media (max-width: 600px){
      margin-bottom: rem(20);
    }
    img {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }

  &__info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    @media (min-width: 600px){

    align-items: flex-end;
    }

  }

  &__title {
    @include adaptiv-value('margin-bottom', 95, 20, 1);
    flex: 1 1 auto;
    @media (min-width: 600px){
        text-align: right;
    }
    max-width: rem(534);
  }

  &__date {
    padding: rem(3) rem(10);
    color: var(--grey);
    background-color: rgb(245, 245, 245);
    font-size: rem(12);

    border-radius: rem(4);
  }

  &__text {}
}
</style>
