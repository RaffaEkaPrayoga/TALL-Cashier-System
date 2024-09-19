<?php


namespace App\Livewire;


use Livewire\Component;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;


class PaymentTransaction extends Component
{
    public $transaction;
    public $transactionId;
    public $total;
    public $bayar;
    public $kembalian;


    public function mount($transactionId)
    {
        $this->transactionId = $transactionId;
        $this->transaction = Transaction::find($transactionId);


        // Pastikan transaksi ditemukan
        if ($this->transaction) {
            $this->total = $this->transaction->total_amount;
            $this->bayar = 0;
            $this->kembalian = 0;
        } else {
            // Redirect atau handling error jika transaksi tidak ditemukan
            session()->flash('error', 'Transaksi tidak ditemukan.');
            return redirect('/transactions');
        }
    }


    public function updatedBayar()
    {
        // Hitung kembalian jika bayar lebih besar atau sama dengan total
        if ($this->bayar >= $this->total) {
            $this->kembalian = $this->bayar - $this->total;
        } else {
            $this->kembalian = 0;
        }
    }


    public function processPayment()
    {
        // Validasi input bayar
        $this->validate([
            'bayar' => 'required|numeric|min:' . $this->total,
        ]);


        DB::beginTransaction();
        try {
            // Update status transaksi menjadi "Complete"
            $transaction = Transaction::find($this->transactionId);
            if ($transaction) {
                $transaction->update([
                    'bayar' => $this->bayar,
                    'kembalian' => $this->kembalian,
                    'status' => 'Completed',
                ]);


                // Kurangi stok produk
                foreach ($transaction->transactionDetails as $detail) {
                    $product = $detail->product;
                    if ($product) {
                        $product->update(['quantity' => $product->quantity - $detail->quantity]);
                    }
                }
            }


            DB::commit();
            session()->flash('message', 'Pembayaran berhasil diselesaikan.');
            return redirect('/transactions'); // Redirect setelah pembayaran selesai
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat proses pembayaran.');
        }
    }



    public function render()
    {
        return view('livewire.payment-transaction');
    }
}