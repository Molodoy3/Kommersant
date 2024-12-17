<script setup lang="ts">

import { RouterLink, RouterView } from "vue-router";
import { onMounted, ref } from "vue";
import { useMeta } from "vue-meta";
import axios from "axios";
import Header from "@/components/Header.vue";
import Footer from "@/components/Footer.vue";
import Tick from "@/components/icons/Tick.vue";

useMeta({
  title: 'Коммерсант',
  description: '555',
  meta: [{ name: 'keywords', content: '45,45' }]
})

const errors = ref<any>({});

//для телефонной маски
onMounted(() => {
  const inputs = document.querySelectorAll<HTMLInputElement>("[data-mask-tel]");
  if (inputs.length) {
    inputs.forEach(input => {
      input.value = "+7 ";

      input.addEventListener("input", mask, false);
      input.addEventListener("focus", mask, false);
      input.addEventListener("blur", mask, false);
      input.addEventListener("keydown", handleKeydown, false);
    });
  }

  function mask(this: HTMLInputElement, event: Event): void {
    const pos = this.selectionStart || 0;
    if (pos < 4) event.preventDefault();

    const matrix = "+7 (___) ___-__-__";
    let i = 0;
    const val = this.value.replace(/\D/g, "");

    let new_value = matrix.replace(/[_\d]/g, (a) => {
      return i < val.length ? val.charAt(i++) : a;
    });

    i = new_value.indexOf("_");
    if (i !== -1) {
      i < 5 && (i = 4);
      new_value = new_value.slice(0, i);
    }

    // Объявляем reg и присваиваем значение в одном выражении
    let reg: RegExp = new RegExp(matrix.substr(0, this.value.length).replace(/_+/g, (a) => {
      return "\\d{1," + a.length + "}";
    }).replace(/[+()]/g, "\\$&"));

    if (!reg.test(this.value) || this.value.length < 6) {
      this.value = new_value;
    }

    if (event.type === "blur" && this.value.length < 6) {
      this.value = "+7 ";
    }
  }

  function handleKeydown(this: HTMLInputElement, event: KeyboardEvent): void {
    if (event.key.length === 1 && !/\d/.test(event.key)) {
      event.preventDefault();
    }
  }
});

const isLoading = ref<Boolean>(false);
async function submitApplication() {
  const form = document.forms.namedItem('application') as HTMLFormElement | null;
  if (form) {
    const formData = new FormData(form);
    //axios.defaults.headers.common['X-CSRF-TOKEN'] = await axios.get('/csrf-token').then(res => res.data);

    let loadingTimeout = setTimeout(() => {
      isLoading.value = true;
    }, 100);

    await axios.post('/application/store', formData)
      .then(() => {
        errors.value = {};
        form.reset();

        const sentElement = document.querySelector<HTMLElement>('.window-application__sent')
        sentElement ? sentElement.classList.add('window-application__sent_active') : null;
      })
      .catch(error => {
        if (error.response.status === 422) {
          errors.value = error.response.data.errors;
        }
      }).finally(() => {
        clearTimeout(loadingTimeout);
        isLoading.value = false;
      });
    //console.log('Успех:', response.data);

    /*const formDataArray = Array.from(formData.entries()).map(([key, value]) => {
      return { key, value }; // Создаем объект с ключом и значением
    });
    // Выводим массив
    console.log(formDataArray);*/
  }
}

</script>

<template>
  <Header />
  <main class="page">
    <RouterView />
    <section class="window-application" data-custom-popup="application">
      <div class="window-application__body">
        <div class="window-application__content" :class="{ 'window-application__content_load': isLoading }" data-custom-popup-content>
          <div class="window-application__sent">
            <div class="window-application__tick">
              <Tick/>
            </div>
            Ваше сообщение отправлено!
          </div>
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
            <div class="form__item form__item_none">
              <input name="service" id="serviceText" type="text" class="input input_readonly" readonly />
              <input name="service_id" id="serviceId" type="hidden" readonly />
              <input name="property_id" id="propertyId" type="hidden" readonly />
            </div>
            <div class="form__item form__item_none">
              <label for="name" class="label">Преложите свою цену в рублях (необязательно)</label>
              <input name="user_price" id="bargainingText" type="text" class="input" />
              <div v-if="errors.user_price" class="form__error">{{ errors.user_price[0] }}</div>
            </div>
            <div class="form__item">
              <label for="name" class="label">Как вас зовут?<span class="necessarily">*</span></label>
              <input name="name" :class="errors.name ? 'error' : null" id="name" type="text" placeholder="Имя"
                class="input" />
              <div v-if="errors.name" class="form__error">{{ errors.name[0] }}</div>
            </div>
            <div class="form__item">
              <label for="tel" class="label">Ваш номер телефона для связи<span class="necessarily">*</span></label>
              <input data-mask-tel :class="errors.telephone ? 'error' : null" name="telephone" id="tel" type="tel"
                placeholder="+7" class="input" />
              <div v-if="errors.telephone" class="form__error">{{ errors.telephone[0] }}</div>
            </div>
            <div class="form__item">
              <label for="message" class="label">Комментарий<span class="necessarily">*</span></label>
              <textarea :class="errors.comment ? 'error' : null" name="comment" id="message"
                class="textarea"></textarea>
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
  <Footer />
</template>

<style scoped lang="scss">
@use 'sass:math';
@import '../assets/scss/functions';

.window-application {
  &__sent{
    display: none;
    &_active{
      display: flex;
    }
    font-weight: 700;
    @include adaptiv-value('font-size', 40, 20, 1);
    text-align: center;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #fff;

    justify-content: center;
    align-items: center;
    flex-direction: column;
  }
  &__tick{
    border-radius: 50%;
    @include adaptiv-value('width', 100, 50, 1);
    @include adaptiv-value('height', 100, 50, 1);
    background-color: green;
    display: flex;
    justify-content: center;
    @include adaptiv-value('margin-bottom', 25, 15, 1);
    align-items: center;
    margin: 0 auto;
    overflow: hidden;
    @include adaptiv-value('padding', 20, 10, 1);
  }
  &.open {

    overflow-y: auto;
    visibility: visible;
    .window-application__body {
      opacity: 1;
    }

    .window-application__content {
      transform: scale(1);
    }
  }
  z-index: 700;
  visibility: hidden;
  position: fixed;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  //z-index: -10;
  overflow: hidden;

  &__body {
    opacity: 0;
    -webkit-transition: opacity .6s ease;
    transition: all .6s ease;
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

  @keyframes mulShdSpin {

    0%,
    100% {
      box-shadow: 0em -2.6em 0em 0em var(--yellow),
        1.8em -1.8em 0 0em rgba(173, 173, 173, 0.2),
        2.5em 0em 0 0em rgba(173, 173, 173, 0.2),
        1.75em 1.75em 0 0em rgba(173, 173, 173, 0.2),
        0em 2.5em 0 0em rgba(173, 173, 173, 0.2),
        -1.8em 1.8em 0 0em rgba(173, 173, 173, 0.2),
        -2.6em 0em 0 0em #e0dcca,
        -1.8em -1.8em 0 0em #cebe81;
    }

    12.5% {
      box-shadow: 0em -2.6em 0em 0em #cebe81,
        1.8em -1.8em 0 0em var(--yellow),
        2.5em 0em 0 0em rgba(173, 173, 173, 0.2),
        1.75em 1.75em 0 0em rgba(173, 173, 173, 0.2),
        0em 2.5em 0 0em rgba(173, 173, 173, 0.2),
        -1.8em 1.8em 0 0em rgba(173, 173, 173, 0.2),
        -2.6em 0em 0 0em rgba(173, 173, 173, 0.2),
        -1.8em -1.8em 0 0em #e0dcca;
    }

    25% {
      box-shadow: 0em -2.6em 0em 0em #e0dcca,
        1.8em -1.8em 0 0em #cebe81,
        2.5em 0em 0 0em var(--yellow),
        1.75em 1.75em 0 0em rgba(173, 173, 173, 0.2),
        0em 2.5em 0 0em rgba(173, 173, 173, 0.2),
        -1.8em 1.8em 0 0em rgba(173, 173, 173, 0.2),
        -2.6em 0em 0 0em rgba(173, 173, 173, 0.2),
        -1.8em -1.8em 0 0em rgba(173, 173, 173, 0.2);
    }

    37.5% {
      box-shadow: 0em -2.6em 0em 0em rgba(173, 173, 173, 0.2),
        1.8em -1.8em 0 0em #e0dcca,
        2.5em 0em 0 0em #cebe81,
        1.75em 1.75em 0 0em var(--yellow),
        0em 2.5em 0 0em rgba(173, 173, 173, 0.2),
        -1.8em 1.8em 0 0em rgba(173, 173, 173, 0.2),
        -2.6em 0em 0 0em rgba(173, 173, 173, 0.2),
        -1.8em -1.8em 0 0em rgba(173, 173, 173, 0.2);
    }

    50% {
      box-shadow: 0em -2.6em 0em 0em rgba(173, 173, 173, 0.2),
        1.8em -1.8em 0 0em rgba(173, 173, 173, 0.2),
        2.5em 0em 0 0em #e0dcca,
        1.75em 1.75em 0 0em #cebe81,
        0em 2.5em 0 0em var(--yellow),
        -1.8em 1.8em 0 0em rgba(173, 173, 173, 0.2),
        -2.6em 0em 0 0em rgba(173, 173, 173, 0.2),
        -1.8em -1.8em 0 0em rgba(173, 173, 173, 0.2);
    }

    62.5% {
      box-shadow: 0em -2.6em 0em 0em rgba(173, 173, 173, 0.2),
        1.8em -1.8em 0 0em rgba(173, 173, 173, 0.2),
        2.5em 0em 0 0em rgba(173, 173, 173, 0.2),
        1.75em 1.75em 0 0em #e0dcca,
        0em 2.5em 0 0em #cebe81,
        -1.8em 1.8em 0 0em var(--yellow),
        -2.6em 0em 0 0em rgba(173, 173, 173, 0.2),
        -1.8em -1.8em 0 0em rgba(173, 173, 173, 0.2);
    }

    75% {
      box-shadow: 0em -2.6em 0em 0em rgba(173, 173, 173, 0.2),
        1.8em -1.8em 0 0em rgba(173, 173, 173, 0.2),
        2.5em 0em 0 0em rgba(173, 173, 173, 0.2),
        1.75em 1.75em 0 0em rgba(173, 173, 173, 0.2),
        0em 2.5em 0 0em #e0dcca,
        -1.8em 1.8em 0 0em #cebe81,
        -2.6em 0em 0 0em var(--yellow),
        -1.8em -1.8em 0 0em rgba(173, 173, 173, 0.2);
    }

    87.5% {
      box-shadow: 0em -2.6em 0em 0em rgba(173, 173, 173, 0.2),
        1.8em -1.8em 0 0em rgba(173, 173, 173, 0.2),
        2.5em 0em 0 0em rgba(173, 173, 173, 0.2),
        1.75em 1.75em 0 0em rgba(173, 173, 173, 0.2),
        0em 2.5em 0 0em rgba(173, 173, 173, 0.2),
        -1.8em 1.8em 0 0em #e0dcca,
        -2.6em 0em 0 0em #cebe81,
        -1.8em -1.8em 0 0em var(--yellow);
    }
  }


  &__content {
    overflow: hidden;

    &_load {
      &::after {
        background-color: rgba(255, 255, 255, 0.432);
        content: '';
        position: absolute;
        backdrop-filter: blur(3px);
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        transition: all 0.3s ease 0.1s;
      }

      &::before {
        content: '';
        font-size: 10px;
        width: 1em;
        height: 1em;
        display: block;
        position: absolute;
        top: 50%;
        left: 50%;
        z-index: 4;
        border-radius: 50%;
        text-indent: -9999em;
        animation: mulShdSpin 1.1s infinite ease .1s;
        transform: translateZ(0) translate(-50%, -50%);
      }
    }

    @include adaptiv-value('border-radius', 18, 12, 1);
    box-shadow: 0px 4px 108px 0px rgba(53, 77, 175, 0.6);

    max-width: rem(710);
    background-color: var(--white);
    transition: all 0.6s ease 0s;
    transform: scale(0.6);
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
    position: relative;
    z-index: 5;
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
        &:hover {
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
