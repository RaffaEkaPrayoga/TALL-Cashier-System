<!-- Modal Structure -->
<dialog wire:ignore.self class="modal fixed inset-0 z-50  modal-bottom sm:modal-middle bg-opacity-50 flex items-center justify-center" id="productsModal">
    <div class="modal-dialog bg-white rounded shadow-lg">
        <div class="modal-content">
            <div class="modal-header flex justify-between items-center p-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold" id="productsModalLabel">{{ $title }}</h5>
                <button type="button" class="btn btn-close" wire:click="cancel">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                </button>
            </div>
            <div class="modal-body p-4">
                <form wire:submit.prevent="save">
                    <div class="mb-4">
                        <!-- Product Name -->
                        <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                        <input type="text" wire:model.defer="product.name" id="name" class="input input-bordered w-full mt-1"
                               :class="{'border-red-500': errors['product.name']}">
                        @error('product.name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror

                        <!-- Product Description -->
                        <label for="description" class="block text-sm font-medium text-gray-700 mt-4">Product Description</label>
                        <div class="prose lg:prose-l" wire:ignore x-data x-init="
                            ClassicEditor.create($refs.editor)
                                .then(newEditor => {
                                    editor = newEditor;
                                    editor.setData(@this.get('product.description')); // Set initial data
                                    editor.model.document.on('change:data', () => {
                                        @this.set('product.description', editor.getData()); // Correct binding to update Livewire
                                    });
                                })
                                .catch(error => {
                                    console.error(error);
                                })
                        ">
                            <textarea x-ref="editor" class=" form-textarea mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                        </div>
                        @error('product.description')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror

                        <!-- Product Quantity -->
                        <label for="quantity" class="mb-2 block text-sm font-medium text-gray-700 mt-4">Product Quantity</label>
                        <input type="number" wire:model.defer="product.quantity" id="quantity" class="input input-bordered w-full mt-1"
                               :class="{'border-red-500': errors['product.quantity']}">
                        @error('product.quantity')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror

                        <!-- Product Price -->
                        <label for="price" class="block text-sm font-medium text-gray-700 mt-4">Product Price</label>
                        <input type="number" step="0.01" wire:model.defer="product.price" id="price" class="input input-bordered w-full mt-1"
                               :class="{'border-red-500': errors['product.price']}">
                        @error('product.price')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror

                        <!-- Product Category -->
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mt-4">Product Category</label>
                        <select wire:model.defer="product.category_id" id="category_id" class="select select-bordered w-full mt-1"
                                :class="{'border-red-500': errors['product.category_id']}">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('product.category_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-floppy"></i> Save
                        </button>
                        <button type="button" onclick="document.getElementById('productsModal').close()" wire:click="cancel" class="btn btn-error" >
                            <i class="bi bi-x-circle"></i> Cancel
                        </button>
                    </div>
                    <div wire:loading class="text-blue-500 mt-2">Processing...</div>
                </form>
            </div>
        </div>
    </div>
</dialog>

<!-- CKEditor Script -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Livewire.on('editProduct', () => {
            if (window.editor) {
                editor.setData(@this.get('product.description')); // Set CKEditor data from Livewire property
            }
        });
        

        Livewire.on('addProduct', () => {
            if (window.editor) {
                // Clear CKEditor value when adding a new product
                editor.setData('');
            }
        });
    });
</script>