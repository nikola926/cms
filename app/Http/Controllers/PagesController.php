<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Media;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{


    public function index()
    {
        $pages = Page::with('featured_image', 'status', 'author')->get();
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

        $page = Page::create([
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

    public function show($page){
        $pages = Page::where('id', $page)->with('featured_image', 'status', 'author')->get();
        return response()->json($pages);

    }

    public function edit($page) {
        $pages = Page::where('id', $page)->with('featured_image', 'status', 'author')->get();

        return response()->json(['Page' => $pages]);
    }

    public function update(Request $request, $page) {

        $title = $request->title;
        $slug = Str::of($title)->slug('-');
        $get_content = $request->page_content;
        $featured_image = $request->file('featured_image');
        $old_image = $request->old_image;
        $status_id = $request->status_id;

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

        $pages = Page::find($page)->update([
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'featured_image_id' => $image_id,
            'author_id' => Auth::user()->id,
            'status_id' => $status_id,
        ]);

        if($pages){
            return response()->json(['message' => 'Page updated successfully']);
        }else{
            return response()->json(['status' => false]);
        }
    }

    public function destroy($page) {
        $page = Page::find($page)->delete();
        return response()->json(['message' => 'Page successfully moved to trash']);
    }

    public function trash() {
        $pages = Page::onlyTrashed()->get();
        return response()->json($pages);
    }

    public function restore($page){
        $pages = Page::withTrashed()->find($page)->restore();
        return response()->json(['message' => 'Page restored successfully']);
    }

    public function delete($page) {
        $pages = Page::withTrashed()->find($page)->forceDelete();
        return response()->json(['message' => 'Page deleted successfully']);
    }


}
