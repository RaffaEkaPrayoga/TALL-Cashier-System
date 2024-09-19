<div class="p-4">
    <!-- Detail Transactions Card -->
    <div class="card bg-base-100 shadow-xl mt-2">
        <div class="card-header p-4 font-bold text-lg">
            Detail Transactions
        </div>
        <div class="card-body p-4">
            <!-- Add New Product Form -->
            <form wire:submit.prevent="save">
                @foreach($products as $key => $product)
                    <div class="mb-4 flex gap-5">
                        <!-- Product Selection -->
                        <div class="mb-4">
                            <label for="product_id_{{ $key }}" class="block text-sm font-medium">Product</label>
                            <select wire:model.live="products.{{ $key }}.product_id" class="select select-bordered w-full">
                                <option value="">Select Product</option>
                                @foreach($productsList as $prod)
                                    <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                @endforeach
                            </select>
                            @error('products.' . $key . '.product_id')
                                <p class="text-error text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stok Produk -->
                        <div class="mb-4">
                            <label for="stok_{{ $key }}" class="block text-sm font-medium">Stok Produk</label>
                            <input type="number" wire:model.live="products.{{ $key }}.stok" class="input input-bordered w-full" readonly>
                            @error('products.' . $key . '.stok')
                                <p class="text-error text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Unit Price -->
                        <div class="mb-4">
                            <label for="unit_price_{{ $key }}" class="block text-sm font-medium">Unit Price</label>
                            <input type="number" wire:model.live="products.{{ $key }}.unit_price" class="input input-bordered w-full" readonly>
                            @error('products.' . $key . '.unit_price')
                                <p class="text-error text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quantity -->
                        <div class="mb-4">
                            <label for="quantity_{{ $key }}" class="block text-sm font-medium">Quantity</label>
                            <input type="number" wire:model.live="products.{{ $key }}.quantity" class="input input-bordered w-full" min="1">
                            @error('products.' . $key . '.quantity')
                                <p class="text-error text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subtotal -->
                        <div class="mb-4">
                            <label for="subtotal_{{ $key }}" class="block text-sm font-medium">Subtotal</label>
                            <input type="number" wire:model.live="products.{{ $key }}.subtotal" class="input input-bordered w-full" readonly>
                            @error('products.' . $key . '.subtotal')
                                <p class="text-error text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remove Button -->
                        <div class="my-5">
                            <button type="button" class="btn btn-error" wire:click="removeProduct({{ $key }})">
                                Remove
                            </button>
                        </div>
                    </div>
                @endforeach

                @if ($transactionStatus == 'Pending')
                    <!-- Add New Product Button -->
                    <button type="button" class="btn btn-warning mb-3" wire:click="addForm">
                        <span class="text-white"><i class="bi bi-cart-plus"></i> Add New Product</span>
                    </button>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-success mb-3 px-5">
                        <i class="bi bi-floppy"></i> Save
                    </button>
                @endif
                <div wire:loading class="text-primary mt-2">Processing...</div>
            </form>

            <!-- Transaction Details Table -->
            <div class="mt-4">
                <table class="table table-compact w-full">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Subtotal</th>
                            @if ($transactionStatus == 'Pending')
                                <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactionDetails as $detail)
                            <tr>
                                <td>{{ optional($detail->product)->name }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>Rp.{{ number_format($detail->unit_price) }}</td>
                                <td>Rp.{{ number_format($detail->subtotal) }}</td>
                                @if ($transactionStatus == 'Pending')
                                    <td>
                                        <button type="button" class="btn btn-error btn-sm" onclick="hapus_details({{ $detail->id }})">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No products found for this transaction.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Total Amount and Buttons -->
            <div class="mt-4 flex justify-between">
                <a href="{{ route('transactions.index') }}" class="btn btn-secondary"><i class="bi bi-skip-backward-fill"></i> Kembali</a>
                <div class="flex gap-2">
                    @if ($transactionStatus == 'Pending')
                    <a href="{{ route('payment-page', ['transactionId' => $transactionId]) }}" class="btn btn-primary"><i class="bi bi-cash-coin"></i> Bayar</a>
                    @endif
                    <h5 class="text-lg font-semibold my-2">Total Amount: Rp.{{ number_format($totalSubtotal) }}</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function hapus_details(hapus_id) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary mx-4',
                cancelButton: 'btn btn-error mx-4'
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: 'Delete Product Data',
            text: "This data cannot be recovered!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('removeProductDetail', hapus_id);
                swalWithBootstrapButtons.fire(
                    'Deleted!',
                    'Product successfully deleted and stock returned if completed.',
                    'success'
                );
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'Your data is safe :)',
                    'error'
                );
            }
        });
    }
</script>