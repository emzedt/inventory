<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Validation\ValidatesRequests;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::paginate(10);

        return view('customer.index', compact('customers'));
    }

    public function create()
    {
        $users = User::all();
        return view('customer.create', compact('users'));
    }

    use ValidatesRequests;

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'contact' => 'required|numeric'
        ]);

        // Membuat slug dari nama customer
        $slug = Str::slug($request->name);

        // Memastikan slug unik dengan menambahkan angka jika slug sudah ada
        $originalSlug = $slug;
        $count = 1;
        while (Customer::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        // Menyimpan customer ke database
        Customer::create([
            'name' => $request->name,
            'address' => $request->address,
            'contact' => $request->contact,
            'user_id' => Auth::id(),
            'slug' => $slug
        ]);

        return redirect()->route('customer.index')->with('success', 'Add Customer Success');
    }

    public function edit(Customer $customer)
    {
        $users = User::all();
        return view('customer.edit', compact('customer', 'users'));
    }

    public function update(Request $request, Customer $customer)
    {
        $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'contact' => 'required|numeric'
        ]);

        $customer->name = $request->name;
        $customer->address = $request->address;
        $customer->contact = $request->contact;
        $customer->user_id = Auth::id();

        $customer->update();

        return redirect()->route('customer.index')->with('success', 'Update Customer Success');
    }

    public function destroy(Customer $customer)
    {
        try {
            // Menghapus customer
            $customer->delete();

            // Return JSON sukses
            return response()->json([
                'message' => 'customer Deleted Successfully.'
            ], 200);

            // Redirect ke halaman customer dengan pesan sukses
            return redirect()->route('customer.index')->with('success', 'Customer Deleted Successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangkap error jika ada constraint violation
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'Cannot delete this Customer because it is associated with other data.'
                ], 400);
            }

            // Tangani error lain
            return response()->json([
                'message' => 'An error occurred while deleting the Customer.'
            ], 500);

            return redirect()->route('customer.index')->with('success', 'Customer Deleted Successfully.');
        }
    }
}
