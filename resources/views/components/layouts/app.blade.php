<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistem Kasir Sederhana' }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="bg-gray-100">
    <div class="container min-h-screen">
        <nav class="bg-base-100 shadow-[0_4px_10px_rgba(0,255,255,0.5)] ">
            <div class="container mx-auto px-4 py-5 flex justify-between items-center">
                <!-- Logo or Brand Name -->
                <a class="text-xl font-semibold" href="{{ route('transactions.index') }}">Kasir Sederhana</a>
    
                <!-- Hamburger Menu (for small screens) -->
                <div class="block lg:hidden">
                    <button class="btn btn-primary" id="hamburger-button">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                    </button>
                </div>
    
                <!-- Navigation Menu (for large screens) -->
                <div class="hidden lg:flex flex-grow justify-end items-center space-x-4">
                    <a href="{{ route('transactions.index') }}" class="{{ request()->routeIs('transactions.index') ? 'text-primary' : '' }}" wire:navigate>Transactions</a>
                    <a href="{{ route('livewire.categories') }}" class="{{ request()->routeIs('livewire.categories') ? 'text-primary' : '' }}" wire:navigate>Categories</a>
                    <a href="{{ route('livewire.products') }}" class="{{ request()->routeIs('livewire.products') ? 'text-primary' : '' }}" wire:navigate>Products</a>
                </div>
            </div>
    
            <!-- Sidebar Menu (for small screens) -->
            <div id="sidebar-menu" class="lg:hidden fixed top-0 left-0 w-64 h-full bg-base-100 shadow-lg z-50 transform -translate-x-full transition-transform duration-300">
                <div class="p-4">
                    <button class="btn btn-primary mb-4" id="close-sidebar">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <ul class="menu p-2">
                        <li><a href="{{ route('transactions.index') }}" class="{{ request()->routeIs('transactions.index') ? 'text-primary' : '' }}" wire:navigate>Transactions</a></li>
                        <li><a href="{{ route('livewire.categories') }}" class="{{ request()->routeIs('livewire.categories') ? 'text-primary' : '' }}" wire:navigate>Categories</a></li>
                        <li><a href="{{ route('livewire.products') }}" class="{{ request()->routeIs('livewire.products') ? 'text-primary' : '' }}" wire:navigate>Products</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        {{ $slot }}
    </div>

    @stack('scripts') <!-- Tambahkan baris ini untuk skrip tambahan -->
    <!-- Include CKEditor -->
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session()->has('message'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('message') }}',
            });
        });
    </script>
    @endif
    <script>
        document.getElementById('hamburger-button').addEventListener('click', () => {
            document.getElementById('sidebar-menu').classList.toggle('-translate-x-full');
        });

        document.getElementById('close-sidebar').addEventListener('click', () => {
            document.getElementById('sidebar-menu').classList.add('-translate-x-full');
        });
    </script>
    @livewireScripts
</body>
</html>