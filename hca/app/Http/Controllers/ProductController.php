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
        $categories = Category::orderByDesc('limited_edition')->orderBy('name')->get();
        $products = Product::all();
        $cartCount = $this->getCartCount();
        
        return view('index', compact('categories', 'products', 'cartCount'));
    }

    /**
     * Shop page - show all products with categories
     */
    public function shop()
    {
        // Get all available products with their categories
        $products = Product::where('is_available', 1)
            ->with('category')
            ->get();
            
        $categories = Category::orderByDesc('limited_edition')
            ->orderBy('name')
            ->get();
        
        $cartCount = $this->getCartCount();
        
        // Debug: Log the data
        \Log::info('Shop Products Count: ' . $products->count());
        \Log::info('Products with category: ' . $products->whereNotNull('category_id')->count());

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

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        // Validate the request...
        
        $data = $request->all();
        $data['limited_edition'] = $request->has('limited_edition') ? 1 : 0;
        Category::create($data);

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the request...

        $category = Category::findOrFail($id);
        $data = $request->all();
        $data['limited_edition'] = $request->has('limited_edition') ? 1 : 0;
        $category->update($data);

        return redirect()->route('admin.products')->with('success', 'Category updated successfully.');
    }
}