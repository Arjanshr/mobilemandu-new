<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Imports\ProductsImport;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Product;
use App\Models\ProductSpecification;
use App\Models\Specification;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $selected_brand = $request->brand_id ?? null;
        $selected_categories = $request->category_id ?? [];
        $query = $request->get('query')??null;
        $products = Product::with('images')
            ->orderBy('id', 'DESC');
        if ($selected_brand != null) {
            $products = $products->where('brand_id', $selected_brand);
        }
        if (count($selected_categories) > 0) {
            $products = $products->whereHas('categories', function ($q) use ($selected_categories) {
                $q->whereIn('categories.id', $selected_categories);
            });
        }
        if (isset($request->query) && $request->query != '') {
            
            $products = $products->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->get('query') . '%')
                    ->orWhere('description', 'like', '%' . $request->get('query') . '%');
            });
        }else{

        }
        $products = $products->paginate(10);
        $categories = Category::get();
        $brands = Brand::get();
        return view('admin.product.index', compact('products', 'categories', 'brands', 'selected_brand', 'selected_categories','query'));
    }

    public function create()
    {
        return view('admin.product.form');
    }

    public function insert(ProductRequest $request)
    {
        $data = $request->validated();
        unset($data['categories']);
        $product = Product::create($data);
        $product->categories()->sync($request->category_id);
        toastr()->success('Product Created Successfully!');
        return redirect()->route('products');
    }

    public function show(Product $product)
    {
        return view('admin.product.show', compact('product'));
    }

    public function edit(Product $product)
    {
        // return $product;
        return view('admin.product.form', compact('product'));
    }

    public function update(Product $product, ProductRequest $request)
    {
        $product->name = $request->name;
        $product->brand_id = $request->brand_id;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->status = $request->status;
        $product->save();
        $product->categories()->sync($request->category_id);
        toastr()->success('Product Edited Successfully!');
        return redirect()->route('products');
    }

    public function delete(Product $product)
    {
        $product->delete();
        toastr()->success('Product Deleted Successfully!');
        return redirect()->route('products');
    }

    public function import(Request $request)
    {
        Excel::queueImport(new ProductsImport, $request->file('import_file'));
        toastr()->success('Product Imported Successfully!');
        return redirect()->route('products');
    }

    public function createSpecifications(Product $product)
    {
        return view('admin.product.specifications-form', compact('product'));
    }

    public function insertSpecifications(Product $product, Request $request)
    {
        $product_specification = new ProductSpecification();
        $specification = Specification::firstOrCreate([
            'name' =>  $request->name
        ]);
        $product_specification->product_id = $product->id;
        $product_specification->specification_id = $specification->id;
        $product_specification->value = $request->value;
        $product_specification->save();
        toastr()->success('Product Created Successfully!');
        return redirect()->route('product.specifications', $product->id);
    }

    public function manageSpecifications(Product $product)
    {
        $product_specifications = ProductSpecification::where('product_id', $product->id)->with('specification')->get();
        return view('admin.product.specifications', compact('product_specifications', 'product'));
    }

    public function editSpecifications(ProductSpecification $product_specification)
    {
        return view('admin.product.specifications-form', compact('product_specification'));
    }

    public function updateSpecifications(ProductSpecification $product_specification, Request $request)
    {
        if (isset($request->name) && $request->name != '') {
            $specification = Specification::firstOrCreate([
                'name' =>  $request->name
            ]);
            $product_specification->specification_id = $specification->id;
        }
        $product_specification->value = $request->value;
        $product_specification->save();
        toastr()->success('Product Edited Successfully!');
        return redirect()->route('product.specifications', $product_specification->product->id);
    }

    public function deleteSpecifications(ProductSpecification $product_specification)
    {
        $product_specification->delete();
        toastr()->success('Product Specification Deleted Successfully!');
        return redirect()->route('product.specifications', $product_specification->product_id);
    }
    public function deleteAllSpecifications(Product $product)
    {
        $product->specifications()->delete();
        toastr()->success('Product Specification Deleted Successfully!');
        return redirect()->route('product.specifications', $product->id);
    }

    public function createFeatures(Product $product)
    {
        return view('admin.product.features-form', compact('product'));
    }

    public function insertFeatures(Product $product, Request $request)
    {
        $feature = new Feature();
        $feature->feature = $request->feature;
        $feature->product_id = $product->id;
        $feature->save();
        toastr()->success('Product Feature Created Successfully!');
        return redirect()->route('product.features', $product->id);
    }

    public function manageFeatures(Product $product)
    {
        $product_features = Feature::where('product_id', $product->id)->with('product')->get();
        return view('admin.product.features', compact('product_features', 'product'));
    }

    public function editFeatures(Feature $feature)
    {
        return view('admin.product.features-form', compact('feature'));
    }

    public function updateFeatures(Feature $feature, Request $request)
    {
        $feature->feature = $request->feature;
        $feature->save();
        toastr()->success('Product Feature Edited Successfully!');
        return redirect()->route('product.features', $feature->product->id);
    }

    public function deleteFeatures(Feature $feature)
    {
        $feature->delete();
        toastr()->success('Product Feature Deleted Successfully!');
        return redirect()->route('product.features', $feature->product_id);
    }

    public function manageImages(Product $product)
    {
        return view('admin.product.images', compact('product'));
    }

    public function linkImages()
    {
        $products = Product::get();
        foreach ($products as $product) {
            foreach ($product->images as $image) {
                if (file_exists(storage_path('app/public/products/' . $image->image))) {
                    $product
                        ->addMedia(storage_path('app/public/products/' . $image->image))
                        ->toMediaCollection();
                }
            }
        }
        return "Images Linked";
    }

    public function insertImages(Product $product, Request $request)
    {
        $product->addMedia($request->file('image'))->toMediaCollection();
    }


    public function updateImages(Product $product, Request $request)
    {
        $media = $product->getMedia()->where('file_name', $request->name)->first();
        // return $media;
        $media->order_column = $request->count;
        $media->save();
    }

    public function deleteImages(Product $product, Request $request)
    {
        $product->getMedia()->where('uuid', $request->id)->first()->delete();
    }
}
