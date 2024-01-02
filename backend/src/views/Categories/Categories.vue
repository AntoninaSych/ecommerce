<template>
  <div class="flex items-center justify-between mb-3">
    <h1 class="text-3xl font-semibold">Categories</h1>
    <button type="button"
            @click="showAddNewModal()"
            class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
    >
      Add new category
    </button>
  </div>
  <CategoriesTable @clickEdit="editCategory"/>
  <CategoryModal v-model="showCategoryModal" :category="CategoryModel" @close="onModalClose"/>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import store from "../../store";
import CategoryModal from "./CategoryModal.vue";
import CategoriesTable from "./CategoriesTable.vue";

const DEFAULT_CATEGORY = {
  id: '',
  name: '',
  email: '',
  created_at: ''
}

const categories = computed(() => store.state.categories);

const CategoryModel = ref({...DEFAULT_CATEGORY})
const showCategoryModal = ref(false);

function showAddNewModal() {
  showCategoryModal.value = true
}

function editCategory(u) {
  CategoryModel.value = u;
  showAddNewModal();
}

function onModalClose() {
  CategoryModel.value = {...DEFAULT_CATEGORY}
}
</script>

<style scoped>

</style>