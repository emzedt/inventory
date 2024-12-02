<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);

        return view('product.index', compact('products'));
    }

    public function create()
    {
        $users = User::all();
        return view('product.create', compact('users'));
    }

    use ValidatesRequests;

    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,jpg,png',
            'name' => 'required',
            'category' => 'required',
            'unit' => 'required',
            'selling_price' => 'required|numeric',
            'purchase_price' => 'required|numeric',
            'published' => 'required|numeric'
        ]);

        $image = $request->file('image');
        $fileName = time() . $image->getClientOriginalName();
        $path = 'uploads/' . $fileName;
        Storage::disk('public')->put($path, file_get_contents($image));

        // Membuat slug dari nama produk
        $slug = Str::slug($request->name);

        // Memastikan slug unik dengan menambahkan angka jika slug sudah ada
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        // Menyimpan produk ke database dengan nama file gambar
        Product::create([
            'image' => $fileName,  // Menyimpan path relatif dari gambar
            'name' => $request->name,
            'category' => $request->category,
            'unit' => $request->unit,
            'selling_price' => str_replace(".", "", $request->selling_price),
            'purchase_price' => str_replace(".", "", $request->purchase_price),
            'published' => $request->published,
            'user_id' => Auth::id(),
            'slug' => $slug,
        ]);

        return redirect()->route('product.index')->with('success', 'Add Product Success');
    }

    public function edit(Product $product)
    {
        $users = User::all();
        return view('product.edit', compact('product', 'users'));
    }

    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'name' => 'required',
            'category' => 'required',
            'unit' => 'required',
            'selling_price' => 'required|numeric',
            'purchase_price' => 'required|numeric',
            'published' => 'required|numeric'
        ]);

        $product->name = $request->name;
        $product->category = $request->category;
        $product->unit = $request->unit;
        $product->selling_price = str_replace(".", "", $request->selling_price);
        $product->purchase_price = str_replace(".", "", $request->purchase_price);
        $product->published = $request->published;
        $product->user_id = Auth::id();

        if ($request->file('image')) {
            Storage::disk('public')->delete('uploads/' . $product->image);
            $image = $request->file('image');
            $product->image = time() . $image->getClientOriginalName();
            $path = 'uploads/' . $product->image;
            Storage::disk('public')->put($path, file_get_contents($image));
        }

        $product->update();

        return redirect()->route('product.index')->with('success', 'Update Product Success');
    }

    public function destroy(Product $product)
    {
        try {
            // Hapus gambar dari storage jika ada
            if ($product->image && Storage::disk('public')->exists('uploads/' . $product->image)) {
                Storage::disk('public')->delete('uploads/' . $product->image);
            }

            // Menghapus product
            $product->delete();

            // Return JSON sukses
            return response()->json([
                'message' => 'Product Deleted Successfully.'
            ], 200);

            // Redirect ke halaman product dengan pesan sukses
            return redirect()->route('product.index')->with('success', 'Product Deleted Successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangkap error jika ada constraint violation
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'Cannot delete this product because it is associated with other data.'
                ], 400);
            }

            // Tangani error lain
            return response()->json([
                'message' => 'An error occurred while deleting the product product.'
            ], 500);

            return redirect()->route('product.index')->with('success', 'Product Deleted Successfully.');
        }
    }
}
