<div class="p-4 mt-3">
    <!-- Transaction Modal -->
    <dialog wire:ignore.self class="modal fixed inset-0 z-50  modal-bottom sm:modal-middle bg-opacity-50 flex items-center justify-center" id="transactionModal">
        <div class="bg-white modal-box rounded-lg shadow-lg w-full mt-1 max-w-lg">
            <div class="modal-header px-4">
                <h3 class="text-lg font-semibold" id="transactionModalLabel">{{ $title }}</h3>
            </div>
            <div class="px-4 py-2 mt-5">
                <form wire:submit.prevent="save">
                    <!-- Transaction Code -->
                    <div class="mb-4">
                        <label for="transaction_code" class="block text-sm font-medium">Transaction Code</label>
                        <input type="text" wire:model.defer="transaction.transaction_code" class="input input-bordered w-full mt-1" readonly>
                        @error('transaction.transaction_code')
                            <p class="text-error text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Customer Name -->
                    <div class="mb-4">
                        <label for="customer_name" class="block text-sm font-medium">Customer Name</label>
                        <input type="text" wire:model.defer="transaction.customer_name" class="input input-bordered w-full mt-1">
                        @error('transaction.customer_name')
                            <p class="text-error text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    @if($isEdit)
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium">Status</label>
                        <select wire:model.defer="transaction.status" class="select select-bordered w-full mt-1">
                            <option value="Pending">Pending</option>
                            <option value="Completed">Completed</option>
                            <option value="Canceled">Canceled</option>
                        </select>
                        @error('transaction.status')
                            <p class="text-error text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    @else
                        <input type="hidden" wire:model.defer="transaction.status" value="pending">
                    @endif

                    <!-- Buttons -->
                    <div class="modal-action">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Save</button>
                        <button type="button" onclick="document.getElementById('transactionModal').close()" wire:click="cancel" class="btn btn-error" >
                            <i class="bi bi-x-circle"></i> Cancel
                        </button>
                    </div>
                    <div wire:loading class="text-primary mt-2">Processing...</div>
                </form>
            </div>
        </div>
    </dialog>

    <!-- Transactions Card -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-header flex justify-between items-center mt-3 p-4">
            <div class="flex mx-2 my-2 -mb-1 gap-3">
                <h2 class="text-lg font-bold">Transactions List</h2>
                <select class="border border-gray-300 p-1 rounded" wire:model.live="pagination">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                </select>
            </div>
            <button class="btn btn-primary"  onclick="transactionModal.showModal()">
                <i class="bi bi-plus-circle"></i> Add New Transaction
            </button>
        </div>
        
        <div class="card-body p-4">
            
            <!-- Transactions Table -->
            <table class="table table-compact w-full mt-1">
                <!-- Search Input -->
                <form class="mb-4">
                    <input type="text" wire:model.live="searchTerm" class="input input-bordered w-full mt-1" placeholder="Search Transactions..." />
                </form>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Transaction Code</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Total Amount</th>
                        <th>Bayar</th>
                        <th>Kembalian</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}</td>
                            <td>{{ $transaction->transaction_code }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('Y-m-d') }}</td>
                            <td>{{ $transaction->customer_name }}</td>
                            <td>Rp.{{ number_format($transaction->total_amount) }}</td>
                            <td>Rp.{{ number_format($transaction->bayar) }}</td>
                            <td>Rp.{{ number_format($transaction->kembalian) }}</td>
                            <td>{{ $transaction->status }}</td>
                            <td>
                                <button class="btn btn-info btn-sm" onclick="transactionModal.showModal()" wire:click="edit({{ $transaction->id }})"><i class="bi bi-pencil-square"></i> Edit</button>
                                <button type="button" class="btn btn-error btn-sm" onclick="hapus_transactions({{ $transaction->id }})"><i class="bi bi-trash"></i> Delete</button>
                                <a href="{{ route('transaction.details', $transaction->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-search"></i> Details</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="mt-4">
                <div class="flex justify-center mt-5 mb-5">
                    <div>
                        @if ($transactions->hasPages())
                            <nav role="navigation" aria-label="Pagination Navigation">
                                <span>
                                    @if ($transactions->onFirstPage())
                                        <span class="pr-3 text-zinc-400">Previous</span>
                                    @else
                                        <button class="pr-3" wire:click="previousPage" wire:loading.attr="disabled"
                                            rel="prev">
                                            << Previous
                                        </button>
                                    @endif
                                </span>

                                <span class="px-3 text-white rounded-lg border-solid bg-slate-700">
                                    {{ $transactions->currentPage() }}
                                </span>

                                <span>
                                    @if ($transactions->onLastPage())
                                        <span class="pl-3 text-zinc-400">Next </span>
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
    function hapus_transactions(hapus_id) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary mx-4',
                cancelButton: 'btn btn-error mx-4'
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: 'Delete Transaction Data',
            text: "This data cannot be recovered!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('delete', hapus_id);
                swalWithBootstrapButtons.fire(
                    'Deleted!',
                    'Transaction successfully deleted and stock returned if completed.',
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