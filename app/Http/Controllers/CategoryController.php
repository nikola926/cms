<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\PostRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\CategoryRelation;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function all_lang_category() {
        $category = CategoryRelation::with('all_lang_category');
        return response()->json($category);
    }

    public function index(Request $request,string $lang) {
        $per_page = $request->per_page;
        $search = $request->search;
        $categories = CategoryRelation::with
        ([
            'category' => function ($query) use ($lang) {
                return $query->where('lang', $lang);
            },
            'translated_category' => function ($query) {
                return $query;
            }
        ])
            ->whereHas(
                'category' , function ($query) use ($lang,$search) {
                return $query->where('lang', $lang)->where('name', 'like', '%' . $search .'%');
            })
            ->paginate($per_page);

        return response()->json($categories);
    }

    public function store(Request $request, string $lang,int $main_category_id = null) {
        $request->validate([
            'name' => 'required|unique:categories|max:255',
            'lang' => 'required|unique:categories,lang,NULL,id,main_category_id,' . $main_category_id
        ]);
        if(!isset($main_category_id)){
            $main_category = CategoryRelation::create();
            $main_category_id = $main_category->id;
        }

        $lang = $request->lang;
        $name = $request->name;
        $slug = Str::slug($name);

        $parent = $request->parent_id;

        if($parent){
            $parent_id = $parent;
        }else{
            $parent_id = null;
        }


        $category = Category::create([
            'main_category_id' => $main_category_id,
            'lang' => $lang,
            'name' => $name,
            'parent_id' => $parent_id,
            'slug' => $slug,

        ]);

        if($category){
            return response()->json($category);
        }else{
            return response()->json(['error' => 'Internal Server Error'], 500);
        }

    }

    public function update(Request $request, string $lang,int $category_id) {
        $request->validate([
            'name' => ['required', Rule::unique('categories')->ignore($category_id)],
        ]);

        $name = $request->name;
        $slug = Str::slug($name);

        $category = Category::findOrFail($category_id)->update([
            'name' => $name,
            'slug' => $slug,

        ]);

        if($category){
            return response()->json(['message' => 'Category updated successfully'], 200);
        }else{
            return response()->json(['error' => 'Internal Server Error'], 500);
        }

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

    public function destroy(string $lang, int $category_id) {
        Category::findOrFail($category_id)->forceDelete();
        return response()->json(['message' => 'Category deleted successfully']);
    }

}
