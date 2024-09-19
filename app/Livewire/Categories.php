<?php

namespace App\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Category;


class Categories extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $categories = [];
    public $category = [];
    public $isEdit = false;
    public $title = 'Add New Category';
    public $shouldCloseModal = false;
    public $pagination= 5;


    protected $rules = [
        'categories.*.name' => 'required|string|max:255',
        'categories.*.description' => 'required|string|max:1000',
        'category.name' => 'required|string|max:255',
        'category.description' => 'required|string|max:1000',
    ];


    public function mount()
    {
        $this->resetFields();
    }

    public function cancel()
    {
        $this->resetFields();
        if ($this->isEdit) {
            $this->reset();
        }
    }

    public function resetFields()
    {
        $this->title = 'Add New Category';
        $this->categories = [['name' => '', 'description' => '']];
        $this->category = ['name' => '', 'description' => ''];
        $this->isEdit = false;
        $this->shouldCloseModal = true; 
    }


    public function addForm()
    {
        $this->categories[] = ['name' => '', 'description' => ''];
    }


    public function removeCategories($key)
    {
        unset($this->categories[$key]);
        $this->categories = array_values($this->categories);
    }


    public function save()
    {
        if ($this->isEdit) {
            $this->validate([
                'category.name' => 'required|string|max:255',
                'category.description' => 'required|string|max:1000',
            ]);
            Category::updateOrCreate(['id' => $this->category['id']], $this->category);
            //sweetalert edit
            session()->flash('message', 'Category Successfully Updated.');
        } else {
            $this->validate([
                'categories.*.name' => 'required|string|max:255',
                'categories.*.description' => 'required|string|max:1000',
            ]);
            foreach ($this->categories as $category) {
                Category::create($category);
            }
            //sweetalert add
            session()->flash('message', 'Category Successfully Added.');
        }


        $this->resetFields();
        $this->shouldCloseModal = true;
        return redirect('/categories');
    }



    public function edit($id)
    {
        $this->title = 'Edit Category';
        $category = Category::findOrFail($id);
        $this->category = ['id' => $id, 'name' => $category->name, 'description' => $category->description];
        $this->isEdit = true;
    }


    public function delete($id)
    {
        Category::findOrFail($id)->delete();
    }


    public function updatingSearchTerm()
    {
        $this->resetPage();
    }


    public function render()
    {
        $categories_read = Category::where('name', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('description', 'like', '%' . $this->searchTerm . '%')
            ->latest()
            ->paginate($this->pagination);


        return view('livewire.categories', ['categories_read' => $categories_read]);
    }
}