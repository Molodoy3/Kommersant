<script setup lang="ts">

import axios from "axios";
import {routes} from "@/api.config.js";
import {nextTick, ref} from "vue";
import router from "@/router";

const errors = ref<any>({})

const infoByProperties = ref<any | null>(null)
axios.get(routes.infoByProperties).then((res) => {
  infoByProperties.value = res.data

  nextTick(() => {
    let images: FileList;
    const form = document.forms.namedItem('form') as HTMLFormElement | null;
    if (form) {
      const fileInput = form.querySelector("input[type='file']") as HTMLInputElement | null;
      if (fileInput)
        fileInput.addEventListener("change", listener);
      function listener (e: Event) {
        const target = e.target as HTMLInputElement;
        if (target && target.files) {
          images = target.files;
          if (form) {
            const wrap = form.querySelector(".form__download-images");
            if (wrap) {
              for (let i = 0; i < images.length; i++) {
                const item = document.createElement('div');
                item.classList.add("item-image");
                wrap.appendChild(item);
                const image = images[i];
                const imageUrl = URL.createObjectURL(image);
                item.innerHTML += `<img data-open-image src="${imageUrl}" alt="image">`;
              }
            }
          }

        }
      }
    }
  })
}).catch(res => {
  console.error(res.error)
})

async function submit() {
  const form = document.forms.namedItem('form') as HTMLFormElement | null;
  if (form) {
    //axios.defaults.headers.common['X-CSRF-TOKEN'] = await axios.get(routes.csrf).then(res => res.data)

    const formData = new FormData(form)

    const labelsSelect = document.querySelector('#labels') as HTMLSelectElement | null;
    if (labelsSelect && labelsSelect.multiple) {
      const selectedOptions: HTMLOptionElement[] = Array.from(labelsSelect.selectedOptions);
      if (selectedOptions.length) {
        const selectedValues: string[] = selectedOptions.map(option => option.value);
        formData.append('labels', JSON.stringify(selectedValues));
      }
    }

    await axios.post(routes.properties, formData)
      .then(() => {
        router.push({name: 'admin_properties'})
      })
      .catch(res => {
        errors.value = res.response.data.errors
      })
  }
}

</script>

<template>
  <h1 class="admin-panel__title title">Добавление нового объекта</h1>
  <form v-if="infoByProperties" @submit.prevent="submit" name="form" action="" class="admin-panel__form form" enctype="multipart/form-data">
    <div class="form__item">
      <label for="name" class="label">Название</label>
      <input :class="errors.name ? 'error' : null" name="name" id="name" type="text" class="input"/>
      <div v-if="errors.name" class="form__error">{{ errors.name[0] }}</div>
    </div>
    <div class="form__item">
      <label for="description" class="label">Описание</label>
      <textarea :class="errors.description ? 'error' : null" name="description" id="description" type="text" class="input"></textarea>
      <div v-if="errors.description" class="form__error">{{ errors.description[0] }}</div>
    </div>
    <div class="form__item">
      <label for="prise" class="label">Цена</label>
      <input :class="errors.prise ? 'error' : null" name="prise" id="prise" type="text" class="input"/>
      <div v-if="errors.prise" class="form__error">{{ errors.prise[0] }}</div>
    </div>
    <div class="form__item">
      <label for="prise" class="label">Адрес</label>
      <input :class="errors.address ? 'error' : null" name="address" id="address" type="text" class="input"/>
      <div v-if="errors.address" class="form__error">{{ errors.address[0] }}</div>
    </div>
    <div class="form__item">
      <label for="prise" class="label">Тип объекта</label>
      <select name="type">
        <option v-for="item in infoByProperties.types" :value="item.id">{{item.name}}</option>
      </select>
      <div v-if="errors.type" class="form__error">{{ errors.type }}</div>
    </div>
    <div class="form__item">
      <label for="prise" class="label">Площадь (кв. метры)</label>
      <input :class="errors.square ? 'error' : null" name="square" id="square" type="text" class="input"/>
      <div v-if="errors.square" class="form__error">{{ errors.square[0] }}</div>
    </div>
    <div class="form__item">
      <label for="prise" class="label">Цена</label>
      <input :class="errors.prise ? 'error' : null" name="prise" id="prise" type="text" class="input"/>
      <div v-if="errors.prise" class="form__error">{{ errors.prise[0] }}</div>
    </div>
    <div class="form__item">
      <label for="prise" class="label">Ссылка</label>
      <input :class="errors.link ? 'error' : null" name="link" id="link" type="text" class="input"/>
      <div v-if="errors.link" class="form__error">{{ errors.link[0] }}</div>
    </div>
    <div class="form__item">
      <label for="latitude" class="label">Широта</label>
      <input :class="errors.link ? 'error' : null" name="latitude" id="latitude" type="text" class="input"/>
      <div v-if="errors.latitude" class="form__error">{{ errors.latitude[0] }}</div>
    </div>
    <div class="form__item">
      <label for="longitude" class="label">Долгота</label>
      <input :class="errors.link ? 'error' : null" name="longitude" id="longitude" type="text" class="input"/>
      <div v-if="errors.longitude" class="form__error">{{ errors.longitude[0] }}</div>
    </div>
    <div class="form__item">
      <label class="label">Надписи</label>
      <select id="labels" multiple>
        <option v-for="item in infoByProperties.labels" name="type" :value="item.id">{{item.name}}</option>
      </select>
      <div v-if="errors.labels" class="form__error">{{ errors.labels[0] }}</div>
    </div>
    <div class="form__item">
      <label for="prise" class="label">Тип продажи</label>
      <select name="transactionType">
        <option v-for="item in infoByProperties.transactionTypes" name="type" :value="item.id">{{item.name}}</option>
      </select>
      <div v-if="errors.transactionType" class="form__error">{{ errors.transactionType }}</div>
    </div>
    <div class="form__item">
      <label for="image" class="label">Картинки</label>
      <input multiple class="input_image" type="file" name="images[]" id="image" accept="image/*"
      />
      <div class="form__download-images">
      </div>
      <label for="image" class="button-border">Загрузить изображения</label>
      <div v-if="errors.images" class="form__error">{{ errors.images }}</div>
    </div>

    <button class="button" type="submit">Сохранить</button>
  </form>
</template>

<style scoped>

</style>
