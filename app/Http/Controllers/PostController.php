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
            $image_id = FeaturedImage::uploadFeaturedImage($featured_image);
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

    public function show(int $post_id){
        $post = Post::where('id', $post_id)->with('category','author', 'featured_image', 'status')->get();
        return response()->json($post);

    }

    public function edit(int $post_id) {
        $post = Post::where('id', $post_id)->with('category','author', 'featured_image', 'status')->get();

        return response()->json($post);
    }

    public function update(Request $request, int $post_id) {

        $title = $request->title;
        $slug = Str::of($title)->slug('-');
        $get_content = $request->post_content;
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

        $post = Post::findOrFail($post_id)->update([
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'featured_image_id' => $image_id,
            'author_id' => Auth::user()->id,
            'status_id' => $status_id,
        ]);

        if($post){
            return response()->json(['message' => 'Post updated successfully']);
        }else{
            return response()->json(['status' => false]);
        }
    }

    public function destroy(int $post_id) {
        $post = Post::findOrFail($post_id)->delete();
        return response()->json(['post_id' => $post->id ,'message' => 'Post successfully moved to trash']);
    }

    public function trash() {
        $posts = Post::onlyTrashed()->get();
        return response()->json($posts);
    }

    public function restore($post_id){
        $post = Post::withTrashed()->findOrFail($post_id)->restore();
        return response()->json(['post_id' => $post->id , 'message' => 'Post restored successfully']);
    }

    public function delete($post_id) {
        $post = Post::withTrashed()->findOrFail($post_id)->forceDelete();
        return response()->json(['$post_id' => $post->id , 'message' => 'Post deleted successfully']);
    }
}
