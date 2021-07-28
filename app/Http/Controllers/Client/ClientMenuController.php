<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ClientMenuController extends Controller
{
    public function show(string $lang,int $menu_id) {
        $menu = Menu::where('id', $menu_id)
            ->with(['menu_items' => function ($query) use ($lang,$menu_id) {
                return $query->where(['lang' => $lang, 'menu_id' => $menu_id]);
            }])
            ->firstOrFail();
        $langs = Config::get('languages');

        return response()->json([$menu, 'langs' => $langs]);
    }
}
