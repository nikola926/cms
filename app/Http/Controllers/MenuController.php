<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    //-----MENU METHODS-------

    public function index() {
        $menus = Menu::all();

        return response()->json($menus);
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|unique:menus|max:255',
        ]);

        $menu = Menu::create([
            'name' => $request->name,
        ]);

        return response()->json($menu);
    }

    public function update(Request $request,int $menu_id){
        $menu = Menu::findOrFail($menu_id)->update([
            'name' => $request->name,
        ]);

        return response()->json($menu);
    }

    public function destroy(int $menu_id) {
        Menu::findOrFail($menu_id)->forceDelete();
        return response()->json(['message' => 'Menu successfully deleted!']);
    }

    //-----MENU ITEM METHODS-------

    public function show(string $lang,int $menu_id) {
        $menu = Menu::where('id', $menu_id)
            ->with(['menu_items' => function ($query) use ($lang,$menu_id) {
                    return $query->where(['lang' => $lang, 'menu_id' => $menu_id])->orderBy('order');
                }])
            ->firstOrFail();

        return response()->json($menu);
    }

    public  function store_item(Request $request, string $lang, int $menu_id) {
        $lang = $request->lang;
        $menu_id = $request->menu_id;
        $type = $request->type;
        $menu_items = collect($request->menu_items);
        $deleted_items = $request->deleted_items;

        if($deleted_items){
            foreach ($deleted_items as $item_id){
                DB::table('menu_items')->where('id', $item_id)->delete();
            }
        }

        if($menu_items){
            $menu_items->map(function ($item, $key) use ($menu_id,$lang,$type) {
                if(array_key_exists('action', $item)){
                    if ($item['action'] == 'new_item'){
                        MenuItem::create([
                            'menu_id' => $menu_id,
                            'lang' => $lang,
                            'order' => $key,
                            'name' => $item['name'],
                            'related_id' => $item['related_id'],
                            'type' => $item['type'],
                            'item_properties' => $item['item_properties']
                        ]);
                    }
                }else {
                    $menu_item = MenuItem::findOrFail($item['id']);
                    $menu_item->update([
                        'name' => $item['name'],
                        'order' => $key,
                        'item_properties' => $item['item_properties']
                    ]);
                }
            });
        }
        return response()->json(['message' => 'Menu updated successfully']);
    }

    public function destroy_item(string $lang, int $menu_item_id) {
        MenuItem::findOrFail($menu_item_id)->forceDelete();
        return response()->json(['message' => 'Menu Item successfully deleted!']);
    }

    public  function update_item(Request $request, string $lang, int $menu_item_id) {
        $request->validate([
            'name' => 'required|max:255',
            'order' => 'required'
        ]);

        $order = $request->order;
        $name = $request->name;
        $item_properties = $request->item_properties;
        $parent_id = $request->parent_id;

        $menu_item = MenuItem::findOrFail($menu_item_id)->update([
            'parent_id' => $parent_id,
            'order' => $order,
            'name' => $name,
            'item_properties' => $item_properties,
        ]);

        return response()->json(['message' => 'Menu item updated successfully']);
    }

}
