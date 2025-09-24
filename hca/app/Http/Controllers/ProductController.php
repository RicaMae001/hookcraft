<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Homepage - show featured products
     */
    public function index()
    {
        // Fetch first 4 products as featured
        $products = Product::take(4)->get();
        return view('index', compact('products'));
    }

    /**
     * Shop page - show all products with categories
     */
    public function shop()
    {
        // Fetch all products
        $products = Product::with('category')->get();

        // Fetch all categories
        $categories = Category::orderBy('name')->get();

        return view('pages.shop', compact('products', 'categories'));
    }

    /**
     * Product details page
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('product', compact('product'));
    }
}
