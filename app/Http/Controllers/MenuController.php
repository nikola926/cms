<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index() {
        $menus = Menu::with('menu_items')->get();

        return response()->json($menus);
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|unique:menu|max:255',
        ]);

        $menu = Menu::create([
            'name' => $request->name,
        ]);

        if($menu){
            return response()->json($menu);
        }else{
            return response()->json(['status' => false]);
        }
    }

    public function update(Request $request,int $menu_id){
        $menu = Menu::find($menu_id)->update([
            'name' => $request->name,
        ]);

        if($menu){
            return response()->json($menu);
        }else{
            return response()->json(['status' => false]);
        }
    }

    public function destroy(int $menu_id) {
        Pages::findOrFail($menu_id)->forceDelete();
        return response()->json(['message' => 'Page successfully moved to trash']);
    }

    public function show(int $menu_id) {
        $menu = Menu::where('id', $menu_id)->with('menu_items')->get();

        return response()->json($menu);
    }

}
