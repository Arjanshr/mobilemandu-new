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
use Illuminate\Support\Facades\Response;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $selected_brand = $request->brand_id ?? null;
        $selected_categories = $request->category_id ?? [];
        $query = $request->get('query') ?? null;
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
        }
        $products = $products->paginate(10);
        $categories = Category::get();
        $brands = Brand::get();
        return view('admin.product.index', compact('products', 'categories', 'brands', 'selected_brand', 'selected_categories', 'query'));
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
        return redirect()->route('product.specification.create', $product->id);
    }

    public function show(Product $product)
    {
        return view('admin.product.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('admin.product.form', compact('product'));
    }

    public function update(Product $product, ProductRequest $request)
    {
        $product->update($request->only(['name', 'description', 'price', 'status', 'alt_text', 'keywords']));
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
        $specifications = $product->categories()
            ->first()
            ->specifications()
            ->withPivot('display_order') // Ensure pivot field is loaded
            ->orderBy('category_specification.display_order') // Correct table and column name
            ->get();

        $product_specifications = [];
        foreach ($product->specifications()->get() as $p_spec) {
            $product_specifications[$p_spec->pivot->specification_id] = $p_spec->pivot->value;
        }

        return view('admin.product.specifications-form', compact('product', 'specifications', 'product_specifications'));
    }

    public function insertSpecifications(Product $product, Request $request)
    {
        $validated = $request->validate([
            'value' => 'required|array',
        ]);
        foreach ($request->value as $specification_id => $value) {
            if ($value != null && $value != '') {
                $product_specification = ProductSpecification::where('product_id', $product->id)
                    ->where('specification_id', $specification_id)
                    ->first();
                if (!$product_specification)
                    $product_specification = new ProductSpecification();
                $product_specification->product_id = $product->id;
                $product_specification->specification_id = $specification_id;
                $product_specification->value = $value;
                $product_specification->save();
            }
        }
        toastr()->success('Product Created Successfully!');
        return redirect()->route('product.feature.create', $product->id);
    }

    public function manageSpecifications(Product $product)
    {
        $specifications = $product->categories()
            ->first()
            ->specifications()
            ->withPivot('display_order') // Ensure pivot field is loaded
            ->orderBy('category_specification.display_order') // Correct table and column name
            ->get();
        $product_specifications = [];
        foreach ($specifications as $specification) {
            $specification_data = $product->specifications()->where('specification_id', $specification->id)->first();
            if ($specification_data !== null) { // Remove null values
                $product_specifications[] = $specification_data;
            }
        }
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

    public function deleteSpecifications(Product $product, Specification $specification)
    {
        $product->specifications()->detach($specification->id);
        toastr()->success('Product Specification Deleted Successfully!');
        return redirect()->route('product.specifications', $product->id);
    }
    public function deleteAllSpecifications(Product $product)
    {
        $product->specifications()->detach();
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
        return redirect()->route('product.images', $product->id);
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

    public function deleteAllFeatures(Product $product)
    {
        $product->features()->delete();
        toastr()->success('Product Features Deleted Successfully!');
        return redirect()->route('product.features', $product->id);
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
        $media->order_column = $request->count;
        $media->save();
    }

    public function deleteImages(Product $product, Request $request)
    {
        $product->getMedia()->where('uuid', $request->id)->first()->delete();
    }

    public function createVariants(Product $product)
    {
        $product = Product::with(['categories.specifications' => function ($query) {
            $query->wherePivot('is_variant', true);
        }])->find($product->id);
        $variant_specifications = $product->categories->first()->specifications;
        return view('admin.product.variants-form', compact('product', 'variant_specifications'));
    }



    public function insertVariants(Request $request, Product $product)
    {
        $validated = $request->validate([
            'variants' => 'required|array',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0', // Ensure stock_quantity is provided
        ]);
        $variants = $request['variants'];
        foreach ($variants as $variant_data) {
            // Generate SKU based on specifications (e.g., RAM, ROM, Color)
            $sku = $this->generateUniqueSku($variant_data,$product);
            // Create the variant with price, stock_quantity, and sku
            $variant = $product->variants()->create([
                'price' => $variant_data['price'],
                'stock_quantity' => $variant_data['stock'], // Include stock_quantity
                'sku' => $sku,
            ]);

            // Add variant options for each specification
            foreach ($variant_data as $key => $value) {
                if ($key !== 'price' && $key !== 'stock_quantity' && $key !== 'sku') {
                    $specification = Specification::where('name', $key)->first();

                    if ($specification) {
                        $variant->variant_options()->create([
                            'specification_id' => $specification->id,
                            'value' => $value,
                        ]);
                    }
                }
            }
        }
        toastr()->success('Variants created successfully.');
        return redirect()->back()->with('success', 'Variants created successfully.');
    }


    public function manageVariants(Product $product)
    {
        // Eager load necessary relationships
        $product->load(['variants.variant_options.specification']);

        // Prepare the variants data in a streamlined manner
        $variants = $product->variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'sku' => $variant->sku,
                'price' => $variant->price,
                'stock' => $variant->stock_quantity,
                'options' => $variant->variant_options->map(fn($option) => [
                    'specification' => $option->specification->name,
                    'value' => $option->value,
                ])->values(), // Ensure a clean indexed array
            ];
        })->values(); // Ensure the result is a clean indexed array

        // Pass data to the view
        return view('admin.product.variants', compact('product', 'variants'));
    }


    private function generateUniqueSku($variant_data, $product)
    {
        // Define the specifications that should be included in the SKU
        $sku_parts = [];
        $sku_parts[0] = strtoupper($product->slug);

        // Dynamically loop through the variant data to create SKU
        foreach ($variant_data as $key => $value) {
            // Exclude non-specification fields like price and stock_quantity
            if ($key !== 'price' && $key !== 'stock' && $key !== 'sku') {
                // Format the specification part (e.g., RAM-8GB, ROM-128GB, COLOR-Black)
                $sku_parts[] = strtoupper($key) . '-' . strtoupper($value);
            }
        }

        // If no valid parts are available for SKU, throw an error
        if (empty($sku_parts)) {
            throw new \Exception("Failed to generate SKU. Missing required specifications.");
        }

        // Generate the SKU by joining the parts with a dash
        return implode('-', $sku_parts);
    }



    public function editVariants($productId, $variantId)
    {
        $product = Product::findOrFail($productId);
        $variant = $product->variants()->findOrFail($variantId);

        return view('admin.product.variants_edit', compact('product', 'variant'));
    }

    public function updateVariants(Request $request, $productId, $variantId)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($productId);
        $variant = $product->variants()->findOrFail($variantId);

        $variant->update([
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
        ]);
        toastr()->success('Variants edited successfully.');
        return redirect()->route('product.variants', $productId)
            ->with('success', 'Variant updated successfully.');
    }


    public function deleteVariants($productId, $variantId)
    {
        $product = Product::findOrFail($productId);
        $variant = $product->variants()->findOrFail($variantId);

        $variant->delete();

        return redirect()->route('product.variants', $productId)
            ->with('success', 'Variant deleted successfully.');
    }
    public function deleteAllVariants(Product $product)
    {
        $product->specifications()->detach();
        toastr()->success('Product Specification Deleted Successfully!');
        return redirect()->route('product.specifications', $product->id);
    }
    public function export()
    {
        $products = Product::with(['categories', 'brand', 'variants', 'media'])->get();

        $csvData = [];
        $csvData[] = ['ID', 'Name', 'Price', 'Category', 'Brand', 'Variant', 'Status', 'Image Link', 'Product Link'];

        foreach ($products as $product) {
            $categories = $product->categories->pluck('name')->implode(', ');
            $brand = $product->brand ? $product->brand->name : 'N/A';
            $variants = $product->variants->pluck('name')->implode(', ');
            $imageLink = $product->getFirstMedia() ? $product->getFirstMedia()->getUrl() : 'N/A';
            $productLink = "https://www.mobilemandu.com/products/" . $product->slug;

            $csvData[] = [
                $product->id,
                $product->name,
                $product->price,
                $categories,
                $brand,
                $variants,
                ucfirst($product->status) . 'ed',
                $imageLink,
                $productLink,
            ];
        }

        $filename = 'products_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://temp', 'w+');
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);

        return Response::stream(function () use ($handle) {
            fpassthru($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}
