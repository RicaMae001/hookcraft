<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Get cart count for authenticated users
     */
    private function getCartCount()
    {
        if (Auth::check()) {
            return CartItem::whereHas('cart', function ($q) {
                $q->where('user_id', Auth::id());
            })->count();
        }
        return 0;
    }

    /**
     * Homepage - show featured products
     */
    public function index()
    {
        // Fetch first 4 products as featured
        $products = Product::take(4)->get();
        $cartCount = $this->getCartCount();
        
        return view('index', compact('products', 'cartCount'));
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
        
        $cartCount = $this->getCartCount();

        return view('pages.shop', compact('products', 'categories', 'cartCount'));
    }

    /**
     * Product details page
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        $cartCount = $this->getCartCount();
        
        return view('product', compact('product', 'cartCount'));
    }

    /**
     * About page
     */
    public function about()
    {
        $cartCount = $this->getCartCount();
        
        return view('pages.about', compact('cartCount'));
    }

    /**
     * Gallery page
     */
    public function gallery()
    {
        $cartCount = $this->getCartCount();
        
        return view('pages.gallery', compact('cartCount'));
    }

    /**
     * Contact page
     */
    public function contact()
    {
        $cartCount = $this->getCartCount();
        
        return view('pages.contact', compact('cartCount'));
    }
}