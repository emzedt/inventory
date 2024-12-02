<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Incoming;
use App\Models\Outgoing;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Validation\ValidatesRequests;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::paginate(10);
        $products = Product::all();

        // Untuk setiap produk, hitung stock_in dan stock_out
        foreach ($products as $product) {
            $product->stock_in = Incoming::where('product_id', $product->id)->sum('quantity');
            $product->stock_out = Outgoing::where('product_id', $product->id)->sum('quantity');
        }

        return view('stock.index', compact('stocks', 'products'));
    }

    // Method untuk menampilkan form tambah stock
    public function create()
    {
        // Mengambil semua produk
        $products = Product::all();
        $users = User::all();

        // Menghitung stock_in dan stock_out untuk setiap produk
        foreach ($products as $product) {
            $product->stock_in = Incoming::where('product_id', $product->id)->sum('quantity');
            $product->stock_out = Outgoing::where('product_id', $product->id)->sum('quantity');
        }

        // Mengirim data produk ke view
        return view('stock.create', compact('products', 'users'));
    }

    use ValidatesRequests;

    public function store(Request $request)
    {
        $this->validate($request, [
            'stock_in' => 'required|numeric',
            'stock_out' => 'required|numeric',
            'location' => 'required'
        ]);

        // Membuat slug dari lokasi stock
        $slug = Str::slug($request->location);

        // Memastikan slug unik dengan menambahkan angka jika slug sudah ada
        $originalSlug = $slug;
        $count = 1;
        while (Stock::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        // Menyimpan Stock ke database
        Stock::create([
            'stock_in' => $request->stock_in,
            'stock_out' => $request->stock_out,
            'location' => $request->location,
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
            'slug' => $slug
        ]);

        // Ambil product_id dari request
        $product_id = $request->product_id;

        // Hitung stock_in dan stock_out berdasarkan product_id
        $stock_in = Incoming::where('product_id', $product_id)->sum('quantity');
        $stock_out = Outgoing::where('product_id', $product_id)->sum('quantity');

        return redirect()->route('stock.index')
            ->with('success', 'Add Stock Success')
            ->with('stock_in', $stock_in)
            ->with('stock_out', $stock_out);
    }


    public function edit(Stock $stock)
    {
        $products = Product::all();
        $users = User::all();
        return view('stock.edit', compact('stock', 'products', 'users'));
    }

    public function update(Request $request, Stock $stock)
    {
        $this->validate($request, [
            'stock_in' => 'required|numeric',
            'stock_out' => 'required|numeric',
            'location' => 'required'
        ]);

        $stock->stock_in = $request->stock_in;
        $stock->stock_out = $request->stock_out;
        $stock->location = $request->location;
        $stock->product_id = $request->product_id;
        $stock->user_id = Auth::id();

        $stock->update();

        return redirect()->route('stock.index')->with('success', 'Update Stock Success');
    }

    public function destroy(Stock $stock)
    {
        try {
            // Menghapus stock
            $stock->delete();

            // Return JSON sukses
            return response()->json([
                'message' => 'Stock Deleted Successfully.'
            ], 200);

            // Redirect ke halaman stock dengan pesan sukses
            return redirect()->route('stock.index')->with('success', 'Stock Deleted Successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangkap error jika ada constraint violation
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'Cannot delete this Stock because it is associated with other data.'
                ], 400);
            }

            // Tangani error lain
            return response()->json([
                'message' => 'An error occurred while deleting the stock.'
            ], 500);

            return redirect()->route('stock.index')->with('success', 'Stock Deleted Successfully.');
        }
    }
}
