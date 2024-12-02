<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 xl:px-16 xl:ml-64 space-y-6">
        <div class="flex mt-16 items-center justify-between">
            <h2 class="font-semibold text-xl">Edit Stock</h2>
        </div>

        <div class="mt-4">
            <form enctype="multipart/form-data" method="POST" action="{{ route('stock.update', $stock) }}">
                @csrf
                @method('PUT')
                <div>
                    <label for="product_id">Product</label>
                    <select id="product_id" name="product_id" class="block mt-1 w-full">
                        <option value="" disabled selected>Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-stock-in="{{ $product->stock_in }}"
                                data-stock-out="{{ $product->stock_out }}">
                                {{ $stock->product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="stock_in" :value="__('Stock In')" />
                    <x-text-input id="stock_in" class="block mt-1 w-full" type="text" name="stock_in"
                        value="{{ $stock->stock_in }}" readonly />
                    <x-input-error :messages="$errors->get('stock_in')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="stock_out" :value="__('Stock Out')" />
                    <x-text-input id="stock_out" class="block mt-1 w-full" type="text" name="stock_out"
                        value="{{ $stock->stock_out }}" readonly />
                    <x-input-error :messages="$errors->get('stock_out')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="location" :value="__('Location')" />
                    <x-text-input id="location" class="block mt-1 w-full" type="text" name="location"
                        value="{{ $stock->location }}" />
                    <x-input-error :messages="$errors->get('location')" class="mt-2" />
                </div>
                <x-primary-button class="justify-center w-full mt-4">
                    {{ __('Submit') }}
                </x-primary-button>
            </form>
        </div>
    </div>
</x-app-layout>
