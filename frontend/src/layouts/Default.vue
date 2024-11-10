<script setup lang="ts">

import {RouterLink, RouterView} from "vue-router";
import {onMounted, ref} from "vue";
import {useMeta} from "vue-meta";
import axios from "axios";
import Header from "@/components/Header.vue";
import Footer from "@/components/Footer.vue";

useMeta({
  title: 'Коммерсант',
  description: '555',
  meta: [{ name: 'keywords', content: '45,45' }]
})

const errors = ref<any>({});

const name = ref('');
const tel = ref('');
const message = ref('');
const service = ref('');

async function submitApplication() {
  const formData = new FormData();

  //ПОМЕНЯТЬ РЕАКТИВНЫЕ НА ФОРМДАТУ, ЗАДАТЬ ВСЕМ ИНПУТАМ name
  const form = document.forms.namedItem('application') as HTMLFormElement | null;
  if (form) {
    const formData2 = new FormData(form);
    const formDataArray = Array.from(formData2.entries()).map(([key, value]) => {
      return { key, value }; // Создаем объект с ключом и значением
    });
    // Выводим массив
    console.log(formDataArray);
  }



  formData.append('name', name.value);
  formData.append('telephone', tel.value);
  formData.append('comment', message.value);
  service.value ? formData.append('service', message.value) : null;

  //console.log(csrfToken)
  //formData.append('_token', csrfToken);
  try {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = await axios.get('/csrf-token').then(res => res.data);

    const response = await axios.post('/application/store', formData)
      .catch(error => {
        if (error.response.status === 422) {
          errors.value = error.response.data.errors;
          console.log(errors.value)
        }
      });
    //console.log('Успех:', response.data);
  } catch (error) {
    console.error('Ошибка:', error);
  }

}

</script>

<template>
  <Header/>
  <main class="page">
    <RouterView />
    <section class="window-application" data-custom-popup="application">
      <div class="window-application__body">
        <div class="window-application__content" data-custom-popup-content>
          <div class="window-application__top">
            <div class="window-application__connect">
              <a href="tel:79224847440" class="window-application__tel">+7 (922) 484-74-40</a>
              <div class="window-application__connect-tel">или Вы можете сами позвонить</div>
            </div>
            <button type="button" class="window-application__close" data-close-for-custom-popup>
              <img src='/img/icons/close.svg' alt='close'>
            </button>
          </div>
          <h2 class="window-application__title title">Отправьте заявку и мы свяжемся с вами в ближайшее время</h2>
          <form name="application" @submit.prevent="submitApplication" class="window-application__form form">
            <div class="form__item">
              <input v-model="service" id="serviceText" type="text" class="input input_readonly input_none"
                readonly />
            </div>
            <div class="form__item">
              <label for="name" class="label">Как вас зовут?<span class="necessarily">*</span></label>
              <input name="name"
                :class="errors.name ? 'error' : null"
                v-model="name" id="name" type="text" placeholder="Имя" class="input" />
              <div v-if="errors.name" class="form__error">{{ errors.name[0] }}</div>
            </div>
            <div class="form__item">
              <label for="tel" class="label">Ваш номер телефона для связи<span class="necessarily">*</span></label>
              <input
                :class="errors.telephone ? 'error' : null"
                v-model="tel" id="tel" type="tel" placeholder="+7" class="input" />
              <div v-if="errors.telephone" class="form__error">{{ errors.telephone[0] }}</div>
            </div>
            <div class="form__item">
              <label for="message" class="label">Комментарий</label>
              <textarea
                :class="errors.comment ? 'error' : null"
                v-model="message" id="message" class="textarea"></textarea>
              <div v-if="errors.comment" class="form__error">{{ errors.comment[0] }}</div>
            </div>
            <button class="button-border" type="submit">Отправить</button>
          </form>
          <div class="window-application__privacy">
            Нажимая на кнопку, вы даете свое <RouterLink to="">согласие на обработку персональных данных</RouterLink>
          </div>
        </div>
      </div>
    </section>
  </main>
  <Footer/>
</template>

<style scoped lang="scss">
@use 'sass:math';
@import '../assets/scss/functions';

.window-application {
  &.open {
    z-index: 700;
    overflow-y: auto;

    .window-application__body {
      opacity: 1;
    }

    .window-application__content {
      transform: scale(1);
    }
  }

  position: fixed;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  z-index: -10;
  overflow: hidden;

  &__body {
    opacity: 0;
    -webkit-transition: opacity .6s ease;
    transition: opacity .6s ease;
    background: rgba(27, 27, 27, .6);
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    -ms-flex-align: center;

    align-items: center;
    padding: 3.75rem;
    //padding-top: 3.75rem !important;
    min-height: 100%;

    @media (max-width: 1920px) {
      padding: calc(10px + 30 * (100vw - 320px)/ 1600);
    }
  }

  &__content {

    @include adaptiv-value('border-radius', 18, 12, 1);
    box-shadow: 0px 4px 108px 0px rgba(53, 77, 175, 0.6);

    max-width: rem(740);
    background-color: var(--white);
    transition: transform 0.6s ease 0s;
    transform: scale(0.45);
    padding-top: rem(15);
    @include adaptiv-value('padding-bottom', 20, 15, 1);
    @include adaptiv-value('padding-left', 30, 25, 1);
    @include adaptiv-value('padding-right', 30, 25, 1);
    position: relative;
    box-shadow: -14px -27px 25.3px 0px rgba(0, 0, 0, 0.25) inset;
  }

  &__top {
    display: flex;
    justify-content: space-between;
    column-gap: rem(15);
    @include adaptiv-value('margin-bottom', 20, 10, 1);
  }

  &__connect {
    @include adaptiv-value('padding-top', 30, 10, 1);
  }

  &__tel {
    color: var(--yellow);
    @include adaptiv-value('font-size', 25, 20, 1);
    font-weight: 700;
    margin-bottom: em(10, 25);
    transition: color 0.3s ease;

    &:focus {
      color: var(--yellow-hover2);
    }

    @media (any-hover: hover) {
      &:hover {
        color: var(--yellow-hover2);
      }
    }
  }

  &__connect-tel {
    text-align: left;
    font-size: rem(12);
    font-weight: 500;
    color: var(--blue);
  }

  &__close {
    align-self: flex-start;
  }

  .button-border {
    display: block;
    width: 100%;
  }

  &__title {}

  &__form {
    margin-bottom: rem(15);
  }

  &__privacy {
    font-size: rem(12);
    color: var(--grey);
    text-align: right;
    a {
      transition: color 0.3s ease;
      &:focus {
          color: var(--yellow);
      }
      @media (any-hover: hover) {
          &:hover{
              color: var(--yellow);
          }
      }
    }
  }

  &__close {
    transition: transform 0.3s ease;

    &:focus {
      transform: scale(1.2);
    }

    @media (any-hover: hover) {
      &:hover {
        transform: scale(1.1);
      }
    }

    img {
      width: rem(25);
    }
  }
}







</style>
