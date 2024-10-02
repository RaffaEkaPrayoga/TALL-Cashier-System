<div class="flex items-center justify-center min-h-screen bg-base-200">
    <div class="glass card w-full max-w-sm shadow-lg bg-base-100">
        <div class="card-body">
            <h5 class="text-2xl font-bold text-center mb-6">
                <i class="bi bi-person-circle"></i> LOGIN
            </h5>
            <form wire:submit.prevent="login">
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">EMAIL</span>
                    </label>
                    <input type="text" wire:model.lazy="email"
                        class="input input-bordered w-full @error('email') input-error @enderror"
                        placeholder="Alamat Email">
                    @error('email')
                    <span class="text-error text-sm">{{ $message }}</span>
                    @enderror
                </div>
    
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">PASSWORD</span>
                    </label>
                    <input type="password" wire:model.lazy="password"
                        class="input input-bordered w-full @error('password') input-error @enderror" placeholder="Password">
                    @error('password')
                    <span class="text-error text-sm">{{ $message }}</span>
                    @enderror
                </div>
    
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary w-full">LOGIN</button>
                </div>
            </form>
            <p class="text-center mt-4">
                Belum punya akun? 
                <a href="{{ route('auth.register') }}" class="text-primary font-bold">Daftar Sekarang</a>
            </p>
        </div>
    </div>
</div>