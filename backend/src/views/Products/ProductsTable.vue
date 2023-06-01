<template>
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="flex justify-between bor pb-3">
            <div class="flex items-center">
                <span class="whitespace-nowrap mr-3"> Per Page </span>
                <select @change="getProducts(null)" v-model="perPage"
                        class="appearance-none relative block w-24 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
        <div>
            <input v-model="search" @change="getProducts(null)"
                   class="appearance-none relative block w-48 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                   placeholder="Type to Search Products">
        </div>


        <table class="table-auto w-full">
            <thead>
            <TableHeaderCell @click="sortProduct('id')" class="border-b-2 p-2 text-left" field="id"
                             :sort-field="sortField"
                             :sort-direction="sortDirection">ID
            </TableHeaderCell>
            <TableHeaderCell class="border-b-2 p-2 text-left" field="" :sort-field="sortField"
                             :sort-direction="sortDirection">Image
            </TableHeaderCell>
            <TableHeaderCell @click="sortProduct('title')" class="border-b-2 p-2 text-left" field="title"
                             :sort-field="sortField"
                             :sort-direction="sortDirection">Title
            </TableHeaderCell>
            <TableHeaderCell @click="sortProduct('price')" class="border-b-2 p-2 text-left" field="price"
                             :sort-field="sortField"
                             :sort-direction="sortDirection">Price
            </TableHeaderCell>
            <TableHeaderCell @click="sortProduct('updated_at')" class="border-b-2 p-2 text-left" field="updated_at"
                             :sort-field="sortField" :sort-direction="sortDirection">Last Updated At
            </TableHeaderCell>
            </thead>
            <tbody v-if="products.loading">
            <tr>
                <td colspan="5">
                    <Spinner class="my-4"/>
                </td>
            </tr>
            </tbody>
            <tbody v-else>
            <tr v-for="product of products.data">
                <td class="border-b p-2"> {{ product.id }}</td>
                <td class="border-b p-2"><img class="w-16" :src="product.image_url" :alt="product.title"></td>
                <td class="border-b p-2 max-w-[200px] whitespace-nowrap overflow-hidden text-ellipsis">{{
                    product.title
                    }}
                </td>
                <td class="border-b p-2"> {{ product.price }}</td>
                <td class="border-b p-2"> {{ product.updated_at }}</td>
            </tr>
            </tbody>
        </table>
        <div v-if="!products.loading" class="flex justify-between items-center mt-5">
        <span>
          Showing from {{ products.from }} to {{ products.to }}
        </span>
            <nav
                    v-if="products.total > products.limit"
                    class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px"
                    aria-label="Pagination"
            ><a v-for="(link, i) of products.links"
                :key="i"
                :disabled="!link.url"
                href="#"
                @click.prevent="getForPage($event, link)"
                aria-current="page"
                class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap"
                :class="[
                link.active
                ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                i === 0 ? 'rounded-l-md' : '' ,
                i === products.links.length -1 ? 'rounded-r-md' : '',
                !link.url ? 'bg-gray-100 text-gray-700' : ''
            ]"
                v-html="link.label"
            >
            </a>
            </nav>
        </div>

    </div>
</template>

<script setup>
import Spinner from "../../components/core/Spinner.vue";
import {computed, onMounted, ref} from "vue";
import store from "../../store/index.js";
import {PRODUCTS_PER_PAGE} from "../../constants.js";
import TableHeaderCell from "../../components/Table/TableHeaderCell.vue";


const perPage = ref(PRODUCTS_PER_PAGE)
const search = ref('')
const products = computed(() => store.state.products)
const sortField = ref('updated_at')
const sortDirection = ref('desc')


onMounted(() => {
    getProducts(null);
})

function getProducts(url = null) {
    store.dispatch('getProducts', {
        url,
        search: search.value,
        per_page: perPage.value,
        sort_field: sortField.value,
        sort_direction: sortDirection.value,
    })
}

function getForPage(event, link) {
    if (!link.url || link.active) {
        return
    }
    getProducts(link.url)
}

function sortProduct(field) {
    if (field === sortField.value) {
        if (sortDirection.value === 'asc') {
            sortDirection.value = 'desc'
        } else {
            sortDirection.value = 'asc'
        }
    } else {
        sortField.value = field;
        sortDirection.value = 'asc'
    }
    getProducts()
}

</script>

<style scoped>

</style>