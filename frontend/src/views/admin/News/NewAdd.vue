<script setup lang="ts">

import axios from "axios";
import {routes} from "@/api.config.js";
import {onMounted, ref} from "vue";
import router from "@/router";

const errors = ref<any>({})

onMounted(()=> {
  let images: FileList;
  const form = document.forms.namedItem('form') as HTMLFormElement | null;
  if (form) {
    const fileInput = form.querySelector("input[type='file']") as HTMLInputElement | null;
    if (fileInput) {
      fileInput.addEventListener("change", listener);
    }
    function listener (e: Event) {
      const target = e.target as HTMLInputElement; // Уточняем тип target
      if (target && target.files) {
        images = target.files
        if (form) {
          const item = form.querySelector(".form__image") as HTMLElement | null;
          if (item) {
            const image = images[0];
            const imageUrl = URL.createObjectURL(image);
            item.innerHTML = `<img data-open-image src="${imageUrl}" alt="image">`;
          }
        }
      }
    }
  }
})

async function submit() {
  const form = document.forms.namedItem('form') as HTMLFormElement | null;
  if (form) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = await axios.get(routes.csrf).then(res => res.data)

    const formData = new FormData(form)
    await axios.post(routes.articles, formData)
      .then(() => {
        router.push({name: 'admin_news'})
      })
      .catch(res => {
        errors.value = res.response.data.errors
      })
  }
}

</script>

<template>
  <h1 class="admin-panel__title title">Добавление новой новости</h1>
  <form @submit.prevent="submit" name="form" action="" class="admin-panel__form form" enctype="multipart/form-data">
    <div class="form__item">
      <label for="title" class="label">Название</label>
      <input :class="errors.title ? 'error' : null" name="title" id="title" type="text" class="input"/>
      <div v-if="errors.title" class="form__error">{{ errors.title[0] }}</div>
    </div>
    <div class="form__item">
      <label for="description" class="label">Описание</label>
      <textarea :class="errors.description ? 'error' : null" name="description" id="description" type="text" class="input"></textarea>
      <div v-if="errors.description" class="form__error">{{ errors.description[0] }}</div>
    </div>
    <div class="form__item">
      <label for="image" class="label">Картинка</label>
      <input class="input_image" type="file" name="image" id="image" accept="image/*"
      />
      <div class="form__image">
      </div>
      <label for="image" class="button-border">Загрузить изображение</label>
      <div v-if="errors.image" class="form__error">{{ errors.image }}</div>
    </div>

    <button class="button" type="submit">Сохранить</button>
  </form>
</template>

<style scoped>

</style>
