<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class PostController extends Controller
{
    public function index() {
        $posts = Post::with('category','author', 'featured_image', 'status')->get();

        return response()->json($posts);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|unique:posts|max:255',
        ]);

        $title = $request->title;
        $slug = Str::of($title)->slug('-');
        $get_content = $request->post_content;
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

        $post = Post::create([
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'featured_image_id' => $image_id,
            'author_id' => Auth::user()->id,
            'status_id' => 1,
        ]);

        if($post){
            return response()->json($post);
        }else{
            return response()->json(['status' => false]);
        }

    }

    public function show($post){
        $posts = Post::where('id', $post)->with('category','author', 'featured_image', 'status')->get();
        return response()->json($posts);

    }

    public function edit($post) {
        $pages = Post::where('id', $post)->with('category','author', 'featured_image', 'status')->get();

        return response()->json(['Page' => $pages]);
    }

    public function update(Request $request, $post) {

        $title = $request->title;
        $slug = Str::of($title)->slug('-');
        $get_content = $request->post_content;
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

        $posts = Post::find($post)->update([
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'featured_image_id' => $image_id,
            'author_id' => Auth::user()->id,
            'status_id' => $status_id,
        ]);

        if($posts){
            return response()->json(['message' => 'Page updated successfully']);
        }else{
            return response()->json(['status' => false]);
        }
    }

    public function destroy($post) {
        $posts = Post::find($post)->delete();
        return response()->json(['message' => 'Page successfully moved to trash']);
    }

    public function trash() {
        $pages = Post::onlyTrashed()->get();
        return response()->json($pages);
    }

    public function restore($post){
        $pages = Post::withTrashed()->find($post)->restore();
        return response()->json(['message' => 'Page restored successfully']);
    }

    public function delete($post) {
        $pages = Post::withTrashed()->find($post)->forceDelete();
        return response()->json(['message' => 'Page deleted successfully']);
    }
}
