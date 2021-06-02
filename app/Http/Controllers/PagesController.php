<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;
use App\Models\Pages;
use App\Models\Media;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use FeaturedImage;

class PagesController extends Controller
{


    public function index()
    {
        $pages = Pages::with('featured_image', 'status', 'author')->get();
        return $pages;
    }


    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|unique:pages|max:255',
        ]);

        $title = $request->title;
        $slug = Str::of($title)->slug('-');
        $get_content = $request->page_content;
        $featured_image = $request->file('featured_image');


        if($featured_image){
            $image_id = FeaturedImage::uploadFeaturedImage($featured_image);

        } else{
            $image_id = null;
        }
        if($get_content){
            $content = $get_content;
        }else {
            $content = null;
        }

        $page = Pages::create([
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'featured_image_id' => $image_id,
            'author_id' => Auth::user()->id,
            'status_id' => 1,
        ]);

        if($page){
            return response()->json($page);
        }else{
            return response()->json(['status' => false]);
        }


    }

    public function show(int $page_id) {
        $page = Pages::where('id', $page_id)->with('featured_image', 'status', 'author')->get();
        return response()->json($page);

    }

    public function edit(int $page_id) {
        $page = Pages::where('id', $page_id)->with('featured_image', 'status', 'author')->get();

        return response()->json($page);
    }

    public function update(Request $request, int $page_id) {

        $title = $request->title;
        $slug = Str::of($title)->slug('-');
        $get_content = $request->page_content;
        $featured_image = $request->file('featured_image');
        $old_image = $request->old_image;
        $status_id = $request->status_id;

        if($featured_image){
            $image_id = FeaturedImage::uploadFeaturedImage($featured_image);
        } elseif ($old_image != 'null') {
            $image_id = $old_image;
        } else {
            $image_id = null;
        }


        if($get_content){
            $content = $get_content;
        }else {
            $content = null;
        }

        $page = Pages::findOrFail($page_id)->update([
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'featured_image_id' => $image_id,
            'author_id' => Auth::user()->id,
            'status_id' => $status_id,
        ]);

        if($page){
            return response()->json(['message' => 'Page updated successfully']);
        }else{
            return response()->json(['status' => false]);
        }
    }

    public function destroy(int $page_id) {
        $page = Pages::findOrFail($page_id)->delete();
        return response()->json(['message' => 'Page successfully moved to trash']);
    }

    public function trash() {
        $pages = Pages::onlyTrashed()->get();
        return response()->json($pages);
    }

    public function restore(int $page_id){
        Pages::withTrashed()->findOrFail($page_id)->restore();
        return response()->json(['message' => 'Page restored successfully']);
    }

    public function delete(int $page_id) {
        Pages::withTrashed()->findOrFail($page_id)->forceDelete();
        return response()->json(['message' => 'Page deleted successfully']);
    }


}
