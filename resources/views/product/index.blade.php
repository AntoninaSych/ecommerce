<?php
/** @var \Illuminate\Database\Eloquent\Collection $products */
$categoryList = \App\Models\Category::getActiveAsTree();

?>
<x-app-layout>
    <x-category-list :category-list="$categoryList" class="-ml-15 -mt-15 -mr-15 px-4 "/>
    {{--    <h2>Product List</h2>--}}
    <div class="p-3">
        <form action="" method="GET" class="flex-1" @submit.prevent="updateUrl">
            <x-input type="text" name="search" placeholder="Search for the products"
                     x-model="searchKeyword"/>
        </form>
    </div>
    <!-- Product List -->
    <div class="grid gap-4 grig-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 p-3">
        <!-- Product Item -->
        @foreach($products as $product)
            <div
                    x-data="productItem({{ json_encode([
                        'id' => $product->id,
                        'slug' => $product->slug,
                        'image' => $product->image  ?: '/image/no-image.jpeg',
                        'title' => $product->title,
                        'price' => $product->price,
                        'addToCartUrl' => route('cart.add', $product)
                    ]) }})"
                    class="border border-1 border-gray-200 rounded-md hover:border-purple-600 transition-colors bg-white"
            >
                <a href="{{route('product.show',  $product->slug)}}"
                   class="aspect-w-3 aspect-h-2 block overflow-hidden">
                    <img
                            src="{{$product->image ?: '/images/no-image.jpeg'}}"
                            alt=""
                            class="hover:scale-105 hover:rotate-1 transition-transform object-cover"
                    />
                </a>
                <div class="p-4">
                    <h3 class="text-lg">
                        <a href="{{route('product.show',  $product->slug)}}">
                            {{$product->title}}
                        </a>
                    </h3>
                    <h5 class="font-bold">${{$product->price}}</h5>
                </div>
                <div class="flex justify-between py-3 px-4">
                    <button class="btn-primary" @click="addToCart()">
                        Add to Cart
                    </button>
                </div>
            </div>
        @endforeach
        <!--/ Product Item -->

    </div>

    {{$products->links()}}

    <!--/ Product List -->
</x-app-layout>