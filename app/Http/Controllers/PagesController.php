<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pages;
use App\Models\Media;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{


    public function index()
    {
        $pages = Pages::all();
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
            $name_gen = hexdec(uniqid());
            $image_ext = strtolower($featured_image->getClientOriginalExtension());
            $img_slug = $name_gen.'.'.$image_ext;
            $up_location = 'image/';
            $last_image = $up_location.$img_slug;
            $featured_image->move($up_location,$img_slug);

            $image = Media::create([
                'alt' => $featured_image->getClientOriginalName(),
                'slug' => $last_image,
                'type' => $image_ext,
            ]);

            $image_id = $image->id;
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
            'featured_image' => $image_id,
            'author' => Auth::user()->id,
            'status' => 0,
        ]);

        if($page){
            return response()->json($page);
        }else{
            return response()->json(['status' => false]);
        }


    }

    public function show($id){
        $page = Pages::find($id);
        $featured_image = Media::find($page->featured_image);
        return response()->json(['Pages' => $page, 'Media' => $featured_image]);

    }

    public function edit($id) {
        $page = Pages::find($id);
        $featured_image = Media::find($page->featured_image);
        return response()->json(['Pages' => $page, 'Media' => $featured_image]);
    }

    public function update(Request $request, $id) {

        $title = $request->title;
        $slug = Str::of($title)->slug('-');
        $get_content = $request->page_content;
        $featured_image = $request->file('featured_image');
        $old_image = $request->old_image;

        if($featured_image){
            if($old_image != 'null') {
                $name_gen = hexdec(uniqid());
                $image_ext = strtolower($featured_image->getClientOriginalExtension());
                $img_slug = $name_gen . '.' . $image_ext;
                $up_location = 'image/';
                $last_image = $up_location . $img_slug;
                $featured_image->move($up_location, $img_slug);

                $image = Media::find($old_image)->update([
                    'alt' => $featured_image->getClientOriginalName(),
                    'slug' => $last_image,
                    'type' => $image_ext,
                ]);

                $image_id = $old_image;
            }
            else{
                $name_gen = hexdec(uniqid());
                $image_ext = strtolower($featured_image->getClientOriginalExtension());
                $img_slug = $name_gen.'.'.$image_ext;
                $up_location = 'image/';
                $last_image = $up_location.$img_slug;
                $featured_image->move($up_location,$img_slug);

                $image = Media::create([
                    'alt' => $featured_image->getClientOriginalName(),
                    'slug' => $last_image,
                    'type' => $image_ext,
                ]);

                $image_id = $image->id;
            }
        } else{
            $image_id = $old_image;
        }
        if($get_content){
            $content = $get_content;
        }else {
            $content = null;
        }

        $page = Pages::find($id)->update([
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'featured_image' => $image_id,
            'author' => Auth::user()->id,
            'status' => 0,
        ]);

        if($page){
            return response()->json(['message' => 'Page updated successfully']);
        }else{
            return response()->json(['status' => false]);
        }
    }

    public function softDelete($id) {
        $page = Pages::find($id)->delete();
        return response()->json(['message' => 'Page successfully moved to trash']);
    }

    public function trash() {
        $pages = Pages::onlyTrashed()->get();
        return response()->json($pages);
    }

    public function restore($id){
        $pages = Pages::withTrashed()->find($id)->restore();
        return response()->json(['message' => 'Page restored successfully']);
    }

    public function delete($id) {
        $pages = Pages::withTrashed()->find($id)->forceDelete();
        return response()->json(['message' => 'Page deleted successfully']);
    }


}
