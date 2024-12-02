<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 xl:px-16 xl:ml-64 space-y-6">
        <div class="flex mt-16 items-center justify-between">
            <h2 class="font-semibold text-xl">Add Customer</h2>
        </div>

        <div class="mt-4">
            <form enctype="multipart/form-data" method="POST" action="{{ route('customer.store') }}">
                @csrf
                <div>
                    <x-input-label for="name" :value="__('Customer Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                        :value="old('name')" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="address" :value="__('Address')" />
                    <x-text-input id="address" class="block mt-1 w-full" type="text" name="address"
                        :value="old('address')" />
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="contact" :value="__('Contact')" />
                    <x-text-input id="contact" class="block mt-1 w-full" type="text" name="contact"
                        :value="old('contact')" />
                    <x-input-error :messages="$errors->get('contact')" class="mt-2" />
                </div>
                <x-primary-button class="justify-center w-full mt-4">
                    {{ __('Submit') }}
                </x-primary-button>
            </form>
        </div>
    </div>
</x-app-layout>
