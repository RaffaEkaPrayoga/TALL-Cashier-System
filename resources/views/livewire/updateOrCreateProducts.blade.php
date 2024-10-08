<!-- Modal Structure -->
<dialog wire:ignore.self class="modal fixed inset-0 z-50  modal-bottom sm:modal-middle bg-opacity-50 flex items-center justify-center" id="productsModal">
    <div class="modal-body modal-box p-4">
        <div class="modal-header flex justify-between items-center p-4 border-b border-gray-200">
            <h5 class="text-lg font-semibold" id="productsModalLabel">{{ $title }}</h5>
            <button type="button" class="btn btn-close" wire:click="cancel">
                 <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            </div>
            <form wire:submit.prevent="save" enctype="multipart/form-data">
                <div class="mb-3">
                    <!-- Product Name -->
                    <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                    <input type="text" wire:model.defer="product.name" id="name" class="input input-bordered w-full mt-1"
                           :class="{'border-red-500': errors['product.name']}">
                    @error('product.name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror

                    <!-- Product Description -->
                    <label for="description" class="block text-sm font-medium text-gray-700 mt-4">Product Description</label>
                    <div class="prose lg:prose-xl" wire:ignore x-data="{ content: @entangle('product.description') }" x-init="$nextTick(() => {
                        ClassicEditor.create($refs.description)
                            .then(newEditor => {
                                editor = newEditor;
                                editor.model.document.on('change:data', () => {
                                    content = editor.getData();
                                });
                    
                                $watch('content', value => {
                                    if (value !== editor.getData()) {
                                        editor.setData(value);
                                    }
                                });
                            });
                        })">
                            <textarea x-ref="description" x-model="content" class="textarea textarea-bordered w-full @error('product.description') textarea-error @enderror"></textarea>
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

                        <label for="image" class="mb-2 block text-sm font-medium text-gray-700 mt-4">Product Image</label>
                        @if ($product['image'] && is_string($product['image']))
                            <img src="{{ Storage::url($product['image']) }}" alt="Product Image" width="30%" class="mt-2">
                        @endif
                        <input type="file" class="mt-1 file-input file-input-bordered w-full @error('product.image') input-error @enderror"
                            id="image" wire:model.defer="product.image">
                        @error('product.image')
                            <div class="text-red-500 mt-1">{{ $message }}</div>
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
</dialog>

<!-- CKEditor Script -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>