<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Helpers\Media\featuredImage;


class PostController extends Controller
{
    public function all_lang_posts(Request $request) {
        $posts_per_page = $request->posts_per_page;
        $posts = PostRelation::with('all_lang_posts')->paginate($posts_per_page);
        return response()->json($posts);
    }

    public function index(Request $request, string $lang) {
        $posts_per_page = $request->posts_per_page;
        $posts = PostRelation::with
        ([
            'post' => function ($query) use ($lang) {
                return $query->where('lang', $lang);
            },
            'post.author',
            'post.featured_image',
            'post.status',
            'category_relation.category'=> function ($query) use ($lang) {
                return $query->where('lang', $lang);
            },
            'translated_post' => function ($query) {
                return $query;
            }
        ])
            ->whereHas(
            'post' , function ($query) use ($lang) {
            return $query->where('lang', $lang);
        })
            ->paginate($posts_per_page);

        return response()->json($posts);
    }

    public function store(Request $request, string $lang,int $main_post_id = null) {
         $request->validate([
            'title' => 'required|unique:posts|max:255',
            'lang' => 'required|unique:posts,lang,NULL,id,main_post_id,' . $main_post_id,
            'status_id' => 'required|exists:statuses,id'
        ]);

        if(!isset($main_post_id)){
            $main_post = PostRelation::create();
            $main_post_id = $main_post->id;
        }

        $lang = $request->lang;
        $title = $request->title;
        $slug = Str::of($title)->slug('-');
        $get_content = $request->post_content;
        $featured_image = $request->file('featured_image');
        $featured_image_id = $request->featured_image_id;
        $categories = $request->categories;
        $status_id = $request->status_id;



        if($featured_image){
            $image_id = FeaturedImage::uploadFeaturedImage($featured_image);
        }elseif($featured_image_id){
            $image_id = $featured_image_id;
        } else{
            $image_id = null;
        }
        if($get_content){
            $content = $get_content;
        }else {
            $content = null;
        }

        $post = Post::create([
            'main_post_id' => $main_post_id,
            'lang' => $lang,
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'featured_image_id' => $image_id,
            'author_id' => Auth::user()->id,
            'status_id' => $status_id,
        ]);

        DB::table('categories_posts')->where('post_id', $main_post_id)->delete();

        if($categories){
            foreach ($categories as $category_id) {
                DB::table('categories_posts')->insert([
                    'category_id' => $category_id,
                    'post_id' => $main_post_id,
                ]);
            }
        }

        if($post){
            return response()->json([$post]);
        }else{
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function show(string $lang, int $main_post_id){
        $post = PostRelation::where('id',$main_post_id)
        ->with
        ([
            'post' => function ($query) use ($lang) {
                return $query->where('lang', $lang);
            },
            'post.author',
            'post.featured_image',
            'post.status',
            'category_relation.category'=> function ($query) use ($lang) {
                return $query->where('lang', $lang);
            }
        ])->firstOrFail();

        return response()->json($post);
    }

    public function update(Request $request, string $lang, int $post_id) {
        $request->validate([
            'title' => ['required', Rule::unique('posts')->ignore($post_id)],
            'status_id' => 'required|exists:statuses,id'
        ]);

        $title = $request->title;
        $slug = Str::of($title)->slug('-');
        $get_content = $request->post_content;
        $featured_image = $request->file('featured_image');
        $featured_image_id = $request->featured_image_id;
        $status_id = $request->status_id;
        $categories = $request->categories;

        if($featured_image){
            $image_id = FeaturedImage::uploadFeaturedImage($featured_image);
        } elseif ($featured_image_id) {
            $image_id = $featured_image_id;
        } else {
            $image_id = null;
        }

        if($get_content){
            $content = $get_content;
        }else {
            $content = null;
        }

        $post = Post::findOrFail($post_id);
        $post->update([
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'featured_image_id' => $image_id,
            'status_id' => $status_id,
        ]);
        $main_post_id = $post->main_post_id;

        DB::table('categories_posts')->where('post_id', $main_post_id)->delete();

        if($categories){
            foreach ($categories as $category_id) {
                DB::table('categories_posts')->insert([
                    'category_id' => $category_id,
                    'post_id' => $main_post_id,
                ]);
            }
        }

        if($post){
            return response()->json(['message' => 'Post updated successfully', 'post' => $post]);
        }else{
            return response()->json(['error' => 'Internal Server Error'], 500);
        }

    }

    public function destroy(string $lang, int $post_id) {
        $post = Post::findOrFail($post_id)->delete();
        return response()->json(['post_id' => $post_id ,'message' => 'Post successfully moved to trash']);
    }

    public function trash(Request $request,string $lang) {
        $posts_per_page = $request->posts_per_page;
        $posts = Post::onlyTrashed()->where('lang', $lang)->paginate($posts_per_page);
        return response()->json($posts);
    }

    public function restore(string $lang, $post_id){
        Post::withTrashed()->findOrFail($post_id)->restore();
        return response()->json(['post_id' => $post_id , 'message' => 'Post restored successfully']);
    }

    public function delete(string $lang, $post_id) {
        Post::withTrashed()->findOrFail($post_id)->forceDelete();
        return response()->json(['post_id' => $post_id , 'message' => 'Post deleted successfully']);
    }
}
