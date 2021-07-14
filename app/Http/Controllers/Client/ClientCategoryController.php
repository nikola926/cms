<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryRelation;
use App\Models\PostRelation;
use Illuminate\Http\Request;

class ClientCategoryController extends Controller
{
    public function index(string $lang) {
        $categories = Category::where('lang', $lang)->get();
        return response()->json($categories);
    }

    public function show(Request $request,string $lang, int $main_category_id)
    {
        $per_page = $request->per_page;

        $category = Category::where(['lang' => $lang, 'main_category_id' => $main_category_id])->firstOrFail();

        $posts = PostRelation::with
        ([
            'post' => function ($query) use ($lang) {
                return $query->where('lang', $lang);
            },
            'post.author',
            'post.featured_image',
            'post.status'
        ])
            ->whereHas(
                'category_relation.category' , function ($query) use ($main_category_id) {
                return $query->where('main_category_id', $main_category_id);
            })
            ->whereHas(
                'post' , function ($query) use ($lang) {
                return $query->where('lang', $lang);
            })
            ->paginate($per_page);

        return response()->json(['category' => $category ,'posts' => $posts]);
    }
}
