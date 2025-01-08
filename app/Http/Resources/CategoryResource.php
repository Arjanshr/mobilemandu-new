<?php

namespace App\Http\Resources;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $subcategories = [];
        foreach ($this->children as $count => $subcategory) {
            $subcategories[$count]['id'] = $subcategory->id;
            $subcategories[$count]['name'] = $subcategory->name;
            $subcategories[$count]['slug'] = $subcategory->slug;
            $subcategories[$count]['brand'] = $this->brands($subcategory->products ?? []);
            if ($subcategory->children->count() > 0) {
                foreach ($subcategory->children as $count1=> $scat) {
                    $subcategories[$count][$count1]['id'] = $scat->id;
                    $subcategories[$count][$count1]['name'] = $scat->name;
                    $subcategories[$count][$count1]['slug'] = $scat->slug;
                    $subcategories[$count][$count1]['brand'] = $this->brands($scat->products ?? []);
                }
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            "description" => $this->description,
            "slug" => $this->slug,
            "imageLink" => $this->image ? asset('storage/categories/' . $this->image) : asset('images/default.png'),
            "subcategories" => $subcategories,
            "brands" => $this->brands($this->products),
        ];
    }

    private function brands($products)
    {
        $brand_ids = null;
        foreach ($products as $product) {
            if (isset($product->brand->id)) {
                $brand_ids[] = $product->brand->id;
            }
        }
        $brands = null;
        if ($brand_ids != null)
            $brands = Brand::select('id', 'name', 'slug')->whereIn('id', $brand_ids)->get();
        return $brands;
    }

    private function subcategories($subcategories)
    {
        if ($subcategories->count() == 0) {
            return null;
        } else {
            foreach ($subcategories as $subcategory) {
                // $subcategory = $this->subcategories($subcategory);
                return $subcategory;
            }
        }
        return $subcategories;
    }
}
