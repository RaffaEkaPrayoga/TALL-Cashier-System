<div class="flex justify-center mt-3">
    <div class="w-full max-w-4xl">
        @include('livewire.UpdateOrCreateProducts')

        <div class="card bg-base-100 shadow-md mt-5">
            <div class="card-header flex items-center justify-between p-4 mt-2 b gray-200">
                <div class="flex mx-2 my-2 -mb-1 gap-3">
                    <h2 class="text-lg font-bold">Products List</h2>
                    <select class="border border-gray-300 p-1 rounded" wire:model.live="pagination">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                    </select>
                </div>
                <!-- Button to Open Modal -->
                <button type="button" class="btn btn-primary" onclick="productsModal.showModal()" wire:click="$refresh()">
                    <i class="bi bi-plus-circle"></i> Add New Product
                </button>
            </div>
            <div class="card-body p-4">
                <!-- Search Input -->
                <form>
                    <input type="text" wire:model.live="searchTerm" class="input input-bordered w-full" placeholder="Search products..." />
                </form>

                <table class="table w-full table-zebra">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products_read as $product)
                            <tr wire:key="{{ $product->id }}">
                                <td>{{ ($products_read->currentPage() - 1) * $products_read->perPage() + $loop->iteration }}</td>
                                <td>{{ $product->name }}</td>
                                <td class="prose lg:prose-l">{!! $product->description !!}</td>
                                <td>{{ $product->quantity }}</td>
                                <td>Rp.{{ number_format($product->price) }}</td>
                                <td>{{ $product->category->name }}</td>
                                <td class="flex space-x-2">
                                    <button wire:click="edit({{ $product->id }})" class="btn btn-primary btn-sm" onclick="productsModal.showModal()">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-error btn-sm" onclick="hapus_product({{ $product->id }})">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class=" text-center text-red-500">
                                    <strong>No Products Found!</strong>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="flex justify-center mt-5 mb-5">
                    <div>
                        @if ($products_read->hasPages())
                            <nav role="navigation" aria-label="Pagination Navigation">
                                <span>
                                    @if ($products_read->onFirstPage())
                                        <span class="pr-3 text-zinc-400">Previous</span>
                                    @else
                                        <button class="pr-3" wire:click="previousPage" wire:loading.attr="disabled"
                                            rel="prev">
                                            << Previous
                                        </button>
                                    @endif
                                </span>

                                <span class="px-3 text-white rounded-lg border-solid bg-slate-700">
                                    {{ $products_read->currentPage() }}
                                </span>

                                <span>
                                    @if ($products_read->onLastPage())
                                        <span class="pl-3 text-zinc-400">Next</span>
                                    @else
                                        <button class="pl-3" wire:click="nextPage" wire:loading.attr="disabled"
                                            rel="next">
                                            Next >>
                                        </button>
                                    @endif
                                </span>
                            </nav>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function hapus_product(hapus_id) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary mx-4',
                cancelButton: 'btn btn-error mx-4'
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: 'Hapus Data Produk',
            text: "Data kamu tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Tidak, batal!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('delete', hapus_id);
                swalWithBootstrapButtons.fire(
                    'Hapus!',
                    'Data kamu telah dihapus.',
                    'success'
                );
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire(
                    'Batal',
                    'Data kamu masih aman :)',
                    'error'
                );
            }
        });
    }
</script>