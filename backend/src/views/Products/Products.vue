<template>
    <div class="flex items-center justify-between mb-3">
        <h1 class="text-3xl font-semibold">Products</h1>
        <button type="button"
                class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                @click="showProductModal">Add New Product
        </button>
    </div>
    <ProductModal v-model="showModal" :product="productModal" @close="onModalClose"></ProductModal>
    <ProductsTable @clickEdit="editProduct"></ProductsTable>
</template>

<script setup>
import ProductsTable from "./ProductsTable.vue";
import ProductModal from "./ProductModal.vue";
import {ref} from "vue";
import store from "../../store/index.js";
import {data} from "autoprefixer";

const INITIAL_OBJECT = {
  id: '',
  title: '',
  image: '',
  description: '',
  price: ''

}
const showModal = ref(false);
const productModal = ref({...INITIAL_OBJECT})

function showProductModal() {
  showModal.value = true;
}

function editProduct(product) {
  store.dispatch('getProduct', product.id)
      .then(({data}) => {
        productModal.value = data
        showProductModal()
      })
}

function onModalClose() {
  productModal.value = {...INITIAL_OBJECT}
}

</script>

<style scoped>

</style>