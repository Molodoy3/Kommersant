<script setup lang="ts">

import {RouterLink} from "vue-router";
</script>

<template>
  <header class="header">
    <div class="header__container">
      <RouterLink :to="{ name: 'home' }" class="header__logo">АН Коммерсант</RouterLink>
      <div class='header__menu menu'>
        <div class='menu__icon'>
          <span></span>
        </div>
        <nav class='menu__body'>
          <ul class='menu__list'>
            <li>
              <RouterLink :to="{name: 'news'}" class='menu__link'>Объекты</RouterLink>
            </li>
            <li>
              <RouterLink to="" class='menu__link'>Услуги</RouterLink>
            </li>
            <li>
              <RouterLink :to="{name: 'news'}" class='menu__link'>Новости</RouterLink>
            </li>
            <li>
              <RouterLink to="" class='menu__link'>Контакты</RouterLink>
            </li>
            <li>
              <button data-button-for-open-custom-popup="application" class="header__connect-mobile button" type="button">Связаться</button>
            </li>
          </ul>
        </nav>
      </div>
      <button data-button-for-open-custom-popup="application" class="header__connect button"
              type="button">Связаться</button>
    </div>
  </header>
</template>

<style scoped lang="scss">
@use 'sass:math';
@import '../assets/scss/functions';

.header {
  position: fixed;
  width: 100%;
  z-index: 50;
  background-color: #fff;
  box-shadow: 0px 4px 20px 0px rgba(204, 169, 29, 0.12);

  &__container {
    min-height: rem(75);

    display: flex;
    justify-content: space-between;
    column-gap: rem(15);
    align-items: center;
  }

  &__connect-mobile {
    @media (min-width: $md3) {
      display: none;
    }
  }

  &__logo {
    position: relative;
    z-index: 2;
    padding: rem(11) rem(23);
    border-radius: rem(11);
    background-color: #fff;
    color: var(--blue);
    font-weight: 700;
    box-shadow: 0px 0px 8px 0px rgba(175, 150, 53, 0.29);
    transition: transform 0.3s ease, box-shadow 0.3s ease;

    &:focus {
      box-shadow: 0px 0px 12px 0px rgba(175, 151, 53, 0.356);
      transform: scale(.97);
    }

    @media (any-hover: hover) {
      &:hover {
        transform: scale(.97);
        box-shadow: 0px 0px 12px 0px rgba(175, 151, 53, 0.356);
      }
    }
  }

  &__menu {}



  &__connect {
    position: relative;
    z-index: 2;

    @media (max-width: $md3) {
      display: none;
    }
  }
}

.menu {

  &__icon {
    @media (min-width: $md3) {
      display: none;
    }

    display: block;
    width: rem(30);
    height: rem(18);
    position: relative;
    z-index: 5;
    transition: all 0.3s ease 0s;
    cursor: pointer;

    &::after,
    &::before,
    >span::after {
      content: '';
      position: absolute;
      width: 100%;
      height: rem(1.5);
      background-color: var(--color-main);
      right: 0;
      transition: all 0.5s ease 0s;
    }

    &::after {
      top: 0;
    }

    &::before {
      top: 50%;
      transform: translate(0, -50%);
    }

    >span::after {
      bottom: 0;
    }

    &.active {
      >span::after {
        transform: scale(0);
      }

      &::before {
        top: 50%;
        transform: translate(0, -50%) rotate(45deg);
      }

      &::after {
        transform: rotate(-45deg) translate(0, -50%);
        top: 50%;
      }
    }
  }

  &__body {
    @media (max-width: $md3) {
      position: fixed;
      width: 100%;
      height: 100%;
      background-color: #e7e7e7;
      top: 0;
      left: -100%;
      padding: rem(90) rem(10) rem(30) rem(10);
      overflow: auto;
      transition: left 0.5s ease, opacity 0.65s ease;
      opacity: 0;

      &.open {
        left: 0;
        opacity: 1;

        &::after {
          left: 0;
          opacity: 1;
        }
      }

      &::after {
        content: '';
        position: fixed;
        width: 100%;
        height: 75px;
        background-color: #fff;
        top: 0;
        left: -100%;
        opacity: 0;
        transition: left 0.5s ease, opacity 0.65s ease;
      }
    }

    >ul {
      @media (min-width: $md3) {
        display: flex;
        align-items: center;
        @include adaptiv-value('column-gap', 56, 20, 1);
      }

      >li {
        font-weight: 500;
        line-height: perc(19, 16);
        position: relative;
        display: inline-block;

        @media (max-width: $md3) {
          font-size: rem(20);
          display: block;

          &:not(:last-child) {
            margin-bottom: rem(12);
          }
        }

        a {
          color: inherit;

          @media (any-hover: hover) {
            transition: color 0.3s ease;

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
      }
    }
  }

  &__list {}

  &__link {}
}
</style>
