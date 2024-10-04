<?php

namespace App\Livewire;

use App\Enums\CategoryType;
use App\Models\Brand;
use App\Models\Category;
use Livewire\Component;

class ProductForm extends Component
{
    public  $brands;
    public $categories;
    public $message;
    public $name;
    public $product;
    public $description;

    public function mount()
    {
        $this->categories = Category::with('children')
            ->doesntHave('children')
            ->get();
        $this->brands = Brand::get();
        if($this->product){
            $this->name = $this->product->name;
            $this->description = $this->product->description;
        }
    }

    public function render()
    {
        return view('admin.livewire.product-form');
    }
}
