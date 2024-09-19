<div class="flex flex-col items-center mt-3">
    <div class="w-full max-w-4xl">
        @include('livewire.updateOrCreateCategories')

        <div class="card bg-base-100 shadow-md mt-5">
            <div class="card-header px-4 flex justify-between items-center">
                <div class="flex mx-2 my-12 gap-3">
                    <span class="text-lg font-semibold">Categories List</span>
                    <select class="border border-gray-300 p-1 rounded" wire:model.live="pagination">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                    </select>
                </div>
                <button class="btn btn-primary"  onclick="categoriesModal.showModal()">
                    <i class="bi bi-plus-circle"></i> Add New Categories
                </button>
            </div>

            <div class="card-body px-4 py-2 -mt-6">
                <!-- Search Input -->
                <form>
                    <input type="text" wire:model.live="searchTerm" class="input input-bordered mb-3 w-full" placeholder="Search categories..." />
                </form>
                <table class="table w-full table-zebra">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories_read as $categories)
                        <tr>
                            <td>{{ ($categories_read->currentPage() - 1) * $categories_read->perPage() + $loop->iteration }}</td>
                            <td>{{ $categories->name }}</td>
                            <td class="prose lg:prose-l">{!! $categories->description !!}</td>
                            <td>
                                <button wire:click="edit({{ $categories->id }})" class="btn btn-primary btn-sm" onclick="categoriesModal.showModal()">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                                <button type="button" class="btn btn-error btn-sm" onclick="hapus_category({{ $categories->id }})">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="flex justify-center mt-5 mb-5">
                    <div>
                        @if ($categories_read->hasPages())
                            <nav role="navigation" aria-label="Pagination Navigation">
                                <span>
                                    @if ($categories_read->onFirstPage())
                                        <span class="pr-3 text-zinc-400">Previous</span>
                                    @else
                                        <button class="pr-3" wire:click="previousPage" wire:loading.attr="disabled"
                                            rel="prev">
                                            << Previous
                                        </button>
                                    @endif
                                </span>

                                <span class="px-3 text-white rounded-lg border-solid bg-slate-700">
                                    {{ $categories_read->currentPage() }}
                                </span>

                                <span>
                                    @if ($categories_read->onLastPage())
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
    function hapus_category(hapus_id) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary mx-4',
                cancelButton: 'btn btn-error mx-4'
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: 'Hapus Data Kategori',
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

<!-- Remove Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('set-editor-data', data => {
            const editorInstance = editorInstances[0]; // assuming single editor for edit
            if (editorInstance) {
                editorInstance.setData(data.description);
            }
        });
    });
</script>