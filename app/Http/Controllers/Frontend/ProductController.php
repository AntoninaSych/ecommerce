<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::query()->orderBy('updated_at', 'desc')->paginate(10);

        return view('product.index')->with(['products' => $products]);
    }

    public function show(Product $product)
    {
        return view('product.show')->with(['product' => $product]);
    }

}