<?php

namespace App\Http\Controllers;

use Log;
use App\Models\User;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Incoming;
use App\Models\Supplier;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Validation\ValidatesRequests;

class IncomingController extends Controller
{
    public function index()
    {
        $incomings = Incoming::paginate(10);
        $products = Product::all();
        $suppliers = Supplier::all();

        return view('incoming.index', compact('incomings', 'products', 'suppliers'));
    }

    public function create()
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        $users = User::all();
        return view('incoming.create', compact('products', 'suppliers', 'users'));
    }

    use ValidatesRequests;

    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,jpg,png',
            'reference' => 'required',
            'quantity' => 'required|numeric',
            'date_received' => 'required|date_format:Y-m-d\TH:i',
            'product_id' => 'required|numeric',
            'supplier_id' => 'required|numeric'
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
        while (Incoming::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        // Menyimpan produk ke database dengan nama file gambar
        Incoming::create([
            'image' => $fileName,  // Menyimpan path relatif dari gambar
            'reference' => $request->reference,
            'quantity' => $request->quantity,
            'date_received' => $request->date_received,
            'product_id' => $request->product_id,
            'supplier_id' => $request->supplier_id,
            'user_id' => Auth::id(),
            'slug' => $slug,
        ]);

        return redirect()->route('incoming.index')->with('success', 'Add Incoming Product Success');
    }

    public function edit(Incoming $incoming)
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        $users = User::all();
        return view('incoming.edit', compact('incoming', 'products', 'suppliers', 'users'));
    }

    public function update(Request $request, Incoming $incoming)
    {
        $this->validate($request, [
            'reference' => 'required',
            'quantity' => 'required|numeric',
            'date_received' => 'required|date_format:Y-m-d\TH:i',
            'product_id' => 'required|numeric',
            'supplier_id' => 'required|numeric'
        ]);

        $incoming->reference = $request->reference;
        $incoming->quantity = $request->quantity;
        $incoming->date_received = $request->date_received;
        $incoming->product_id = $request->product_id;
        $incoming->supplier_id = $request->supplier_id;
        $incoming->user_id = Auth::id();

        if ($request->file('image')) {
            Storage::disk('public')->delete('uploads/' . $incoming->image);
            $image = $request->file('image');
            $incoming->image = time() . $image->getClientOriginalName();
            $path = 'uploads/' . $incoming->image;
            Storage::disk('public')->put($path, file_get_contents($image));
        }

        $incoming->update();

        // Update stock
        $stock = Stock::where('product_id', $request->product_id)->first();

        if ($stock) {
            // Menambah quantity stock berdasarkan incoming quantity
            $stock->stock_in = $request->quantity;
            $stock->save();
        } else {
            // Jika tidak ada record stock, buat yang baru
            Stock::create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->route('incoming.index')->with('success', 'Update Incoming Product Success');
    }

    public function destroy(Incoming $incoming)
    {
        try {
            // Hapus gambar dari storage jika ada
            if ($incoming->image && Storage::disk('public')->exists('uploads/' . $incoming->image)) {
                Storage::disk('public')->delete('uploads/' . $incoming->image);
            }

            // Menghapus incoming product
            $incoming->delete();

            // Return JSON sukses
            return response()->json([
                'message' => 'Incoming Product Deleted Successfully.'
            ], 200);

            // Redirect ke halaman incoming product dengan pesan sukses
            return redirect()->route('incoming.index')->with('success', 'Incoming Product Deleted Successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangkap error jika ada constraint violation
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'Cannot delete this incoming product because it is associated with other data.'
                ], 400);
            }

            // Tangani error lain
            return response()->json([
                'message' => 'An error occurred while deleting the incoming product.'
            ], 500);

            return redirect()->route('incoming.index')->with('success', 'Incoming Product Deleted Successfully.');
        }
    }
}
