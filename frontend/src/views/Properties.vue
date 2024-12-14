<script setup lang="ts">

import Preloader from "@/components/Preloader.vue";
import {useRoute} from "vue-router";
import {computed, onMounted, ref} from "vue";
import axios from "axios";
import Pagination from "@/components/Pagination.vue";

//для первоначального вывода объектов берем из get параметра текущий номер страинцы
const route = useRoute();
const currentDefaultPage = computed(() => {
  return Number(route.query.page) || 1;
});

const properties = ref<any | null>(null)
axios.get('/properties', {
  params: {
    page: currentDefaultPage.value
  }})
  .then(res => {
    properties.value = res.data
    updateButtonLinks()
  })
  .catch(error => {
    console.error(error)
  })

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})

//небольшая функция для замены на норм текст кнопок
function updateButtonLinks() {
  if (properties.value && properties.value.links) {
    properties.value.links[0].label = 'Назад'
    properties.value.links[properties.value.links.length - 1].label = 'Вперед'
  }
}

let currentPage = currentDefaultPage.value;
const limitPages = 10;
let isLoading = ref(false);

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})

const handleScroll = () => {
  const propertiesElement = document.querySelector('.properties');
  if (propertiesElement && window.innerHeight + window.scrollY >= propertiesElement.clientHeight) {
    //проверяем не привышаем ли общее кол-во страниц
    if (properties.value && currentPage < properties.value.last_page) {
      //проверяем не привышаем ли лимит загруженных страниц
      if (currentPage - currentDefaultPage.value < limitPages) {
        //загружаем
        // Проверяем, не идет ли уже загрузка
        if (!isLoading.value) {
          isLoading.value = true;
          currentPage++;
          axios.get('/properties', {
            params: {
              page: currentPage
            }
          })
            .then(res => {
              properties.value.data = [...properties.value.data, ...res.data.data]
              properties.value.links = res.data.links
              updateButtonLinks()
            })
            .catch(error => {
              isLoading.value = false;
              console.log(error)
            })
            .finally(() => {
              isLoading.value = false; // Сбрасываем флаг загрузки после завершения
            });
        }
      }
    }
  }
}

</script>

<template>
  <section id="properties" class="properties">
    <div v-if="properties" class='properties__container'>
      <h2 class="properties__title title">Объекты в продаже</h2>
      <div class="properties__items">
        <RouterLink :to="{ name: 'property', params: { id: property.id } }" v-for="property in properties.data" class="properties__item">
          <div class="properties__image">
            <picture>
              <source :srcset='property.image + ".webp"' :type='"image/webp"'>
              <source :srcset='property.image + "." + property.image_extension' :type='"image/" + property.image_extension'>
              <img v-lazy='property.image + ".webp"' alt='объект недвижимости'>
            </picture>
            <div class="properties__labels">
              <div
                :style="'background-color: #' + label.color"
                v-for="label in property.labels" class="properties__label">{{ label.name }}</div>
            </div>
          </div>
          <div class="properties__content">
            <h4 class="properties__title-item">{{ property.name }}</h4>
            <div class="properties__price">{{ property.prise }}</div>
            <ul class="properties__parameters">
              <li class="rent" v-if="property.transaction_type_id === 1">— {{ property.transaction_type.name }}</li>
              <li>— {{ property.type.name }}</li>
              <li>— {{ property.address }}</li>
              <li>— {{ property.square }}м²</li>
            </ul>
          </div>
        </RouterLink>
      </div>
    </div>
    <Preloader v-else />
    <Preloader v-if="isLoading"/>
    <Pagination v-if="!isLoading && properties && properties.links" :links="properties.links"/>
  </section>
</template>

<style scoped lang="scss">
@use 'sass:math';
@import '../assets/scss/functions';

.properties {
  @include adaptiv-value('padding-top', 50, 30, 1);
  &__container {}

  &__title {}

  &__items {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(rem(270), 1fr));
    @include adaptiv-value('gap', 30, 15, 1);
  }

  &__item {
    @include adaptiv-value('border-radius', 14, 10, 1);
    overflow: hidden;
    box-shadow: 0px 4px 18px 0px rgba(0, 0, 0, 0.11);
    display: flex;
    flex-direction: column;
    transition: transform 0.4s ease-out;
    &:focus {
      transform: scale(1.1);
    }
    @media (any-hover: hover) {
      &:hover{
        transform: scale(1.05);
      }
    }
  }

  &__image {
    @include adaptiv-value('height', 210, 170, 1);
    position: relative;
    img {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }
  &__title-item{
    color: var(--blue);
    margin-bottom: em(10, 16);
    font-weight: 700;
  }
  &__labels {
    position: absolute;
    top: rem(15);
    right: rem(13);
    display: flex;
    flex-wrap: wrap;
    gap: rem(5);
  }
  &__content{
    padding-top: rem(13);
    @include adaptiv-value('padding-left', 20, 10, 1);
    @include adaptiv-value('padding-right', 20, 10, 1);
    @include adaptiv-value('padding-bottom', 25, 15, 1);
  }
  &__label {
    padding: rem(6) rem(15);
    color: var(--white);
    text-transform: uppercase;
    font-weight: 600;
    font-size: rem(10);
    //background-color: var(--yellow);
    border-radius: rem(6);

  }

  &__price {
    font-weight: 700;
    @include adaptiv-value('font-size', 20, 18, 1);
    margin-bottom: em(10, 20);
    flex: 1 1 auto;
  }

  &__parameters {
    color: var(--grey);
  }
}
</style>
