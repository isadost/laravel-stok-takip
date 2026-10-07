<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Rule;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
 public function index(Request $request)
{
    $products = Product::with('category')
        ->when($request->filled('search'), function ($query) use ($request) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('sku', 'like', "%{$term}%");
            });
        })
        ->when($request->filled('category_id'), function ($query) use ($request) {
            $query->where('category_id', $request->input('category_id'));
        })
        ->latest()
        ->orderByDesc('id')
        ->paginate(10)
        ->withQueryString();

    $categories = Category::orderBy('name')->get();

    return view('products.index', compact('products', 'categories'));
}
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'quantity' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
        ]);

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Ürün eklendi.');
    }

    public function show(Product $product)
    {
        //
    }

    public function edit(Product $product)
{
    $categories = Category::orderBy('name')->get();

    return view('products.edit', compact('product', 'categories'));
}

public function update(Request $request, Product $product)
{
    $validated = $request->validate([
        'category_id' => ['required', 'exists:categories,id'],
        'name' => ['required', 'string', 'max:255'],
        'sku' => ['required', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($product->id)],
        'quantity' => ['required', 'integer', 'min:0'],
        'price' => ['required', 'numeric', 'min:0'],
        'min_stock' => ['required', 'integer', 'min:0'],
    ]);

    $product->update($validated);

    return redirect()->route('products.index')->with('success', 'Ürün güncellendi.');
}

public function destroy(Product $product)
 {
    $product->delete();

    return redirect()->route('products.index')->with('success', 'Ürün silindi.');
 }

}