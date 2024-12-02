<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 xl:px-16 xl:ml-64 space-y-6">
        <div class="flex mt-16 items-center justify-between">
            <h2 class="font-semibold text-xl">Edit Product</h2>
        </div>

        <div class="mt-4" x-data="{ imageUrl: '/uploads/{{ $product->image }}' }">
            <form enctype="multipart/form-data" method="POST" action="{{ route('product.update', $product) }}"
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
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                            :value="$product->name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="category" :value="__('Category')" />
                        <x-text-input id="category" class="block mt-1 w-full" type="text" name="category"
                            :value="$product->category" />
                        <x-input-error :messages="$errors->get('category')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="unit" :value="__('Unit')" />
                        <x-text-input id="unit" class="block mt-1 w-full" type="text" name="unit"
                            :value="$product->unit" />
                        <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="selling_price" :value="__('Selling Price')" />
                        <x-text-input id="selling_price" class="block mt-1 w-full" type="text" name="selling_price"
                            :value="$product->selling_price" x-mask:dynamic="$money($input, ',')" />
                        <x-input-error :messages="$errors->get('selling_price')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="purchase_price" :value="__('Purchase Price')" />
                        <x-text-input id="purchase_price" class="block mt-1 w-full" type="text" name="purchase_price"
                            :value="$product->purchase_price" x-mask:dynamic="$money($input, ',')" />
                        <x-input-error :messages="$errors->get('purchase_price')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="published" :value="__('Published')" />
                        <select id="published" name="published"
                            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="" disabled selected>-- Select Published --</option>
                            <option value="1" {{ $product->published ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ !$product->published ? 'selected' : '' }}>No</option>
                        </select>
                        <x-input-error :messages="$errors->get('published')" class="mt-2" />
                    </div>
                    <x-primary-button class="justify-center w-full mt-4">
                        {{ __('Submit') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
