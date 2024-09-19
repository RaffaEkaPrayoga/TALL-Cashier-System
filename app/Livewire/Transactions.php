<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use Livewire\WithPagination;


class Transactions extends Component
{
    use WithPagination;

    public $isEdit = false;
    public $transaction = [];
    public $title = 'Add New Transaction';
    public $shouldCloseModal = false;
    public $searchTerm = '';
    public $pagination = 5;

    protected $rules = [
        'transaction.transaction_code' => 'required|string|max:255|unique:transactions,transaction_code',
        'transaction.customer_name' => 'nullable|string|max:255',
        'transaction.status' => 'required|string|max:255',
        'transaction.bayar' => 'nullable|integer|min:0',  // Payment validation
        'transaction.kembalian' => 'nullable|integer|min:0', // Change validation
    ];


    public function mount()
    {
        $this->resetFields();
    }


    public function resetFields()
    {
        $this->title = 'Add New Transaction';
        $this->transaction = [
            'transaction_code' => $this->generateTransactionCode(),
            'customer_name' => '',
            'status' => 'Pending',
            'total_amount' => 0, // Default value for total_amount
            'bayar' => 0,        // Default value for bayar (payment)
            'kembalian' => 0     // Default value for kembalian (change)
        ];
        $this->isEdit = false;
    }


    public function generateTransactionCode()
    {
        return 'TRX-' . strtoupper(uniqid());
    }


    public function save()
    {
        if ($this->isEdit) {
            // Editing an existing transaction
            $transaction = Transaction::findOrFail($this->transaction['id']);

            // Jika status diubah menjadi selain "Complete", kembalikan stok dan reset bayar/kembalian
            if ($transaction->status === 'Completed' && $this->transaction['status'] !== 'Completed') {
                // Kembalikan stok produk dalam detail transaksi
                foreach ($transaction->transactionDetails as $detail) {
                    $product = $detail->product;
                    if ($product) {
                        $product->quantity += $detail->quantity; // Kembalikan jumlah stok produk
                        $product->save();
                    }
                }

                // Reset bayar dan kembalian
                $this->transaction['bayar'] = 0;
                $this->transaction['kembalian'] = 0;
            }


            $this->transaction['transaction_code'] = $transaction->transaction_code;
            $this->transaction['kembalian'] = max(0, $this->transaction['bayar'] - $this->transaction['total_amount']);
            $transaction->update($this->transaction);
            //sweetalert edit
            session()->flash('message', 'Transaction Successfully Updated.');
        } else {
            // Creating a new transaction
            $this->validate(); // Validate unique transaction code only for new transactions
            $this->transaction['total_amount'] = 0; // Set default value
            $this->transaction['kembalian'] = max(0, $this->transaction['bayar'] - $this->transaction['total_amount']); // Calculate change
            Transaction::create($this->transaction);
            //sweetalert add
            session()->flash('message', 'Transaction Successfully Created.');
        }


        $this->resetFields();
        $this->shouldCloseModal = true;

        return redirect()->route('transactions.index');
    }



    public function edit($id)
    {
        $this->title = 'Edit Transaction';
        $transaction = Transaction::findOrFail($id);
        $this->transaction = $transaction->toArray();
        $this->isEdit = true;
    }


    public function delete($id)
    {
        // Cari transaksi berdasarkan ID
        $transaction = Transaction::find($id);


        // Cek apakah transaksi berstatus "Complete"
        if ($transaction->status === 'Completed') {
            foreach ($transaction->transactionDetails as $detail) {
                $product = $detail->product;
                if ($product) {
                    $product->quantity += $detail->quantity;
                    $product->save();
                }
            }
        }
        $transaction->delete();
    }

    public function cancel()
    {
        $this->resetFields();
        if ($this->isEdit) {
            $this->reset();
        }
    }

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }


    public function render()
    {
        $transactions = Transaction::where('transaction_code', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('customer_name', 'like', '%' . $this->searchTerm . '%')
            ->latest()
            ->paginate($this->pagination);


        return view('livewire.transactions', ['transactions' => $transactions]);
    }
}