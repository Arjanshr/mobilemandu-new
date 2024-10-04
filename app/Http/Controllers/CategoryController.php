<?php

namespace App\Http\Controllers;

use App\Enums\CategoryType;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(100);
        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        $parent_categories = Category::where('status', 1)->get();
        return view('admin.category.form', compact('parent_categories'));
    }

    public function insert(CategoryRequest $request)
    {
        $category = Category::create($request->validated());
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
        $category->status = $request->status;
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
        if($category->getAllChildrenIds()->count()>0) return true;
        return false;
    }

    
}
