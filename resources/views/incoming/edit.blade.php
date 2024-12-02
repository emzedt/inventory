<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 xl:px-16 xl:ml-64 space-y-6">
        <div class="flex mt-16 items-center justify-between">
            <h2 class="font-semibold text-xl">Edit Incoming Product</h2>
        </div>

        <div class="mt-4" x-data="{ imageUrl: '/uploads/{{ $incoming->image }}' }">
            <form enctype="multipart/form-data" method="POST" action="{{ route('incoming.update', $incoming) }}"
                class="flex gap-8">
                @csrf
                @method('PUT')
                <div class="w-1/2">
                    <img :src="imageUrl" class="rounded-md" alt="Product Image" />
                </div>
                <div class="w-1/2">
                    <div>
                        <x-input-label for="image" :value="__('Image')" />
                        <!-- Use input tag for file -->
                        <input id="image" class="block mt-1 w-full border p-2" type="file" name="image"
                            accept="image/*" @change="imageUrl = URL.createObjectURL($event.target.files[0])" />
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="reference" :value="__('Reference')" />
                        <x-text-input id="reference" class="block mt-1 w-full" type="text" name="reference"
                            :value="$incoming->reference" />
                        <x-input-error :messages="$errors->get('reference')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="product" :value="__('Product Name')" />
                        <select id="product" name="product_id"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                            <option value="" disabled selected>Select Product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}"
                                    {{ $product->id == $incoming->product_id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('product_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="supplier" :value="__('Supplier Name')" />
                        <select id="supplier" name="supplier_id"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                            <option value="" disabled>Select Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ $supplier->id == $incoming->supplier_id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('supplier_id')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="quantity" :value="__('Quantity')" />
                        <x-text-input id="quantity" class="block mt-1 w-full" type="text" name="quantity"
                            :value="$incoming->quantity" />
                        <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="date_received" :value="__('Date Received')" />
                        <x-text-input id="date_received" class="block mt-1 w-full" type="datetime-local"
                            name="date_received" :value="$incoming->date_received
                                ? $incoming->date_received
                                : now()->format('Y-m-d\TH:i')" />

                        <x-input-error :messages="$errors->get('date_received')" class="mt-2" />
                    </div>

                    <x-primary-button class="justify-center w-full mt-4">
                        {{ __('Submit') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
