<script setup lang="ts">

import axios from "axios";
import {onMounted, ref} from "vue";
import {computed} from 'vue';
import {useRoute} from 'vue-router';
import Pagination from "@/components/Pagination.vue";
import Preloader from "@/components/Preloader.vue";
import {routes} from "@/api.config.js";
import {useMeta} from "vue-meta";

useMeta({
  title: 'Новости — Агентство недвижимости Коммерсант',
  description: 'Коммерсант" в Пермском крае предлагает полный спектр услуг по купле-продаже объектов недвижимости. На нашем сайте вы найдете свежие новости рынка недвижимости региона, подробное описание наших услуг и сможете легко оставить заявку на понравившийся объект.',
})

//для первоначального вывода новостей берем из get параметра текущий номер страинцы
const route = useRoute();
const currentDefaultPage = computed(() => {
  return Number(route.query.page) || 1;
});

const news = ref<any | null>(null)
axios.get(routes.articles, {
  params: {
    page: currentDefaultPage.value
  }})
  .then(res => {
    news.value = res.data
    updateButtonLinks()
  })
  .catch(error => {
    console.error(error)
  })

//небольшая функция для замены на норм текст кнопок
function updateButtonLinks() {
  if (news.value && news.value.links) {
    news.value.links[0].label = 'Назад'
    news.value.links[news.value.links.length - 1].label = 'Вперед'
  }
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})

let currentPage = currentDefaultPage.value;
const limitPages = 10;
let isLoading = ref(false);
const handleScroll = () => {
  //проверяем докрутили ли до конца новостей
  //if (window.innerHeight + window.scrollY >= document.querySelector('.news').clientHeight) {
  const newsElement = document.querySelector('.news');
  if (newsElement && window.innerHeight + window.scrollY >= newsElement.clientHeight) {
    //проверяем не привышаем ли общее кол-во страниц
    if (news.value && currentPage < news.value.last_page) {
      //проверяем не привышаем ли лимит загруженных страниц
      if (currentPage - currentDefaultPage.value < limitPages) {
        //загружаем
        // Проверяем, не идет ли уже загрузка
        if (!isLoading.value) {
          isLoading.value = true;
          currentPage++;
          axios.get('/articles', {
            params: {
              page: currentPage
            }
          })
            .then(res => {
              news.value.data = [...news.value.data, ...res.data.data]
              news.value.links = res.data.links
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
  <section class="news">
    <div class='news__container'>
      <h1 class="news__title title">Все новости</h1>
      <div v-if="news && news.data" class="news__items">
        <article v-for="item in news.data" class="news__item">
          <div class="news__image">
            <picture>
              <source :srcset='item.image + ".webp"' :type='"image/webp"'>
              <source :srcset='item.image + "." + item.image_extension' :type='"image/" + item.image_extension'>
              <img v-lazy='item.image + ".webp"' alt='объект недвижимости'>
            </picture>
          </div>
          <h3 class="news__title-new">{{ item.title }}</h3>
          <div class="news__text">{{ item.description }}</div>
          <div class="news__bottom">
            <div class="news__date">{{ item.created_at }}</div>
            <RouterLink :to="{ name: 'new', params: { id: item.id } }" class="news__button button">Читать</RouterLink>
          </div>
        </article>
      </div>
      <Preloader v-else/>
      <Preloader v-if="isLoading"/>
      <Pagination v-if="!isLoading && news && news.links" :links="news.links"/>
    </div>
  </section>
</template>

<style scoped lang="scss">
@use 'sass:math';
@import '../assets/scss/functions';

.news {
  @include adaptiv-value('padding-top', 60, 30, 1);

  &__container {
  }

  &__title {
  }

  &__items {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(rem(350), 1fr));
    @include adaptiv-value('gap', 25, 15, 1);
  }

  &__item {
    @include adaptiv-value('border-radius', 23, 18, 1);
    background-color: var(--white);
    @include adaptiv-value('padding-top', 20, 15, 1);
    @include adaptiv-value('padding-right', 15, 10, 1);
    @include adaptiv-value('padding-left', 15, 10, 1);
    @include adaptiv-value('padding-bottom', 15, 10, 1);
    display: flex;
    flex-direction: column;
  }

  &__image {
    padding-bottom: 55%;
    position: relative;
    @include adaptiv-value('border-radius', 18, 10, 1);
    overflow: hidden;
    @include adaptiv-value('margin-bottom', 18, 12, 1);

    img {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;


    }
  }

  &__title-new {
    @include adaptiv-value('font-size', 20, 18, 1);
    color: var(--blue);
    font-weight: 700;
    margin-bottom: em(9, 20);

  }

  &__text {
    margin-bottom: rem(15);
    flex: 1 1 auto;

  }

  &__bottom {
    display: flex;
    justify-content: space-between;
    column-gap: rem(10);
    font-size: rem(12);
    align-items: center;
  }

  &__date {
    padding: rem(3) rem(10);
    border-radius: rem(4);
    color: var(--grey);
    background-color: rgb(245, 245, 245);
  }

  &__button {
    border-radius: rem(6);
    padding: rem(5) rem(40);
  }
}
</style>
