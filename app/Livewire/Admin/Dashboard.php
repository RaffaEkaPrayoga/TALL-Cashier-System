<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Livewire\Component;

class Dashboard extends Component
{
    public $categoriesCount;
    public $productsCount;
    public $transactionsCount;
    public $transactionDetailsCount;
    public $customerData;

    public function mount()
    {
        $this->categoriesCount = Category::count();
        $this->productsCount = Product::count();
        $this->transactionsCount = Transaction::count();
        $this->transactionDetailsCount = TransactionDetail::count();
        $this->customerData = Transaction::select('customer_name', 'total_amount', 'bayar')->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
