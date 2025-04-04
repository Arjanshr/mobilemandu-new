<?php

namespace App\Http\Controllers;

use App\Enums\CategoryType;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\Specification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(100);
        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        $parent_categories = Category::where('status', 1)->where('parent_id', null)->get();
        // return $parent_categories;
        return view('admin.category.form', compact('parent_categories'));
    }

    public function insert(CategoryRequest $request)
    {
        // return $request;
        $category = $request->validated();
        $category['image'] = $request->hasFile('image') ? $request->validated()['image']->file_name : null;
        Category::create($category);
        toastr()->success('Category Created Successfully!');
        return redirect()->route('categories');
    }

    public function show(Category $category)
    {
        return view('admin.category.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.category.form', compact('category'));
    }

    public function update(Category $category, CategoryRequest $request)
    {
        // return $request;
        $category->name = $request->name;
        $category->parent_id = $request->parent_id;
        $category->description = $request->description;
        $category->status = $request->status;
        if ($request->hasFile('image')) {
            if (File::exists(storage_path("app/public/categories/$category->image")))
                File::delete(storage_path("app/public/categories/$category->image"));
            $category->image = $request->validated()['image']->file_name;
        }
        $category->save();
        toastr()->success('Category Edited Successfully!');
        return redirect()->route('categories');
    }

    public function delete(Category $category)
    {
        if ($this->checkIfHasSubCategories($category))
            return redirect()->route('categories')
                ->withError('Category cannot be deleted!')
                ->withWarning('Delete all the sub categories of this category first');
        if ($category->checkIfHasItems())
            return redirect()->route('categories')
                ->withError('Category cannot be deleted!')
                ->withWarning('Delete all the items of this category first');
        $category->delete();
        toastr()->success('Category Created Successfully!');
        return redirect()->route('categories');
    }

    private function  checkIfHasSubCategories($category)
    {
        if ($category->getAllChildrenIds()->count() > 0) return true;
        return false;
    }

    public function categorySpecifications(Category $category)
    {
        $category_specifications = $category->specifications()->orderBy('pivot_display_order')->get(); // Order by display_order
        return view('admin.category.category-specifications.index', compact('category', 'category_specifications'));
    }
    public function createCategorySpecifications(Category $category)
    {
        return view('admin.category.category-specifications.form', compact('category'));
    }

    public function insertCategorySpecifications(Request $request, Category $category)
    {
        $validated = $request->validate([
            'specification' => 'required',
            ' $request->specification' => 'nullable',
        ]);
        $specification = Specification::firstOrCreate([
            'name' =>  $request->specification
        ]);
        if (!$category->specifications()->where(['specification_id' => $specification->id])->first()) {

            $category->specifications()->attach($specification, ['is_variant' => $request->is_variant ? 1 : 0]);
            toastr()->success('Specification Added Successfully!');
        }
        toastr()->warning('Specification already exists!!!!');
        return redirect()->route('category-specification.create', $category->id);
    }

    public function editCategorySpecifications(Category $category, $category_specification_id)
    {
        $category_specification = $category->specifications()->where(['specification_id' => $category_specification_id])->first();
        return view('admin.category.category-specifications.form', compact('category', 'category_specification'));
    }

    public function updateCategorySpecifications(Request $request, Category $category, $category_specification_id)
    {
        $validated = $request->validate([
            'specification' => 'required',
            ' $request->specification' => 'nullable',
        ]);
        $category->specifications()->updateExistingPivot($category_specification_id, ['is_variant' => $request->is_variant ? 1 : 0]);
        toastr()->success('Specification Added Successfully!');
        return redirect()->route('category-specifications', $category->id);
    }

    public function deleteCategorySpecifications(Category $category, $category_specifications)
    {
        // return  $category->specifications()->where(['specification_id' => $category_specifications])->first();
        $category->specifications()->where(['specification_id' => $category_specifications])->first()->delete();
        toastr()->success('Specification Deleted Successfully!');
        return redirect()->route('category-specifications', $category->id);
    }

    public function updateOrder(Request $request, $category_id)
    {
        $order = $request->input('order');

        foreach ($order as $item) {
            $categorySpecification = Category::find($category_id)
                ->specifications()
                ->where('specification_id', $item['id'])
                ->first();

            if ($categorySpecification) {
                $categorySpecification->pivot->display_order = $item['position'];
                $categorySpecification->pivot->save();
            }
        }

        return response()->json(['success' => true]);
    }
}
