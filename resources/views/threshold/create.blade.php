<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 xl:px-16 xl:ml-64 space-y-6">
        <div class="flex mt-16 items-center justify-between">
            <h2 class="font-semibold text-xl">Add Threshold</h2>
        </div>

        <div class="mt-4" x-data="{ imageUrl: '/storage/noimage.png' }">
            <form enctype="multipart/form-data" method="POST" action="{{ route('threshold.store') }}">
                @csrf
                <div>
                    <x-input-label for="product" :value="__('Product Name')" />
                    <select id="product" name="product_id"
                        class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                        <option value="" disabled selected>-- Select Product --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('product_id')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="minimum_stock" :value="__('Minimum Stock')" />
                    <x-text-input id="minimum_stock" class="block mt-1 w-full" type="text" name="minimum_stock"
                        :value="old('minimum_stock')" />
                    <x-input-error :messages="$errors->get('minimum_stock')" class="mt-2" />
                </div>
                <x-primary-button class="justify-center w-full mt-4">
                    {{ __('Submit') }}
                </x-primary-button>
            </form>
        </div>
    </div>
</x-app-layout>
