<template>
  <div class="flex items-center justify-between mb-3">
    <h1 class="text-3xl font-semibold">Orders</h1>
  </div>
  <OrdersTable @clickView="viewOrder"></OrdersTable>
</template>

<script setup>
import OrdersTable from "./OrdersTable.vue";
import {computed, ref} from "vue";
import store from "../../store/index.js";
import {data} from "autoprefixer";

const orders = computed(() => store.state.orders)

function viewOrder(product) {
  store.dispatch('getOrder', order.id)
      .then(({data}) => {
        productModal.value = data

      })
}


</script>

<style scoped>

</style>