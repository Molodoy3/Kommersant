<script setup lang="ts">

//import axios from "axios";
import {onMounted, ref, watchEffect, nextTick} from "vue";
import Adv01 from "@/components/icons/Adv-01.vue";
import Adv02 from "@/components/icons/Adv-02.vue";
import Adv03 from "@/components/icons/Adv-03.vue";
import Adv04 from "@/components/icons/Adv-04.vue";
import axios from "axios";
import Preloader from "@/components/Preloader.vue";

let categories = ref<any | null>(null)
axios.get('/categories', {withCredentials: true})
  .then(res => {
    categories.value = res.data
  })
  .catch((error) => {
    console.log(error)
  })
let services = ref<any | null>(null)
axios.get('/services')
  .then(res => {
    services.value = res.data
  })
  .catch((error) => {
    console.log(error)
  })

// watchEffect - ждем когда загрузятся данные через axios
watchEffect(async () => {
  if (categories.value && services.value) {

    // Ждем обновления DOM
    await nextTick();

    const tabs = document.querySelectorAll('[data-tabs]');
    if (tabs.length) {
      tabs.forEach(tab => {
        const activeFilter = tab.querySelector('.active');
        if (activeFilter) {
          const filterValue = activeFilter.dataset.filter;
          if (filterValue != '*') {
            tab.querySelectorAll('[data-filter-item]').forEach(filterItem => {
              if (filterItem.dataset.filterItem != filterValue) {
                filterItem.style.cssText = `position: absolute;opacity: 0;`;
              }
            });
          }
        }
      });
    }
  }
});


</script>

<template>
  <section class="welcome">
    <picture>
      <source data-srcset='/img/welcome/bg.webp' type='image/webp'>
      <img v-lazy='"/img/welcome/bg.jpeg"' alt='фон'>
    </picture>
    <div class='welcome__container'>
      <h1 class="welcome__title title">Агенство недвижимости
        «Коммернант»</h1>
      <div class="welcome__text">надежное агентство недвижимости, предлагающее услуги по покупке, продаже и аренде в
        Пермском крае
      </div>
      <a href="#objects" class="welcome__button button">Смотреть объекты</a>
    </div>
  </section>
  <section id="objects" class="objects">

  </section>
  <section class="advantages">
    <div class='advantages__container'>
      <h4 class="advantages__title title">Почему выбирают нас</h4>
      <div class="advantages__items">
        <div class="advantages__item">
          <div class="advantages__icon">
            <Adv01/>
          </div>
          <div class="advantages__text">Индивидуальный подход к клиентам</div>
        </div>
        <div class="advantages__item">
          <div class="advantages__icon">
            <Adv02/>
          </div>
          <div class="advantages__text">Профессиональное сопровождение сделок
          </div>
        </div>
        <div class="advantages__item">
          <div class="advantages__icon">
            <Adv03/>
          </div>
          <div class="advantages__text">Широкий выбор объектов недвижимости</div>
        </div>
        <div class="advantages__item">
          <div class="advantages__icon">
            <Adv04/>
          </div>
          <div class="advantages__text">Опыт и экспертиза специалистов</div>
        </div>
      </div>
    </div>
  </section>
  <section class="services">
    <div v-if="categories && services" data-tabs class='services__container'>
      <h2 class="services__title title"></h2>
      <div class="services__filter">
        <ul class="services__filter__wrap">
          <li v-for="(category, index) in categories">
            <button :data-filter="category.id" type="button"
                    :class="{ active: index === 0 }">{{ category.name }}
            </button>
          </li>
        </ul>
      </div>
      <div class="services__tabs">
        <div data-button-for-open-custom-popup="application" :data-service-popup="service.title"
             v-for="service in services" :data-filter-item="service.category_id" class="services__item">
          <h5 class="services__title-item">{{ service.title }}</h5>
          <div class="services__description">{{ service.description }}</div>
          <div class="services__price">{{ service.price }}₽</div>
        </div>
      </div>
    </div>
    <Preloader v-else/>
  </section>
  <section class="team">
    <div class='team__container'>
      <h3 class="team__title title">Наша команда</h3>
      <div class="team__items">
        <div class="team__item">
          <div class="team__image">
            <picture>
              <source data-srcset="/img/team/artem.webp" type='image/webp'>
              <img v-lazy="'/img/team/artem.jpg'" alt='Артём'>
            </picture>
          </div>
          <h5 class="team__title-item">Главный IT специалист</h5>
          <div class="team__text">Отвечает за работу сайта и конфиденциальность данных</div>
        </div>
        <div class="team__item">
          <div class="team__image">
            <picture>
              <source data-srcset="/img/team/djavad.webp" type='image/webp'>
              <img v-lazy="'/img/team/djavad.jpg'" alt='Джавад'>
            </picture>
          </div>
          <h5 class="team__title-item">Директор</h5>
          <div class="team__text">Управляет стратегией и операциями компании, координируя работу всех подразделений
          </div>
        </div>
        <div class="team__item">
          <div class="team__image">
            <picture>
              <source data-srcset="/img/team/vasa.webp" type='image/webp'>
              <img v-lazy="'/img/team/vasa.jpg'" alt='Вася'>
            </picture>
          </div>
          <h5 class="team__title-item"> Маркетолог</h5>
          <div class="team__text">Разрабатывает маркетинговые стратегии для привлечения клиентов и повышения
            узнаваемости бренда
          </div>
        </div>
      </div>
    </div>
  </section>

</template>

<style scoped lang="scss">
@use 'sass:math';
@import '../assets/scss/functions';


.services {

  &__container {
  }

  &__title {
  }

  &__filter__wrap {
    display: inline-flex;

    @include adaptiv-value('border-radius', 9, 6, 1);

    overflow: hidden;
    flex-wrap: wrap;
    justify-content: center;

    button {
      font-weight: 500;
      background-color: var(--white);
      @include adaptiv-value('padding-top', 13, 10, 1);
      @include adaptiv-value('padding-bottom', 13, 10, 1);
      @include adaptiv-value('padding-right', 25, 15, 1);
      @include adaptiv-value('padding-left', 25, 15, 1);
      transition: background-color 0.3s ease;

      &:focus {
        color: var(--yellow);
      }

      @media (any-hover: hover) {
        &:hover {
          color: var(--yellow);
        }
      }

      &.active {
        background-color: var(--yellow);
        color: var(--white);
        transition: all 0.3s ease;
      }
    }
  }

  &__filter {
    text-align: center;
    @include adaptiv-value('margin-bottom', 40, 20, 1);
  }

  &__tabs {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(rem(270), 1fr));
    @include adaptiv-value('gap', 20, 15, 1);
  }

  &__item {
    background-color: var(--blue);
    padding: rem(13);
    color: var(--white);
    overflow: hidden;
    @include adaptiv-value('border-radius', 12, 9, 1);
    display: flex;
    flex-direction: column;
    transition: all 0.4s ease 0s;
    cursor: pointer;

    &:focus {
      transform: scale(.85);
    }

    @media (any-hover: hover) {
      &:hover {
        transform: scale(.95);
      }
    }
  }

  &__title-item {
    font-size: rem(18);
    font-weight: 500;
    margin-bottom: em(10, 18);
  }

  &__description {
    font-size: rem(14);
    flex: 1 1 auto;
    margin-bottom: em(9, 14);
  }

  &__price {
    color: rgb(255, 228, 124);
    font-weight: 700;
  }
}


.advantages {

  &__container {
  }

  &__title {
  }

  &__items {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    @include adaptiv-value('column-gap', 30, 15, 1);
    row-gap: rem(15);
  }

  &__item {
    background-color: var(--white);
    padding: rem(35) rem(10);
    text-align: center;

    @media (min-width: $md4) {
      max-width: rem(215);
    }

    @media (max-width: $md4) {
      flex: 0 1 100%;
    }

    @include adaptiv-value('border-radius', 20, 15, 1);
  }

  &__icon {
    margin-bottom: rem(15);
    height: rem(100);

    svg {
      max-width: 100%;
      max-height: 100%;
    }
  }

  &__text {
    font-weight: 500;

    @media (max-width: $md4) {
      max-width: rem(200);
      margin: 0 auto;
    }

  }
}


.team {

  &__container {
  }

  &__title {
  }

  &__items {
    display: flex;
    flex-wrap: wrap;
    @include adaptiv-value('column-gap', 60, 20, 1);
    row-gap: rem(20);
    justify-content: center;
  }

  &__title-item {
    color: var(--blue);
    @include adaptiv-value('font-size', 20, 18, 1);
    font-weight: 700;
    margin-bottom: em(12, 20);
  }

  &__item {
    text-align: center;
    max-width: rem(303);
  }

  &__image {
    position: relative;
    overflow: hidden;
    margin: 0 auto;
    border-radius: 50%;
    margin-bottom: rem(12);
    @include adaptiv-value('width', 135, 100, 1);
    @include adaptiv-value('height', 135, 100, 1);

    img {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }

  &__text {
  }
}


.welcome {
  position: relative;
  min-height: calc(100vh - 75px);
  display: flex;
  justify-content: center;
  align-items: center;

  img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  &::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.41);
  }

  &__container {
    position: relative;
    color: var(--white);
    z-index: 2;
    text-align: center;
    @include adaptiv-value('padding-top', 100, 40, 1);
    @include adaptiv-value('padding-bottom', 100, 40, 1);
  }

  &__title {
    max-width: rem(908);
    margin: 0 auto;
    @include adaptiv-value('font-size', 60, 40, 1);
    margin-bottom: em(30, 60);
  }

  &__text {
    max-width: rem(700);
    margin: 0 auto;
    line-height: 140%;
    @include adaptiv-value('margin-bottom', 40, 20, 1);
    @include adaptiv-value('font-size', 22, 18, 1);
  }

  &__button {
  }
}
</style>
