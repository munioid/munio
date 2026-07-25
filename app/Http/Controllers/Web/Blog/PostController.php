<?php

namespace App\Http\Controllers\Web\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog\Category;
use App\Models\Blog\Tag;
use Filament\Facades\Filament;

class PostController extends Controller
{
    public function index()
    {
        $organization = Filament::getTenant();
        $categories = Category::all();
        $tags = Tag::all();
        return view('default.pages.blog.posts', compact('organization', 'categories', 'tags'));
    }
}
