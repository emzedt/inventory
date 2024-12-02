<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Outgoing;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Validation\ValidatesRequests;


class OutgoingController extends Controller
{
    public function index()
    {
        $outgoings = Outgoing::paginate(10);
        $customers = Customer::all();
        $products = Product::all();
        $users = User::all();

        return view('outgoing.index', compact('outgoings', 'customers', 'products', 'users'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        $users = User::all();
        return view('outgoing.create', compact('customers', 'products', 'users'));
    }

    use ValidatesRequests;

    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,jpg,png',
            'reference' => 'required',
            'quantity' => 'required|numeric',
            'date_sent' => 'required|date_format:Y-m-d\TH:i',
            'customer_id' => 'required|numeric',
            'product_id' => 'required|numeric',
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
        while (Outgoing::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        // Menyimpan produk ke database dengan nama file gambar
        Outgoing::create([
            'image' => $fileName,  // Menyimpan path relatif dari gambar
            'reference' => $request->reference,
            'quantity' => $request->quantity,
            'date_sent' => $request->date_sent,
            'customer_id' => $request->customer_id,
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
            'slug' => $slug,
        ]);

        return redirect()->route('outgoing.index')->with('success', 'Add Outgoing Product Success');
    }

    public function edit(Outgoing $outgoing)
    {
        $customers = Customer::all();
        $products = Product::all();
        $users = User::all();
        return view('outgoing.edit', compact('outgoing', 'customers', 'products', 'users'));
    }

    public function update(Request $request, Outgoing $outgoing)
    {
        $this->validate($request, [
            'reference' => 'required',
            'quantity' => 'required|numeric',
            'date_sent' => 'required|date_format:Y-m-d\TH:i',
            'customer_id' => 'required|numeric',
            'product_id' => 'required|numeric',
        ]);

        $outgoing->reference = $request->reference;
        $outgoing->quantity = $request->quantity;
        $outgoing->date_sent = $request->date_sent;
        $outgoing->customer_id = $request->customer_id;
        $outgoing->product_id = $request->product_id;
        $outgoing->user_id = Auth::id();

        if ($request->file('image')) {
            Storage::disk('public')->delete('uploads/' . $outgoing->image);
            $image = $request->file('image');
            $outgoing->image = time() . $image->getClientOriginalName();
            $path = 'uploads/' . $outgoing->image;
            Storage::disk('public')->put($path, file_get_contents($image));
        }

        $outgoing->update();

        // Update stock
        $stock = Stock::where('product_id', $request->product_id)->first();

        if ($stock) {
            // Nilai quantity stock berdasarkan outgoing quantity
            $stock->stock_out = $request->quantity;
            $stock->save();
        } else {
            // Jika tidak ada record stock, buat yang baru
            Stock::create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->route('outgoing.index')->with('success', 'Update Outgoing Product Success');
    }

    public function destroy(Outgoing $outgoing)
    {
        try {
            // Hapus gambar dari storage jika ada
            if ($outgoing->image && Storage::disk('public')->exists('uploads/' . $outgoing->image)) {
                Storage::disk('public')->delete('uploads/' . $outgoing->image);
            }

            // Menghapus Outgoing product
            $outgoing->delete();

            // Return JSON sukses
            return response()->json([
                'message' => 'Outgoing Product Deleted Successfully.'
            ], 200);

            // Redirect ke halaman outgoing product dengan pesan sukses
            return redirect()->route('outgoing.index')->with('success', 'outgoing Product Deleted Successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangkap error jika ada constraint violation
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'Cannot delete this outgoing product because it is associated with other data.'
                ], 400);
            }

            // Tangani error lain
            return response()->json([
                'message' => 'An error occurred while deleting the outgoing product.'
            ], 500);

            return redirect()->route('outgoing.index')->with('success', 'Outgoing Product Deleted Successfully.');
        }
    }
}
