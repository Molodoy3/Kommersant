<script setup lang="ts">

import { defineProps, toRefs } from "vue";


const props = defineProps<{
  links: Array<any>;
}>();

// Используем toRefs для деструктуризации props
const { links } = toRefs(props);
</script>

<template>
  <ul class="news__pagination pagination">
    <li class="pagination__item" v-if="links.length > 3" v-for="link in links"
      :class="{ 'pagination__item_active': link.active, 'button-border': link.label === 'Назад' || link.label === 'Вперед' }">
      <a class="pagination__link" :href="link.url" :class="{
        'active': link.active, 'pagination__item_unactive': !link.url && !link.active,
      }">
        {{ link.label }}
      </a>
    </li>
  </ul>
</template>

<style scoped lang="scss">
@use 'sass:math';
@import '../assets/scss/functions';

.pagination Я{
  display: flex;
  flex-wrap: wrap;
  overflow-x: auto;
  justify-content: center;
  @include adaptiv-value('column-gap', 16, 5, 1);
  @include adaptiv-value('margin-top', 40, 20, 1);
  row-gap: rem(10);

  &__item {
    @include adaptiv-value('font-size', 14, 12, 1);
    border-radius: rem(10);
    overflow: hidden;
    &_button {

      &_forward {
        .pagination__link {
          flex-direction: row-reverse;

          &::before {

            transform: rotate(-90deg);
          }
        }
      }
    }

    &_unactive {
      pointer-events: none;
    }

    &_active {
      border-radius: rem(10);
      background-color: var(--yellow);
      color: #fff;
      pointer-events: none;
    }
  }

  &__link {
    padding: rem(13) rem(12);
    display: inline-block;
    transition: background-color 0.3s ease;

    &:focus {
      color: #fff;
      background-color: var(--yellow);
    }

    @media (any-hover: hover) {
      &:hover {
        color: #fff;
        background-color: var(--yellow);
      }
    }
  }

  .button-border {
    padding: 0;

    .pagination__link {
      &:focus {
        background-color: transparent;
        color: inherit;
      }

      @media (any-hover: hover) {
        &:hover {
          background-color: transparent;
          color: inherit;
        }
      }
    }

  }
}
</style>
