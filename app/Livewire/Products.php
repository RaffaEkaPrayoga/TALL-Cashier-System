<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Livewire\WithPagination;


class Products extends Component
{
    use WithPagination;    

    public $product = [];
    public $isEdit = false;
    public $title = 'Add New Product';
    public $shouldCloseModal = false;
    public $searchTerm = '';
    public $pagination = 5;

    protected $rules = [
        'product.name' => 'required|string|max:255',
        'product.description' => 'required|string|max:1000',
        'product.quantity' => 'required|integer|min:0',
        'product.price' => 'required|numeric|min:0',
        'product.category_id' => 'required|exists:categories,id',
    ];

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->resetFields();
    }


    public function resetFields()
    {
        $this->title = 'Add New Product';
        $this->product = ['name' => '', 'description' => '', 'quantity' => 0, 'price' => 0, 'category_id' => ''];
        $this->isEdit = false;
        $this->dispatch('addProduct');
    }


    public function save()
    {
        $this->validate();


        if ($this->isEdit) {
            Product::updateOrCreate(
                ['id' => $this->product['id']],
                $this->product
            );
            //sweetalert edit
            session()->flash('message', 'Product Successfully Updated.');
        } else {
            Product::create($this->product);
            //sweetalert add
            session()->flash('message', 'Product Successfully Added.');
        }


        $this->resetFields();
        $this->shouldCloseModal = true;
        return redirect('/products');
    }


    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->title = 'Edit Product';
        $this->product = [
            'id' => $id,
            'name' => $product->name,
            'description' => $product->description, // Cek apakah ada data
            'quantity' => $product->quantity,
            'price' => $product->price,
            'category_id' => $product->category_id,
        ];
        $this->isEdit = true;
        $this->dispatch('editProduct');
    }




    public function delete($id)
    {
        Product::find($id)->delete();
    }


    public function cancel()
    {
        $this->resetFields();
        if ($this->isEdit) {
            $this->reset();
        }
    }


    public function render()
    {
        $products_read = Product::where('name', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('description', 'like', '%' . $this->searchTerm . '%')
            ->latest()
            ->paginate($this->pagination);


        return view('livewire.products', [
            'products_read' => $products_read,
            'categories' => Category::all()
        ]);
    }

}