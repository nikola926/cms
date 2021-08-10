<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\PostRelation;
use App\Models\Status;
use Illuminate\Http\Request;

class ClientPostController extends Controller
{
    public function index(Request $request, string $lang) {
        $per_page = $request->per_page;
        $posts = PostRelation::with
        ([
            'post' => function ($query) use ($lang) {
                return $query->where(['lang' => $lang, 'status_id' => Status::STATUS_PUBLISH]);
            },
            'post.author',
            'post.featured_image',
            'post.status',
            'category_relation.category'=> function ($query) use ($lang) {
                return $query->where('lang', $lang);
            }
        ])
            ->whereHas(
                'post' , function ($query) use ($lang) {
                return $query->where('lang', $lang);
            })
            ->paginate($per_page);

        return response()->json($posts);
    }

    public function show(string $lang, int $main_post_id){
        $post = PostRelation::where('id',$main_post_id)
            ->with
            ([
                'post' => function ($query) use ($lang) {
                    return $query->where(['lang' => $lang,'status_id' => Status::STATUS_PUBLISH]);
                },
                'post.author',
                'post.featured_image',
                'post.status',
                'category_relation.category'=> function ($query) use ($lang) {
                    return $query->where('lang', $lang);
                }
            ])->whereHas(
                'post' , function ($query) use ($lang) {
                return $query->where(['lang' => $lang,'status_id' => Status::STATUS_PUBLISH]);
            })->first();
        if($post){
            return response()->json($post);
        }else{
            return response()->json(['message' => 'No post translation'], 404);
        }

    }
}
