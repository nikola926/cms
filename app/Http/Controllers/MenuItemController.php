<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function store(Request $request, string $lang, int $menu_id) {
        $request->validate([
            'name' => 'required|unique:menus|max:255',
        ]);
    }
}
