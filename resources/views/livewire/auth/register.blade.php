<div class="flex items-center justify-center min-h-screen bg-base-200">
    <div class="card w-full max-w-lg shadow-lg bg-base-100">
        <div class="card-body">
            <h5 class="text-2xl font-bold text-center mb-6">
                <i class="bi bi-person-plus"></i> REGISTER
            </h5>
            <form wire:submit.prevent="store">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">NAMA LENGKAP</span>
                        </label>
                        <input type="text" wire:model.lazy="name" 
                            class="input input-bordered w-full @error('name') input-error @enderror" 
                            placeholder="Nama Lengkap">
                        @error('name')
                        <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">EMAIL</span>
                        </label>
                        <input type="email" wire:model.lazy="email"
                            class="input input-bordered w-full @error('email') input-error @enderror"
                            placeholder="Alamat Email">
                        @error('email')
                        <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">PASSWORD</span>
                        </label>
                        <input type="password" wire:model.lazy="password"
                            class="input input-bordered w-full @error('password') input-error @enderror" 
                            placeholder="Password">
                        @error('password')
                        <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">KONFIRMASI PASSWORD</span>
                        </label>
                        <input type="password" wire:model.lazy="password_confirmation"
                            class="input input-bordered w-full" placeholder="Konfirmasi Password">
                    </div>
                </div>

                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary w-full">REGISTER</button>
                </div>
            </form>
        </div>
    </div>
</div>
