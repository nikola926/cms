<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

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
        $menu = Menu::findOrFail($menu_id)
            ->with(['menu_items' => function ($query) use ($lang,$menu_id) {
                    return $query->where(['lang' => $lang, 'menu_id' => $menu_id]);
                }])
            ->firstOrFail();

        return response()->json($menu);
    }

    public  function store_item(Request $request, string $lang, int $menu_id) {
        $request->validate([
            'name' => 'required|max:255',
            'order' => 'required',
            'related_id' => 'required',
            'type' => 'required',
        ]);

        $order = $request->order;
        $name = $request->name;
        $related_id = $request->related_id;
        $item_properties = $request->item_properties;
        $type = $request->type;
        $parent_id = $request->parent_id;

        $menu_item = MenuItem::create([
            'menu_id' => $menu_id,
            'parent_id' => $parent_id,
            'lang' => $lang,
            'order' => $order,
            'name' => $name,
            'related_id' => $related_id,
            'item_properties' => $item_properties,
            'type' => $type,
        ]);

        return response()->json($menu_item);
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
