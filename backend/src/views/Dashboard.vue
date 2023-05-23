<template>
  <div class="mb-2 flex items-center justify-between">
    <h1 class="text-3xl font-semibold">Dashboard</h1>
    <div class="flex items-center">
      <label class="mr-2">Change Date Period</label>
    </div>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
    <!--    Active Customers-->
    <div class="animate-fade-in-down bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center">
      <label class="text-lg font-semibold block mb-2">Active Customers</label>


    </div>
    <!--/    Active Customers-->
    <!--    Active Products -->
    <div class="animate-fade-in-down bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center"
         style="animation-delay: 0.1s">
      <label class="text-lg font-semibold block mb-2">Active Products</label>


    </div>
    <!--/    Active Products -->
    <!--    Paid Orders -->
    <div class="animate-fade-in-down bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center"
         style="animation-delay: 0.2s">
      <label class="text-lg font-semibold block mb-2">Paid Orders</label>

    </div>
    <!--/    Paid Orders -->
    <!--    Total Income -->
    <div class="animate-fade-in-down bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center"
         style="animation-delay: 0.3s">
      <label class="text-lg font-semibold block mb-2">Total Income</label>

    </div>
    <!--/    Total Income -->
  </div>

  <div class="grid grid-rows-1 md:grid-rows-2 md:grid-flow-col grid-cols-1 md:grid-cols-3 gap-3">
    <div class="col-span-1 md:col-span-2 row-span-1 md:row-span-2 bg-white py-6 px-5 rounded-lg shadow">
      <label class="text-lg font-semibold block mb-2">Latest Orders</label>


    </div>
    <div class="bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center">
      <label class="text-lg font-semibold block mb-2">Orders by Country</label>


    </div>
    <div class="bg-white py-6 px-5 rounded-lg shadow">
      <label class="text-lg font-semibold block mb-2">Latest Customers</label>


    </div>
  </div>
</template>

<script>

export default {
  name: "Dashboard"
}
</script>