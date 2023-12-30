<template>
  <div>
    <h1 class="text-3xl font-semibold  mb-4" v-if="!loading">
      {{ product.id ? `Update product: "${product.title}"` : 'Create Product' }}</h1>
    <div class="bg-white p-4 rounded-lg shadow animate-fade-in-down">

      <Spinner v-if="loading"
               class="absolute left-0 top-0 bg-white right-0 bottom-0 flex items-center justify-center"/>


      <form @submit.prevent="onSubmit">
        <div class="bg-white px-4 pt-5 pb-4">
          <CustomInput class="mb-2" v-model="product.title" label="Product Title"/>
          <CustomInput type="file" class="mb-2" label="Product Image"
                       @change="file=>product.image = file"/>
          <CustomInput type="textarea" class="mb-2" v-model="product.description" label="Description"/>
          <CustomInput type="number" class="mb-2" v-model="product.price" label="Price" prepend="$"/>
          <CustomInput type="checkbox" class="mb-2" v-model="product.published" label="Published"/>
        </div>
        <footer class="  px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button type="submit"
                  class="bg-indigo-600  text-white  mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2   text-base font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm
                          hover:bg-indigo-700 focus:ring-indigo-500">
            Submit
          </button>
          <router-link :to="{name:'app.products'}"
                       class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                       ref="cancelButtonRef">
            Cancel
          </router-link>
        </footer>
      </form>

    </div>
  </div>
</template>

<script setup>
import {onMounted, ref} from 'vue'
import Spinner from "../../components/core/Spinner.vue";
import CustomInput from "../../components/core/CustomInput.vue";
import store from "../../store/index.js";
import {useRoute} from "vue-router";

const route = useRoute()
const product = ref({
  id: null,
  title: null,
  image: null,
  description: null,
  price: null,
  published: null
})
const loading = ref(false);

const emit = defineEmits(['update:modelValue', 'close'])


onMounted(() => {

  if (route.params.id) {
    loading.value = true
    //update
    store.dispatch('getProduct', route.params.id)
        .then((response) => {
          loading.value = false;
          product.value = response.data;
        })
  }
})

function onSubmit() {
  loading.value = true
  if (product.value.id) {
    store.dispatch('updateProduct', product.value)
        .then(response => {
          loading.value = false;
          if (response.status === 200) {
            store.dispatch('getProducts')
          }
        })
  } else {
    store.dispatch('createProduct', product.value)
        .then(response => {
          loading.value = false;
          if (response.status === 201) {
            store.dispatch('getProducts')
          }
        })
        .catch(err => {
          loading.value = false;
          debugger;
        })
  }
}
</script>
