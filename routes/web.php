<?php


use Illuminate\Support\Facades\Route;
use App\Livewire\Categories;
use App\Livewire\Products;
use App\Livewire\Transactions;
use App\Livewire\TransactionDetails;
use App\Livewire\PaymentTransaction;


Route::get('/', function () {
    return redirect()->route('transactions.index');
});


Route::get('/categories', Categories::class)->name('livewire.categories');
Route::get('/products', Products::class)->name('livewire.products');
Route::get('/transactions', Transactions::class)->name('transactions.index');
Route::get('/transaction/{transactionId}/details', TransactionDetails::class)->name('transaction.details');
Route::get('/payment-transaction/{transactionId}', PaymentTransaction::class)->name('payment-page');
Route::get('/transaction/receipt/{id}', [Transactions::class, 'generateReceipt'])->name('transaction.receipt');