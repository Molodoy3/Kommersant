<script setup lang="ts">

import { ref, watchEffect, nextTick } from "vue";
import Adv01 from "@/components/icons/Adv-01.vue";
import Adv02 from "@/components/icons/Adv-02.vue";
import Adv03 from "@/components/icons/Adv-03.vue";
import Adv04 from "@/components/icons/Adv-04.vue";
import axios from "axios";
import Preloader from "@/components/Preloader.vue";
import { useMeta } from "vue-meta";

useMeta({
  title: 'Агентство недвижимости Коммерсант',
  description: 'Коммерсант" в Пермском крае предлагает полный спектр услуг по купле-продаже объектов недвижимости.' +
    ' На нашем сайте вы найдете свежие новости рынка недвижимости региона,' +
    ' подробное описание наших услуг и сможете легко оставить заявку на понравившийся объект.',
})

let categories = ref<any | null>(null)
axios.get('/categories', { withCredentials: true })
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

let properties = ref<any | null>(null)
axios.get('/properties-recent')
  .then(res => {
    properties.value = res.data
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
        const activeFilter = tab.querySelector('.active') as HTMLElement;
        if (activeFilter) {
          const filterValue = activeFilter.dataset.filter;
          if (filterValue != '*') {
            tab.querySelectorAll<HTMLElement>('[data-filter-item]').forEach(filterItem => {
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
  <section class="welcome welcome__image">
    <picture>
      <source srcset="/img/welcome/bg.webp" type="image/webp">
      <source srcset="/img/welcome/bg.jpeg" type="image/jpeg">
      <img v-lazy="'/img/welcome/bg.webp'" alt="фон">
    </picture>
    <div class='welcome__container'>
      <h1 class="welcome__title title">Агенство недвижимости
        «Коммерсант»</h1>
      <div class="welcome__text">надежное агентство недвижимости, предлагающее услуги по покупке, продаже и аренде в
        Пермском крае
      </div>
      <a href="#properties" class="welcome__button button">Смотреть объекты</a>
    </div>
  </section>
  <section id="services" class="services">
    <div data-tabs class='services__container'>
      <h2 class="services__title title">Наши услуги</h2>
      <div v-if="categories && services" class="services__body">
        <div class="services__filter">
          <ul class="services__filter__wrap">
            <li v-for="(category, index) in categories">
              <button :data-filter="category.id" type="button" :class="{ active: index === 0 }">{{ category.name }}
              </button>
            </li>
          </ul>
        </div>
        <div class="services__tabs">
          <div data-button-for-open-custom-popup="application" :data-service-popup="service.title"
            :data-service-id-popup="service.id" v-for="service in services" :data-filter-item="service.category_id"
            class="services__item">
            <h5 class="services__title-item">{{ service.title }}</h5>
            <div class="services__description">{{ service.description }}</div>
            <div class="services__price">{{ service.price }}</div>
          </div>
        </div>
      </div>
      <Preloader v-else />
    </div>
  </section>
  <section id="properties" class="properties">
    <div class='properties__container'>
      <h2 class="properties__title title">Объекты в продаже</h2>
      <div v-if="properties" class="properties__items">
        <RouterLink :to="{ name: 'property', params: { id: property.id } }" v-for="property in properties"
          class="properties__item">
          <div class="properties__image">
            <picture>
              <source :srcset='property.image + ".webp"' :type='"image/webp"'>
              <source :srcset='property.image + "." + property.image_extension'
                :type='"image/" + property.image_extension'>
              <img v-lazy='property.image + ".webp"' alt='объект недвижимости'>
            </picture>
            <div class="properties__labels">
              <div :style="'background-color: #' + label.color" v-for="label in property.labels"
                class="properties__label">{{ label.name }}</div>
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
      <Preloader v-else />
    </div>
  </section>
  <section class="advantages">
    <div class='advantages__container'>
      <h4 class="advantages__title title">Почему выбирают нас</h4>
      <div class="advantages__items">
        <div class="advantages__item">
          <div class="advantages__icon">
            <Adv01 />
          </div>
          <div class="advantages__text">Индивидуальный подход к клиентам</div>
        </div>
        <div class="advantages__item">
          <div class="advantages__icon">
            <Adv02 />
          </div>
          <div class="advantages__text">Профессиональное сопровождение сделок
          </div>
        </div>
        <div class="advantages__item">
          <div class="advantages__icon">
            <Adv03 />
          </div>
          <div class="advantages__text">Широкий выбор объектов недвижимости</div>
        </div>
        <div class="advantages__item">
          <div class="advantages__icon">
            <Adv04 />
          </div>
          <div class="advantages__text">Опыт и экспертиза специалистов</div>
        </div>
      </div>
    </div>
  </section>
  <section class="team">
    <div class='team__container'>
      <h3 class="team__title title">Наша команда</h3>
      <div class="team__items">
        <div class="team__item">
          <div class="team__image">
            <picture>
              <source srcset="/img/team/artem.webp" type='image/webp'>
              <source srcset="/img/team/artem.jpg" type='image/jpg'>
              <img v-lazy="'/img/team/artem.webp'" alt='Артём'>
            </picture>
          </div>
          <h5 class="team__title-item">Главный IT специалист</h5>
          <div class="team__text">Отвечает за работу сайта и конфиденциальность данных</div>
        </div>
        <div class="team__item">
          <div class="team__image">
            <picture>
              <source srcset="/img/team/djavad.webp" type='image/webp'>
              <source srcset="/img/team/djavad.jpg" type='image/jpg'>
              <img v-lazy="'/img/team/djavad.webp'" alt='Артём'>
            </picture>
          </div>
          <h5 class="team__title-item">Директор</h5>
          <div class="team__text">Управляет стратегией и операциями компании, координируя работу всех подразделений
          </div>
        </div>
        <div class="team__item">
          <div class="team__image">
            <picture>
              <source srcset="/img/team/vasa.webp" type='image/webp'>
              <source srcset="/img/team/vasa.jpg" type='image/jpg'>
              <img v-lazy="'/img/team/vasa.webp'" alt='Артём'>
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


.properties {

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
      &:hover {
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

  &__title-item {
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

  &__content {
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


.services {

  &__container {}

  &__title {}

  &__filter__wrap {
    display: inline-flex;

    @include adaptiv-value('border-radius', 9, 6, 1);

    overflow: hidden;
    flex-wrap: wrap;
    justify-content: center;

    li {
      flex: 1 1 auto;
      @media (min-width: $md4){
        &:not(:last-child) {
          border-right: rem(1) solid #cca91d50;
      }
      }

      @media (max-width: $md4) {
        width: 100%;
        flex: 1 1 auto;
      }
    }

    button {
      font-weight: 500;
      width: 100%;
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

  &__container {}

  &__title {}

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

  &__container {}

  &__title {}

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

  &__text {}
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

  &__button {}
}
</style>
