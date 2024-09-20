<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;


class Products extends Component
{
    use WithPagination, WithFileUploads;

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
        'product.image' => 'nullable|image|max:1024',
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
        $this->product = ['name' => '', 'description' => '', 'quantity' => 0, 'price' => 0, 'category_id' => '', 'image' => null];
        $this->isEdit = false;
        $this->dispatch('addProduct');
    }


    public function save()
    {
        $this->validate();

        if ($this->isEdit) {
            // Update existing product
            $product = Product::findOrFail($this->product['id']);

            // Check if a new image is uploaded
            if (is_string($this->product['image'])) {
                // Keep the existing image if the image is a string (no new image uploaded)
                $this->product['image'] = $product->image;
            } elseif ($this->product['image']) {
                // If a new image is uploaded, delete the old image if it's not the default
                if ($product->image && $product->image !== 'images/default.png') {
                    Storage::delete('public/' . $product->image);
                }
                // Store new image
                $imagePath = $this->product['image']->store('images', 'public');
                $this->product['image'] = $imagePath;
            }

            $product->update($this->product);
            session()->flash('message', 'Product Successfully Updated.');
        } else {
            // Create a new product
            if ($this->product['image']) {
                // Store the new image
                $imagePath = $this->product['image']->store('images', 'public');
                $this->product['image'] = $imagePath;
            } else {
                // Default image
                $this->product['image'] = 'images/default.png'; // Include 'images/' prefix
            }

            // Create the product with the correct image path
            Product::create($this->product);
            session()->flash('message', 'Product Successfully Added.');
        }

        $this->resetFields();
        $this->shouldCloseModal = true;
        return redirect('/products');
    }



    public function delete($id)
    {
        $product = Product::findOrFail($id);

        // Delete the image from storage if it's not the default image
        if ($product->image && $product->image !== 'images/default.png') {
            $filePath = storage_path('app/public/' . $product->image);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Delete the product
        $product->delete();
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
            'image' => $product->image,
        ];
        $this->isEdit = true;
        $this->dispatch('editProduct');
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
