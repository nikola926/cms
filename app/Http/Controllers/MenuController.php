<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index() {
        $menus = Menu::with('menu_item')->get();

        return response()->json($menus);
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|unique:menu|max:255',
        ]);

        $menus = Menu::create([
            'name' => $request->name,
        ]);

        if($menus){
            return response()->json($menus);
        }else{
            return response()->json(['status' => false]);
        }
    }

    public function update(Request $request, $menu){
        $menus = Menu::find($menu)->update([
            'name' => $request->name,
        ]);

        if($menus){
            return response()->json($menus);
        }else{
            return response()->json(['status' => false]);
        }
    }

    public function destroy($menu) {
        $menus = Pages::find($menu)->forceDelete();
        return response()->json(['message' => 'Page successfully moved to trash']);
    }

    public function show($menu) {
        $menus = Menu::where('id', $menu)->with('menu_item')->get();

        return response()->json($menus);
    }

}
