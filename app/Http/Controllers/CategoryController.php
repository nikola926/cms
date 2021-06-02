<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::with('posts')->get();
        return response()->json($categories);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|unique:categories|max:255',
        ]);

        $name = $request->name;
        $slug = Str::slug($name);

        $parent = $request->parent_id;

        if($parent){
            $parent_id = $parent;
        }else{
            $parent_id = null;
        }


        $category = Category::create([
            'name' => $name,
            'parent_id' => $parent_id,
            'slug' => $slug,

        ]);

        if($category){
            return response()->json($category);
        }else{
            return response()->json(['status' => false]);
        }

    }

    public function update(Request $request, $category){

        $name = $request->name;
        $slug = Str::slug($name);

        $parent = $request->parent_id;

        if($parent){
            $parent_id = $parent;
        }else{
            $parent_id = null;
        }


        $categories = Category::find($category)->update([
            'name' => $name,
            'parent_id' => $parent_id,
            'slug' => $slug,

        ]);

        if($categories){
            return response()->json(['message' => 'Category updated successfully']);
        }else{
            return response()->json(['status' => false]);
        }
    }

    public function show($category) {
        $categories = Category::where('id', $category)->with('posts')->get();
        return response()->json($categories);
    }

    public function destroy($category) {
        $pages = Category::find($category)->forceDelete();
        return response()->json(['message' => 'Category deleted successfully']);
    }

    public function edit($category) {
        $categories = Category::where('id', $category)->get();

        return response()->json($categories);
    }

}
