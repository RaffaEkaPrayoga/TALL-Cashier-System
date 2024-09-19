<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_code',  'total_amount', 'customer_name', 'bayar', 'kembalian', 'status'];

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

}