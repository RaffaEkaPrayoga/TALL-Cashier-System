<?php


namespace App\Livewire;


use Livewire\Component;
use App\Models\Product;
use App\Models\TransactionDetail;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;


class TransactionDetails extends Component
{
    public $transactionId;
    public $products = [];
    public $productsList = [];
    public $transactionStatus;


    protected $rules = [
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
        'products.*.stok' => 'required|integer|min:0',
        'products.*.unit_price' => 'required|integer|min:0',
        'products.*.subtotal' => 'required|integer|min:0',
    ];


    public function mount($transactionId)
    {
        $this->transactionId = $transactionId;
        $this->products = [];
        $this->productsList = Product::all();


        $transaction = Transaction::find($transactionId);
        $this->transactionStatus = $transaction ? $transaction->status : null;
    }


    public function addForm()
    {
        $this->products[] = ['product_id' => '', 'quantity' => 1, 'stok' => 0, 'unit_price' => 0, 'subtotal' => 0];
    }
   

    public function removeProduct($key)
    {
        unset($this->products[$key]);
        $this->products = array_values($this->products);
    }


    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        // Update stock and unit price when product_id changes
        if (strpos($propertyName, 'products.') === 0 && strpos($propertyName, 'product_id') !== false) {
            $key = explode('.', $propertyName)[1];
            $productId = $this->products[$key]['product_id'];


            if (!empty($productId)) {
                $product = Product::find($productId);
                if ($product) {
                    $this->products[$key]['stok'] = $product->quantity;
                    $this->products[$key]['unit_price'] = $product->price;
                    // Recalculate subtotal if quantity is set
                    $this->products[$key]['subtotal'] = (int) $this->products[$key]['quantity'] * (int) $product->price;
                }
            }
        }


        // Update subtotal when quantity changes
        if (strpos($propertyName, 'products.') === 0 && strpos($propertyName, 'quantity') !== false) {
            $key = explode('.', $propertyName)[1];
            $productId = $this->products[$key]['product_id'];
            $quantity = $this->products[$key]['quantity'];


            if (!empty($productId)) {
                $product = Product::find($productId);
                if ($product) {
                    // Ensure quantity does not exceed stock
                    if ($quantity > $product->quantity) {
                        $this->products[$key]['quantity'] = $product->quantity;
                    } else {
                        $this->products[$key]['quantity'] = $quantity; // Use original quantity if valid
                    }

                    // Recalculate subtotal based on adjusted quantity
                    $this->products[$key]['subtotal'] = $this->products[$key]['quantity'] * $this->products[$key]['unit_price'];
                }
            }

        }
    }


    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            foreach ($this->products as $product) {
                TransactionDetail::updateOrCreate(
                    ['transaction_id' => $this->transactionId, 'product_id' => $product['product_id']],
                    [
                        'quantity' => $product['quantity'],
                        'unit_price' => $product['unit_price'],
                        'subtotal' => $product['subtotal'],
                    ]
                );
            }


            $totalAmount = TransactionDetail::where('transaction_id', $this->transactionId)->sum('subtotal');
            Transaction::where('id', $this->transactionId)->update(['total_amount' => $totalAmount]);


            DB::commit();


            $this->products = [];

            //SweetAlert Add Atau Edit
            session()->flash('message', 'Transaction Details Successfully Updated.');


            return redirect()->to(route('transaction.details', $this->transactionId));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'An error occurred while saving transaction details.');
        }
    }



    public function removeProductDetail($detailId)
    {
        // Cari dan hapus transaction detail berdasarkan id
        $detail = TransactionDetail::find($detailId);


        if ($detail) {
            $detail->delete();


            // Perbarui total_amount di tabel Transaction setelah penghapusan
            $totalAmount = TransactionDetail::where('transaction_id', $this->transactionId)->sum('subtotal');
            Transaction::where('id', $this->transactionId)->update(['total_amount' => $totalAmount]);
        }
    }

    public function render()
    {
        $transactionDetails = TransactionDetail::where('transaction_id', $this->transactionId)
            ->with('product') 
            ->get();


        // Total subtotal dari seluruh transaction details
        $totalSubtotal = $transactionDetails->sum('subtotal');


        return view('livewire.transaction-details', [
            'transactionDetails' => $transactionDetails,
            'totalSubtotal' => $totalSubtotal,  // Mengirimkan total subtotal ke view
        ]);
    }
}