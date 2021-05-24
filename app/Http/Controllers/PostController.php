<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index() {
        $posts = Posts::with('category','author', 'featured_image', 'status')->get();

        return response()->json($posts);
    }
}
