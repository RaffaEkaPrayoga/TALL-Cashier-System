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
        // Conditional validation for image only when it's a new file upload
        $rules = [
            'product.name' => 'required|string|max:255',
            'product.description' => 'nullable|string',
            'product.quantity' => 'required|integer|min:0',
            'product.price' => 'required|integer|min:0',
            'product.category_id' => 'required|exists:categories,id',
        ];

        if (!$this->isEdit || ($this->isEdit && !is_string($this->product['image']))) {
            $rules['product.image'] = 'nullable|image|mimes:jpg,jpeg,png,gif|max:1024';
        }

        $this->validate($rules);

        if ($this->isEdit) {
            $product = Product::findOrFail($this->product['id']);

            if (is_string($this->product['image'])) {
                $this->product['image'] = $product->image;
            } elseif ($this->product['image']) {
                if ($product->image && $product->image !== 'images/default.png') {
                    $filePath = storage_path('app/public/' . $product->image);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
                $imagePath = $this->product['image']->store('images', 'public');
                $this->product['image'] = $imagePath;
            }

            // Update the product with new data, keeping the image if no new image is uploaded
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
