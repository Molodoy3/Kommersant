<script setup lang="ts">

import axios from "axios";
import {routes} from "@/api.config.js";
import {useRoute} from "vue-router";
import {nextTick, ref} from "vue";
import Preloader from "@/components/Preloader.vue";
import NotFound from "@/views/NotFound.vue";
import router from "@/router";

const route = useRoute()
const id = route.params.id;

const element = ref<any | null>(null)
let title = <string>''
const errors = ref<any>({})

//если нет такой записи
const isFind = ref<boolean>(true)

axios.get(routes.articles  + "/" + id)
  .then(res => {
    element.value = res.data
    title = element.value.title;

    // ставим обработку на загрузку изображения
    nextTick(() => {
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
  })
  .catch(() => {
    isFind.value = false;
  })

async function submit() {
  const form = document.forms.namedItem('form') as HTMLFormElement | null;
  if (form) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = await axios.get(routes.csrf).then(res => res.data)

    const formData = new FormData(form)
    // так как метод put не может передавать formData и файлы, передаем методом post, но добавляем поле _method = PUT
    // благодаря чему ларавел будет принимать как put запрос
    formData.append('_method', 'PATCH');

    await axios.post(routes.articles + "/" + element.value.id, formData)
      .then(res => {
        router.push({name: 'admin_news'})
      })
      .catch(res => {
        errors.value = res.response.data.errors
      })
  }
}

async function deleteElement() {
  if (confirm('Вы уверены что хотите удалить запись?')) {
    await axios.delete(routes.articles + '/' + id).then(() => {
      router.push({name: 'admin_news'})
    }).catch(() => {
      alert('произошла ошибка')
      router.push({name: 'admin_news'})
    })
  }
}

</script>

<template>
  <NotFound v-if="!isFind"/>
  <h1 v-if="element" class="admin-panel__title title">{{title}} — редактирование</h1>
  <form v-if="element" @submit.prevent="submit" name="form" action="" class="admin-panel__form form" enctype="multipart/form-data">
    <div class="form__item">
      <label for="title" class="label">Название</label>
      <input v-model="element.title" :class="errors.title ? 'error' : null" name="title" id="title" type="text" class="input"/>
      <div v-if="errors.title" class="form__error">{{ errors.title[0] }}</div>
    </div>
    <div class="form__item">
      <label for="description" class="label">Описание</label>
      <textarea v-model="element.description" :class="errors.description ? 'error' : null" name="description" id="description" type="text" class="input"></textarea>
      <div v-if="errors.description" class="form__error">{{ errors.description[0] }}</div>
    </div>
    <div class="form__item">
      <label for="image" class="label">Картинка</label>
      <input class="input_image" type="file" name="image" id="image" accept="image/*"
             />
      <div class="form__image">
        <picture>
          <source :srcset='element.image + ".webp"' :type='"image/webp"'>
          <source :srcset='element.image + "." + element.image_extension' :type='"image/" + element.image_extension'>
          <img v-lazy='element.image + ".webp"'>
        </picture>
      </div>
      <label for="image" class="button-border">Загрузить изображение</label>
    </div>
    <button class="button" type="submit">Сохранить</button>
    <button @click="deleteElement" type="button" class="button admin-panel__delete">Удалить</button>
  </form>
  <Preloader v-else/>
</template>

<style scoped>

</style>
