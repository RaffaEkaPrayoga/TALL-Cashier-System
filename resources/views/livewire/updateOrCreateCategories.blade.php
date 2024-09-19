<dialog wire:ignore.self id="categoriesModal" class="modal fixed inset-0 z-50  modal-bottom sm:modal-middle bg-opacity-50">
    <div class="bg-white modal-box rounded-lg shadow-lg w-full max-w-lg">
        <div class="px-4 py-2 flex justify-between items-center p-4 border-b border-gray-200">
            <h5 class="text-lg text-danger font-semibold">{{ $title }}</h5>
            <button type="button" class="btn btn-ghost" wire:click="cancel" data-bs-dismiss="modal">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="px-4 py-2 mt-5">
            <form wire:submit.prevent="save" enctype="multipart/form-data">
                <!-- Single categories Form for Edit -->
                @if($isEdit)
                    <div class="mb-4">
                        <!-- categories Name -->
                        <label for="name" class="block text-sm font-medium text-gray-700">Category Name</label>
                        <input type="text" wire:model.defer="category.name" class="input input-bordered w-full mt-1"
                               :class="{'input-error': errors['categories.name']}">
                        @error('categories.name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror

                        <!-- categories Description -->
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-700 mt-4">Category Description</label>
                        <div class="prose lg:prose-xl" wire:ignore x-data="{ description: '{{ $category['description'] ?? '' }}' }" x-init="
                            ClassicEditor.create($refs.editor)
                                .then(newEditor => {
                                    editor = newEditor;
                                    editor.model.document.on('change:data', () => {
                                        @this.set('category.description', editor.getData());
                                    });
                                })
                                .catch(error => {
                                    console.error(error);
                                })
                        ">
                            <textarea x-ref="editor" class="textarea textarea-bordered w-full">{{ $category['description'] ?? '' }}</textarea>
                        </div>
                        @error('categories.description')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                @else
                    <!-- Loop through categories for add mode -->
                    @foreach($categories as $key => $prod)
                        <div class="mb-4">
                            <!-- categories Name -->
                            <label for="name_{{ $key }}" class="block text-sm font-medium text-gray-700">Category Name</label>
                            <input type="text" wire:model.defer="categories.{{ $key }}.name" class="input input-bordered w-full mt-1"
                                   :class="{'input-error': errors['categories.{{ $key }}.name']}">
                            @error('categories.' . $key . '.name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <!-- categories Description -->
                            <label for="description_{{ $key }}" class="mb-2  block text-sm font-medium text-gray-700 mt-4">Category Description</label>
                            <div class="prose lg:prose-l" wire:ignore x-data="{ description: '{{ $categories['description'] ?? '' }}' }" x-init="
                                ClassicEditor.create($refs.editor_{{ $key }})
                                    .then(newEditor => {
                                        editor = newEditor;
                                        editor.model.document.on('change:data', () => {
                                            @this.set('categories.{{ $key }}.description', editor.getData());
                                        });
                                    })
                                    .catch(error => {
                                        console.error(error);
                                    })
                            ">
                                <textarea x-ref="editor_{{ $key }}" class="textarea textarea-bordered w-full mt-1">{{ $categories['description'] ?? '' }}</textarea>
                            </div>
                            @error('categories.' . $key . '.description')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <!-- Remove Button -->
                            <button type="button" class="btn btn-error mt-2" wire:click="removeCategories({{ $key }})">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </div>
                    @endforeach
                @endif

                <!-- Conditional Add New categories Button -->
                @if(!$isEdit)
                    <button type="button" class="btn btn-info mb-3" wire:click="addForm">
                        <i class="bi bi-plus-circle"></i>Add New categories
                    </button>
                @endif

                <!-- Submit Button -->
                <div class="flex justify-end mt-4">
                    <button type="submit" class="btn btn-success mr-2"><i class="bi bi-floppy"></i> Save</button>
                    <button type="button" onclick="document.getElementById('categoriesModal').close()" wire:click="cancel" class="btn btn-error" >
                            <i class="bi bi-x-circle"></i> Cancel
                    </button>
                </div>
                <div wire:loading class="text-blue-500 mt-2">Processing...</div>
            </form>
        </div>
    </div>
</dialog>


<!-- CKEditor Script -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>