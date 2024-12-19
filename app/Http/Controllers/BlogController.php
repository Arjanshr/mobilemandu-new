<?php

namespace App\Http\Controllers;

use App\Enums\BrandType;
use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use Illuminate\Support\Facades\File;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::paginate(100);
        return view('admin.blog.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blog.form');
    }

    public function insert(BlogRequest $request)
    {
        $blog = $request->validated();
        $blog['image'] = $request->hasFile('image') ? $request->validated()['image']->file_name : null;
        Blog::create($blog);
        toastr()->success('Blog Created Successfully!');
        return redirect()->route('blogs');
    }

    public function show(Blog $blog)
    {
        return view('admin.blog.show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        return view('admin.blog.form', compact('blog'));
    }

    public function update(Blog $blog, BlogRequest $request)
    {
        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->status = $request->status;
        if ($request->hasFile('image')) {
            if (File::exists(storage_path("app/public/blogs/$blog->image")))
                File::delete(storage_path("app/public/blogs/$blog->image"));
            $blog->image = $request->validated()['image']->file_name;
        }
        $blog->save();
        toastr()->success('Blog Edited Successfully!');
        return redirect()->route('blogs');
    }

    public function delete(Blog $blog)
    {
        if (File::exists(storage_path("app/public/blogs/$blog->image")))
            File::delete(storage_path("app/public/blogs/$blog->image"));
        $blog->delete();
        toastr()->success('Blog Deleted Successfully!');
        return redirect()->route('blogs');
    }
}
