<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Validation\ValidatesRequests;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::paginate(10);

        return view('supplier.index', compact('suppliers'));
    }

    public function create()
    {
        $users = User::all();
        return view('supplier.create', compact('users'));
    }

    use ValidatesRequests;

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'contact' => 'required|numeric',
            'published' => 'required|numeric'
        ]);

        // Membuat slug dari nama supplier
        $slug = Str::slug($request->name);

        // Memastikan slug unik dengan menambahkan angka jika slug sudah ada
        $originalSlug = $slug;
        $count = 1;
        while (Supplier::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        // Menyimpan supplier ke database
        Supplier::create([
            'name' => $request->name,
            'address' => $request->address,
            'contact' => $request->contact,
            'published' => $request->published,
            'user_id' => Auth::id(),
            'slug' => $slug
        ]);

        return redirect()->route('supplier.index')->with('success', 'Add Supplier Success');
    }

    public function edit(Supplier $supplier)
    {
        $users = User::all();
        return view('supplier.edit', compact('supplier', 'users'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'contact' => 'required|numeric',
            'published' => 'required|numeric'
        ]);

        $supplier->name = $request->name;
        $supplier->address = $request->address;
        $supplier->contact = $request->contact;
        $supplier->published = $request->published;
        $supplier->user_id = Auth::id();

        $supplier->update();

        return redirect()->route('supplier.index')->with('success', 'Update Supplier Success');
    }

    public function destroy(Supplier $supplier)
    {
        try {
            // Menghapus supplier
            $supplier->delete();

            // Return JSON sukses
            return response()->json([
                'message' => 'Supplier Deleted Successfully.'
            ], 200);

            // Redirect ke halaman supplier dengan pesan sukses
            return redirect()->route('supplier.index')->with('success', 'Supplier Deleted Successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangkap error jika ada constraint violation
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'Cannot delete this Supplier because it is associated with other data.'
                ], 400);
            }

            // Tangani error lain
            return response()->json([
                'message' => 'An error occurred while deleting the supplier.'
            ], 500);

            return redirect()->route('supplier.index')->with('success', 'Supplier Deleted Successfully.');
        }
    }
}
