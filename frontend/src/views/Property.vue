<script setup lang="ts">

import axios from "axios";
import { useRoute } from "vue-router";
import { nextTick, onMounted, ref, watchEffect } from "vue";
import Preloader from "@/components/Preloader.vue";
import { Swiper } from 'swiper';
import { Navigation, Pagination, Keyboard, Mousewheel, Thumbs } from 'swiper/modules';
import Arrow from "@/components/icons/Arrow.vue";

const idProperty = useRoute().params.id;
const property = ref<any | null>(null);

axios.get('property/' + idProperty)
  .then(res => {
    property.value = res.data
  }).catch(error => {
    console.log(error)
  })

/*onMounted(() => {
   const script = document.createElement('script');
  script.src = "https://api-maps.yandex.ru/v3/?apikey=435f424d-08d3-4002-ab0b-81fd922114ec&lang=ru_RU";
  script.async = true;
  script.onload = () => {
    this.initMap();
  };
  document.head.appendChild(script);

  initMap();
  async function initMap() {
    await ymaps3.ready;
    const { YMap, YMapDefaultSchemeLayer } = ymaps3;
    const map = new YMap(
      document.getElementById('map'),
      {
        location: { center: [37.588144, 55.733842], zoom: 10 }
      }
    );
    map.addChild(new YMapDefaultSchemeLayer());
  }
});*/

interface Label {
  id: number;
}

watchEffect(async () => {
  if (property.value) {

    // Ждем обновления DOM
    await nextTick();
    let swp;
    const sliderThumbs = document.querySelector<HTMLElement>('.slider-thumbs');
    if (sliderThumbs) {
      swp = new Swiper(sliderThumbs, {
        modules: [Navigation, Pagination, Mousewheel],
        wrapperClass: 'slider-thumbs__wrapper',
        slideClass: 'slider-thumbs__slide',
        direction: 'horizontal',
        slidesPerView: 4,
        loop: false,
        speed: 800,
        spaceBetween: 0,
        observer: true,
        observeParents: true,
        observeSlideChildren: true,
        mousewheel: {
          invert: true,
        },
      });
    }
    const slider = document.querySelector<HTMLElement>('.slider');
    if (slider) {
      new Swiper(slider, {
        modules: [Navigation, Pagination, Keyboard, Mousewheel, Thumbs],
        wrapperClass: 'slider__wrapper',
        slideClass: 'slider__slide',
        navigation: {
          prevEl: '.slider__prev',
          nextEl: '.slider__next',
        },
        direction: 'horizontal',
        slidesPerView: 1,
        loop: false,
        speed: 800,
        spaceBetween: 0,
        observer: true,
        observeParents: true,
        observeSlideChildren: true,
        mousewheel: {
          invert: true,
        },
        keyboard: {
          enabled: true,
        },
        thumbs: {
          swiper:swp
        },
      });
    }
    //const sliderThumbs = document.querySelector<HTMLElement>('.slider-thumbs');

  }
});

</script>

<template>
  <section class="property">
    <div v-if="property" class='property__container'>
      <div class="property__body">
        <div class="property__imgs">
          <div class="slider">
            <div class="slider__wrapper">
              <div v-for="image in property.images" class="slider__slide slider__image">
                <picture>
                  <source :srcset='image.path + "." + image.extension' :type='"image/" + image.extension'>
                  <img v-lazy='image.path + ".webp"' alt='объект недвижимости'>
                </picture>
              </div>
            </div>
            <div class="slider__arrows">
              <button type="button" class="slider__arrow slider__prev"><Arrow/></button>
              <button type="button" class="slider__arrow slider__next"><Arrow/></button>
            </div>
          </div>
          <!--          слайдер для превьюшек-->
          <div class="slider-thumbs">
            <div class="slider-thumbs__wrapper">
              <div v-for="image in property.images" class="slider-thumbs__slide slider-thumbs__image">
                <picture>
                  <source :srcset='image.path + "." + image.extension' :type='"image/" + image.extension'>
                  <img v-lazy='image.path + ".webp"' alt='объект недвижимости'>
                </picture>
              </div>
            </div>
          </div>
        </div>
        <div class="property__content">
          <div class="properties__labels">
            <div :style="'background-color: #' + label.color" v-for="label in property.labels"
              class="properties__label">{{ label.name }}</div>
          </div>
          <h1 class="property__title title">{{ property.name }}</h1>
          <ul class="properties__parameters">
            <li class="rent" v-if="property.transaction_type_id === 1">— {{ property.transaction_type.name }}</li>
            <li>— {{ property.type.name }}</li>
            <li>— {{ property.address }}</li>
            <li>— {{ property.square }}м²</li>
          </ul>
          <a :href="property.link" class="property__link">Ссылка на объявление</a>
          <div class="property__price">{{ property.prise }}</div>
          <button data-button-for-open-custom-popup="application" :data-service-popup="property.name"
                  :data-property-id-popup="property.id"
                  :data-is-bargaining-popup="property.labels.some((label: Label) => label.id == 1)" type="button"
            class="property__button button">Отправить заявку
          </button>
        </div>
      </div>
      <div class="property__description">
        <h2 class="property__sub-title">Описание</h2>
        {{ property.description }}
      </div>
      <div class="property__geo">
        <h2 class="property__sub-title">Расположение на карте</h2>
      </div>
    </div>
    <Preloader v-else />
    <div v-if="property" class="property__map" style="position:relative;overflow:hidden;">
      <iframe
        :src="'https://yandex.ru/map-widget/v1/?indoorLevel=1&ll=' + property.latitude + '%2C' + property.longitude + '&mode=whatshere&whatshere%5Bpoint%5D=' + property.longitude + '%2C' + property.latitude + '&whatshere%5Bzoom%5D=17&z=17.26'"
        frameborder="1" allowfullscreen="true" style="position:relative;">
      </iframe>
    </div>
  </section>
</template>

<style scoped lang="scss">
@use 'sass:math';
@import '../assets/scss/functions';

.properties {
  &__labels {
    display: flex;
    flex-wrap: wrap;

    @media (min-width: $md3) {

      justify-content: flex-end;
    }

    justify-content: center;
    gap: rem(5);
  }

  &__label {
    padding: rem(6) rem(15);
    color: var(--white);
    text-transform: uppercase;
    font-weight: 600;
    font-size: rem(10);
    background-color: var(--yellow);
    border-radius: rem(6);
  }
}

.slider {
  @include adaptiv-value('border-radius', 14, 10, 1);
  overflow: hidden;
  width: 100%;
  position: relative;
  @include adaptiv-value('margin-bottom', 25, 15, 1);
  &__wrapper {
    display: flex;
    @include adaptiv-value('height', 325, 250, 1);
  }

  &__slide {
    flex-shrink: 0;
    background-color: #c01010;
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

  &__arrows {

  }

  &__arrow {
    @include adaptiv-value('padding', 10, 7, 1);
    background-color: var(--white);
    top: 50%;
    position: absolute;
    transform: translate(0, -50%);
    display: flex;
    justify-content: center;
    align-items: center;
    transition: background-color 0.3s ease, transform .3s ease;
    &:focus {
      background-color: #f0f0f0;
      transform: scale(1.05) translate(0, -50%);
    }
    @media (any-hover: hover) {
        &:hover{
            background-color: #f0f0f0;
            transform: scale(1.05) translate(0, -50%);
        }
    }
  }

  &__prev {
    border-radius: rem(0) rem(7) rem(7) rem(0);
    left: rem(-1);
    svg{

      transform: rotate(180deg);
    }
  }

  &__next {
    border-radius: rem(7) rem(0) rem(0) rem(7);
    right: rem(-1);
  }
}

.slider-thumbs {
  overflow: hidden;

  &__wrapper {
    display: flex;
    column-gap: rem(20);
  }

  &__slide {
    cursor: pointer;
    flex-shrink: 0;
    position: relative;
    @include adaptiv-value('height', 75, 60, 1);
    img {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    &.swiper-slide-thumb-active{
      border: rem(2) solid var(--yellow);
    }
  }
}

.property {
  @include adaptiv-value('padding-top', 50, 20, 1);
  margin-bottom: 0;
  &__price{
    @include adaptiv-value('font-size', 28, 20, 1);
    font-weight: 700;
    margin-bottom: .5em;
  }
  &__container {}

  &__body {
    display: flex;
    gap: rem(30);
    flex-wrap: wrap;

    @media (max-width: $md3) {
      flex-direction: row-reverse;
    }

    @include adaptiv-value('margin-bottom', 45, 25, 1);
  }

  &__imgs {
    @media (min-width: $md3) {
      flex: 0 0 45%;
    }

    overflow: hidden;

    @media (max-width: $md3) {
      flex: 0 1 100%;
      margin-bottom: rem(25);
    }
  }

  &__content {
    text-align: right;
    flex: 1 1 auto;

    @media (max-width: $md3) {
      text-align: center;
    }
  }

  &__title {
    margin-bottom: em(16, 40);
    text-align: right;

    @media (max-width: $md3) {
      text-align: center;
    }
  }

  .properties__parameters {
    @include adaptiv-value('margin-bottom', 20, 12, 1);
    color: var(--grey);
    margin-left: auto;
    max-width: rem(300);
    li {

      &:not(:last-child) {
        margin-bottom: rem(7);
      }

    }
  }

  &__link {
    color: var(--blue);
    transition: color 0.3s ease;
    font-weight: 500;
    font-size: rem(14);
    display: inline-block;
    @include adaptiv-value('margin-bottom', 20, 12, 1);

    &:focus {
      color: #1f2e69;
    }

    @media (any-hover: hover) {
      &:hover {
        color: #1f2e69;
      }
    }
  }

  &__button {}

  &__description {
    p {
      &:not(:last-child) {
        margin-bottom: em(16, 16);
      }
    }

    @include adaptiv-value('margin-bottom', 35, 20, 1);
  }

  &__sub-title {
    @include adaptiv-value('font-size', 30, 20, 1);
    font-weight: 700;
    margin-bottom: em(30, 30);

  }

  &__map {
    width: 100%;
    @include adaptiv-value('height', 350, 250, 1);

    iframe {
      width: 100%;
      height: 100%;
    }
  }
}
</style>
