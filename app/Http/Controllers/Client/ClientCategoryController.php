<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryRelation;
use Illuminate\Http\Request;

class ClientCategoryController extends Controller
{
    public function index(string $lang) {
        $categories = Category::where('lang', $lang)->get();
        return response()->json($categories);
    }

    public function show(string $lang, int $main_category_id)
    {
        $category = CategoryRelation::where('id', $main_category_id)
            ->with([
                'category' => function ($query) use ($lang) {
                    return $query->where('lang', $lang);
                },
                'post_relation.post' => function ($query) use ($lang) {
                    return $query->where('lang', $lang);
                }])
            ->firstOrFail();
        return response()->json($category);
    }
}
