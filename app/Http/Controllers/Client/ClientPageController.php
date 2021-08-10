<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Status;
use Illuminate\Http\Request;

class ClientPageController extends Controller
{
    public function index(Request $request, string $lang)
    {
        $per_page = $request->per_page;
        $pages = Page::with('featured_image', 'status', 'author')->where(['lang' => $lang, 'status_id' => Status::STATUS_PUBLISH])->paginate($per_page);
        return response()->json($pages);
    }

    public function show(string $lang, int $main_page_id){
        $page = Page::where(['main_page_id'=> $main_page_id, 'lang' => $lang, 'status_id' => Status::STATUS_PUBLISH])->with('author', 'featured_image', 'status')->first();

        return response()->json($page);
    }
}
