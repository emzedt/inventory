<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Threshold;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ThresholdController extends Controller
{
    public function index()
    {
        $thresholds = Threshold::paginate(10);

        return view('threshold.index', compact('thresholds'));
    }

    public function create()
    {
        $users = User::all();
        $products = Product::all();
        return view('threshold.create', compact('users', 'products'));
    }

    use ValidatesRequests;

    public function store(Request $request)
    {
        $this->validate($request, [
            'minimum_stock' => 'required|numeric'
        ]);

        // Mendapatkan nama produk dari product_id yang diberikan
        $product = Product::find($request->product_id);

        // Membuat slug dari nama produk
        $slug = Str::slug($product->name);

        // Memastikan slug unik dengan menambahkan angka jika slug sudah ada
        $originalSlug = $slug;
        $count = 1;
        while (Threshold::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        // Menyimpan threshold ke database
        $threshold = Threshold::create([
            'minimum_stock' => $request->minimum_stock,
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
            'slug' => $slug
        ]);

        $product->threshold_id = $threshold->id;
        $product->save();

        return redirect()->route('threshold.index')->with('success', 'Add Threshold Success');
    }

    public function edit(Threshold $threshold)
    {
        $users = User::all();
        $products = Product::all();
        return view('threshold.edit', compact('threshold', 'users', 'products'));
    }

    public function update(Request $request, Threshold $threshold)
    {
        $this->validate($request, [
            'minimum_stock' => 'required|numeric'
        ]);

        $threshold->minimum_stock = $request->minimum_stock;
        $threshold->product_id = $request->product_id;
        $threshold->user_id = Auth::id();

        $threshold->update();

        return redirect()->route('threshold.index')->with('success', 'Update Threshold Success');
    }

    public function destroy(Threshold $threshold)
    {
        try {
            // Menghapus threshold
            $threshold->delete();

            // Return JSON sukses
            return response()->json([
                'message' => 'Threshold Deleted Successfully.'
            ], 200);

            // Redirect ke halaman threshold dengan pesan sukses
            return redirect()->route('threshold.index')->with('success', 'Threshold Deleted Successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangkap error jika ada constraint violation
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'Cannot delete this Threshold because it is associated with other data.'
                ], 400);
            }

            // Tangani error lain
            return response()->json([
                'message' => 'An error occurred while deleting the Threshold.'
            ], 500);

            return redirect()->route('threshold.index')->with('success', 'Threshold Deleted Successfully.');
        }
    }
}
