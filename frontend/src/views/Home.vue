<script setup lang="ts">

//import axios from "axios";
import { ref } from "vue";
import Adv01 from "@/components/icons/Adv-01.vue";
import Adv02 from "@/components/icons/Adv-02.vue";
import Adv03 from "@/components/icons/Adv-03.vue";
import Adv04 from "@/components/icons/Adv-04.vue";

let users = ref()

/*axios
  .get('/users')
  .then((res) => {
    users.value = res.data.data;
  })
  .catch((error) => {
    console.log(error)
  })*/

</script>

<template>
  <section class="welcome">
    <picture>
      <source v-lazy='"/img/welcome/bg.webp"' type='image/webp'>
      <img v-lazy='"/img/welcome/bg.jpeg"' alt='фон'>
    </picture>
    <div class='welcome__container'>
      <h1 class="welcome__title title">Агенство недвижимости
        «Коммернант»</h1>
      <div class="welcome__text">надежное агентство недвижимости, предлагающее услуги по покупке, продаже и аренде в
        Пермском крае</div>
      <a href="#objects" class="welcome__button button">Смотреть объекты</a>
    </div>
  </section>
  <section id="objects" class="objects">

  </section>
  <section class="team">
    <div class='team__container'>
      <h3 class="team__title title">Наша команда</h3>
      <div class="team__items">
        <div class="team__item">
          <div class="team__image">
            <picture>
              <source v-lazy="'/img/team/artem.webp'" type='image/webp'>
              <img v-lazy="'/img/team/artem.jpg'" alt='Артём'>
            </picture>
          </div>
          <h5 class="team__title-item">Главный IT специалист</h5>
          <div class="team__text">Отвечает за обеспечивая работу сайта и конфиденциальность</div>
        </div>
        <div class="team__item">
          <div class="team__image">
            <picture>
              <source v-lazy="'/img/team/djavad.webp'" type='image/webp'>
              <img v-lazy="'/img/team/djavad.jpg'" alt='Артём'>
            </picture>
          </div>
          <h5 class="team__title-item">Директор</h5>
          <div class="team__text">Управляет стратегией и операциями компании, координируя работу всех подразделений
          </div>
        </div>
        <div class="team__item">
          <div class="team__image">
            <picture>
              <source v-lazy="'/img/team/vasa.webp'" type='image/webp'>
              <img v-lazy="'/img/team/vasa.jpg'" alt='Артём'>
            </picture>
          </div>
          <h5 class="team__title-item"> Маркетолог</h5>
          <div class="team__text">Разрабатывает маркетинговые стратегии для привлечения клиентов и повышения
            узнаваемости бренда</div>
        </div>
      </div>
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
</template>

<style scoped lang="scss">
@use 'sass:math';
@import '../assets/scss/functions';

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
    @media (min-width: $md4){
        max-width: rem(215);
    }
    @media (max-width: $md4){
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
    @media (max-width: $md4){
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
