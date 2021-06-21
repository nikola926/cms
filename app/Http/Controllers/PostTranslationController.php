<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Integer;

class PostTranslationController extends Controller
{
    public function index() {
        $posts = Post::with('category','author', 'featured_image', 'status')->get();

        return response()->json($posts);
    }

    public function store(Request $request, int $main_post_id = null) {
        $request->validate([
            'title' => 'required|unique:post_translations|max:255',
            'lang' => 'unique:post_translations,lang,NULL,id,main_post_id,' . $main_post_id

        ]);

        if(!isset($main_post_id)){
            $main_post = Post::create();
            $main_post_id = $main_post->id;
        }

        $lang = $request->lang;
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

        $post = PostTranslation::create([
            'main_post_id' => $main_post_id,
            'lang' => $lang,
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

    public function show(string $lang, int $post_id){
        $post = PostTranslation::where('id', $post_id)->with('category','author', 'featured_image', 'status')->get();
        return response()->json($post);

    }
}
