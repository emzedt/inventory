<!-- resources/views/partials/modal.blade.php -->
<div id="{{ $modalId }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-md shadow-lg w-1/3">
        <h3 class="text-lg font-semibold">{{ $title }}</h3>
        <div class="mt-4 flex justify-end space-x-4">
            <button onclick="closeModal('{{ $modalId }}')"
                class="px-4 py-2 text-gray-600 hover:bg-gray-200 rounded-md">Cancel</button>
            <form action="{{ $action }}" method="POST" id="{{ $formId }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded-md">Yes,
                    Delete</button>
            </form>
        </div>
    </div>
</div>

<script>
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>
