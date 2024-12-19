<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogDetailResource;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends BaseController
{
    public function blogs()
    {
        $blogs =  Blog::published()->orderBy('id', 'DESC')->get();
        return $this->sendResponse(BlogResource::collection($blogs), 'Blogs retrieved successfully.');
    }

    public function blogDetails($blog_slug)
    {
        $blog = Blog::published()->where('slug', $blog_slug)->first();
        if ($blog)
            return $this->sendResponse(BlogDetailResource::make($blog), 'Blog details retrieved successfully.');
        return $this->sendError('Cannot find the blog', 'No such blog', 404);
    }
}
