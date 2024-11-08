<script setup lang="ts">

import { RouterLink } from "vue-router";
import Tg from "@/components/icons/Tg.vue";
import WhatsApp from "@/components/icons/WhatsApp.vue";
import Vk from "@/components/icons/Vk.vue";
</script>

<template>
  <footer class="footer">
    <div class='footer__container'>
      <div class="footer__body">
        <RouterLink :to="{ name: 'home' }" class="footer__logo">
          <picture>
              <source data-srcset='/img/logo.webp' type='image/webp'>
              <img v-lazy='"/img/logo.jpg"' alt='Логотип'>
          </picture>
        </RouterLink>
        <div class="footer__links">
          <ul class="footer__social-list">
            <li><a href="">
                <tg />
              </a></li>
            <li><a href="">
                <vk />
              </a></li>
            <li><a href=""><whats-app /></a></li>
          </ul>
          <ul class="footer__list">
            <li>
              <RouterLink to="">Объекты</RouterLink>
            </li>
            <li>
              <RouterLink to="">Услуги</RouterLink>
            </li>
            <li>
              <RouterLink to="">Новости</RouterLink>
            </li>
            <li>
              <RouterLink to="">Контакты</RouterLink>
            </li>
          </ul>
        </div>
      </div>
      <div class="footer__bottom">
        <div class="footer__org">© 2024 КОММЕРСАНТ. Все права защищены.</div>
        <RouterLink class="footer__policy" to="">Политика конфидициальности</RouterLink>
      </div>
    </div>
  </footer>
</template>

<style scoped lang="scss">
@use 'sass:math';
@import '../assets/scss/functions';

.footer {
  background-color: #1E1E1E;

  &__container {}

  &__body {
    padding: rem(25) 0;
    border-bottom: 1px solid rgb(69, 69, 69);
    display: flex;
    align-items: center;
    justify-content: space-between;
    column-gap: rem(20);
    flex-wrap: wrap;
  }

  &__logo {
    @include adaptiv-value('width', 120, 80, 1);

    @media (max-width: $md3) {
      margin: 0 auto;
      margin-bottom: rem(20);
    }

    img {
      max-width: 100%;
    }
  }

  &__links {
    @media (max-width: $md3){

      width: 100%;
    }
  }

  &__social-list {
    display: flex;
    column-gap: rem(12);
    justify-content: flex-end;

    @media (max-width: $md3) {
      justify-content: center;
    }

    margin-bottom: rem(20);

    li {
      flex: 0 0 rem(31);
      width: rem(31);
      height: rem(31);
      border-radius: rem(3);
      background-color: var(--white);
      display: flex;
      justify-content: center;
      align-items: center;
      transition: background-color 0.3s ease;

      &:focus {
        background-color: var(--yellow);
      }

      @media (any-hover: hover) {
        &:hover {
          background-color: var(--yellow);
        }
      }
    }
  }

  &__list {
    justify-content: flex-end;

    @media (max-width: $md3) {
      justify-content: center;
    }

    display: flex;
    @include adaptiv-value('column-gap', 44, 20, 1);
    color: var(--white);
    row-gap: rem(5);
    flex-wrap: wrap;

    a {
      transition: color 0.3s ease;

      &:focus {
        color: var(--yellow);
      }

      @media (any-hover: hover) {
        &:hover {
          color: var(--yellow);
        }
      }
    }
  }

  &__bottom {
    display: flex;
    justify-content: space-between;
    color: var(--grey);
    font-size: rem(12);
    @include adaptiv-value('padding-top', 20, 15, 1);
    @include adaptiv-value('padding-bottom', 20, 15, 1);

    a {
      transition: color 0.3s ease;

      &:focus {
        color: var(--yellow);
      }

      @media (any-hover: hover) {
        &:hover {
          color: var(--yellow);
        }
      }
    }
  }

  &__org {}

  &__policy {}
}
</style>
