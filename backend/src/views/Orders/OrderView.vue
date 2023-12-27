<template>
  {{ order }}
</template>

<script setup>

import {computed, onMounted, ref} from "vue";
import store from "../../store/index.js";
import {useRoute} from "vue-router";

const orders = computed(() => store.state.orders)
const route = useRoute()
onMounted(() => {
  store.dispatch('getOrder', route.params.id)
});


</script>

<style scoped>

</style>