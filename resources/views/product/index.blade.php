<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 xl:px-16 xl:ml-64 space-y-14">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('success'))
            <x-alert message="{{ session('success') }}"></x-alert>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tombol untuk menambah produk -->
        <div class="flex mt-16 items-center justify-between">
            <h2 class="font-semibold text-xl">List Products</h2>
            <a href="{{ route('product.create') }}">
                <button type="button"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add</button>
            </a>
        </div>

        <!-- Tabel Produk -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Product name</th>
                        <th scope="col" class="px-6 py-3 text-center">Image</th>
                        <th scope="col" class="px-6 py-3 text-center">Category</th>
                        <th scope="col" class="px-6 py-3 text-center">Unit</th>
                        <th scope="col" class="px-6 py-3 text-center">Selling Price</th>
                        <th scope="col" class="px-6 py-3 text-center">Purchase Price</th>
                        <th scope="col" class="px-6 py-3 text-center">Published</th>
                        <th scope="col" class="px-6 py-3 text-center">Admin</th>
                        <th scope="col" class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $product->name }}
                            </th>
                            <td class="px-6 py-4" align="center">
                                <img src="{{ asset('storage/uploads/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="w-16 h-16 object-cover rounded-md">
                            </td>
                            <td class="px-6 py-4 text-center">{{ $product->category }}</td>
                            <td class="px-6 py-4 text-center">{{ $product->unit }}</td>
                            <td class="px-6 py-4 text-center">{{ number_format($product->selling_price, 0, ',') }}</td>
                            <td class="px-6 py-4 text-center">{{ number_format($product->purchase_price, 0, ',') }}
                            </td>
                            <td class="px-6 py-4 text-center">{{ $product->published ? 'Yes' : 'No' }}</td>
                            <td class="px-6 py-4 text-center">{{ $product->user->name }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('product.edit', $product) }}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>

                                <!-- Button for Delete -->
                                <button type="button"
                                    class="font-medium text-red-600 dark:text-red-500 hover:underline"
                                    onclick="confirmDelete('{{ $product->name }}', '{{ route('product.destroy', $product) }}')">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginate Products -->
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</x-app-layout>

<script>
    function confirmDelete(name, url) {
        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete the product: ${name}. This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            _method: 'DELETE'
                        })
                    })
                    .then(async (response) => {
                        if (!response.ok) {
                            // Tangkap error respons dari server
                            const errorData = await response.json();
                            throw new Error(errorData.message ||
                                'Failed to delete the product.');
                        }
                        return response.json();
                    })
                    .then((data) => {
                        // Jika sukses, tampilkan pesan dan reload halaman
                        Swal.fire(
                            'Deleted!',
                            `${name} from product has been deleted.`,
                            'success'
                        ).then(() => {
                            location.reload(); // Reload halaman untuk memperbarui data
                        });
                    })
                    .catch((error) => {
                        // Tangani error dengan benar
                        Swal.fire(
                            'Error!',
                            error.message || 'Something went wrong. Please try again later.',
                            'error'
                        );
                    });
            }
        });
    }
</script>
