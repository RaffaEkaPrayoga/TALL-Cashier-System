<div class="p-4">
    <!-- Payment Card -->
    <div class="card bg-base-100 shadow-xl mt-3">
        <div class="card-header p-4 font-bold text-lg">
            <h4>Payment for Transaction {{ $transaction->customer_name }}</h4>
        </div>
        <div class="card-body p-4">
            <form wire:submit.prevent="processPayment">
                <!-- Total Amount -->
                <div class="mb-4">
                    <label for="total" class="block text-sm font-medium">Total</label>
                    <input type="text" id="total" class="input input-bordered w-full" value="{{ $total }}" readonly>
                </div>
                
                <!-- Payment Amount -->
                <div class="mb-4">
                    <label for="bayar" class="block text-sm font-medium">Pay</label>
                    <input type="number" id="bayar" class="input input-bordered w-full" wire:model.live="bayar" required min="{{ $total }}">
                </div>

                <!-- Change Amount -->
                <div class="mb-4">
                    <label for="kembalian" class="block text-sm font-medium">Change</label>
                    <input type="text" id="kembalian" class="input input-bordered w-full" value="{{ $kembalian }}" readonly>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-full">
                    <i class="bi bi-cash-stack"></i> Submit Payment
                </button>
            </form>
        </div>
    </div>
</div>