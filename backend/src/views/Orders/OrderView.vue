<template>

  <div v-if="order">
    <!--  Order Details-->
    <h2 class="flex justify-between  items-center text-xl font-semi-bold mt-6 pb-2 border-b  border-gray-300">Order
      Details
      <OrderStatus :order="order"/>
    </h2>

    <table>
      <tbody>
      <tr>
        <td class="font-bold py-1 px-2">Order #</td>
        <td>{{ order.id }}</td>
      </tr>
      <tr>
        <td class="font-bold py-1 px-2">Order Date</td>
        <td>{{ order.created_at }}</td>
      </tr>
      <tr>
        <td class="font-bold py-1 px-2">Order Status</td>
        <td>
          <select v-model="order.status" @change="onStatusChange">
            <option v-for="status of orderStatuses" :value="status">{{ status }}</option>
          </select>
        </td>
      </tr>
      <tr>
        <td class="font-bold py-1 px-2">SubTotal</td>
        <td>${{ order.total_price }}</td>
      </tr>
      </tbody>
    </table>
    <!--  Order Details-->

    <!--  Customer Details-->
    <div>
      <h2 class="text-xl font-semi-bold mt-6 pb-2 border-b border-gray-300">Customer Details</h2>
      <table class="pt-2">
        <tbody>
        <tr>
          <td class="font-bold py-1 px-2">Full Name</td>
          <td>{{ customer.first_name }} {{ customer.last_name }}</td>
        </tr>
        <tr>
          <td class="font-bold py-1 px-2">Email</td>
          <td>{{ customer.email }}</td>
        </tr>
        <tr>
          <td class="font-bold py-1 px-2">Phone</td>
          <td>{{ customer.phone }}</td>
        </tr>
        </tbody>
      </table>
    </div>
    <!--  Customer Details-->

    <!--  Addresses-->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <div>
        <h2 class="text-xl font-semi-bold mt-6 pb-2 border-b border-gray-300">Billing Address</h2>
        <!--  Billing Address Details-->
        <div class="pt-2">
          {{ billingAddress.address1 }}, {{ billingAddress.address2 }} <br>
          {{ billingAddress.city }}, {{ billingAddress.zipcode }} <br>
          {{ billingAddress.state }}, {{ billingAddress.country }} <br>
        </div>
        <!--/  Billing Address Details-->
      </div>
      <div>
        <h2 class="text-xl font-semi-bold mt-6 pb-2 border-b border-gray-300">Shipping Address</h2>
        <!--  Shipping Address Details-->
        <div class="pt-2">
          {{ shippingAddress.address1 }}, {{ shippingAddress.address2 }} <br>
          {{ shippingAddress.city }}, {{ shippingAddress.zipcode }} <br>
          {{ shippingAddress.state }}, {{ shippingAddress.country }} <br>
        </div>
        <!--/  Shipping Address Details-->
      </div>
    </div>
    <!--  Addresses-->


    <!--  Order Items-->
    <div>
      <h2 class="text-xl font-semibold mt-6 pb-2 border-b border-gray-300">Order Items</h2>
      <div v-for="item of order.items">
        <!-- Order Item -->
        <div class="flex flex-col sm:flex-row items-center  gap-4">
          <a href="#"
             class="w-36 h-32 flex items-center justify-center overflow-hidden">
            <img :src="item.product.image" class="object-cover" alt=""/>
          </a>
          <div class="flex flex-col justify-between flex-1">
            <div class="flex justify-between mb-3">
              <h3>
                {{ item.product.title }}
              </h3>
            </div>
            <div class="flex justify-between items-center">
              <div class="flex items-center">Qty: {{ item.quantity }}</div>
              <span class="text-lg font-semibold"> ${{ item.unit_price }} </span>
            </div>
          </div>
        </div>
        <!--/ Order Item -->
        <hr class="my-3"/>
      </div>
    </div>
    <!--  Order Items-->
  </div>
</template>

<script setup>

import {computed, onMounted, ref} from "vue";
import store from "../../store/index.js";
import {useRoute} from "vue-router";
import axiosClient from "../../axios.js";
import OrderStatus from "./OrderStatus.vue";

const order = ref({})
const orderStatuses = ref([]);
const billingAddress = ref({})
const shippingAddress = ref({})
const customer = ref({})
const orders = computed(() => store.state.orders)
const route = useRoute()
onMounted(() => {
  axiosClient.get(`/orders/statuses`)
      .then(({data}) => orderStatuses.value = data)
  store.dispatch('getOrder', route.params.id).then((response) => {
    order.value = response.data;
    customer.value = order.value.customer;
    billingAddress.value = customer.value.billingAddress;
    shippingAddress.value = customer.value.shippingAddress
  })

})

function onStatusChange() {
  axiosClient.post(`/orders/change-status/${order.value.id}/${order.value.status}`)
      .then(({data}) => {
        store.commit('showToast', `Order status was successfully changed into "${order.value.status}"`)
      })

}


</script>

<style scoped>

</style>