<?php

namespace App\Http\Controllers;

use App\Models\Widget;
use Illuminate\Http\Request;

class WidgetController extends Controller
{
    public function index() {
        $widgets = Widget::all();

        return response()->json($widgets);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|unique:widgets|max:255',
        ]);

        $name = $request->name;
        $content = $request->widget_content;

        $widgets = Widget::create([
            'name' => $name,
            'content' => $content,
        ]);

        if($widgets){
            return response()->json($widgets);
        }else{
            return response()->json(['status' => false]);
        }
    }

    public function update(Request $request, $widget) {

        $name = $request->name;
        $content = $request->widget_content;

        $widgets = Widget::find($widget)->update([
            'name' => $name,
            'content' => $content,
        ]);

        if($widgets){
            return response()->json($widgets);
        }else{
            return response()->json(['status' => false]);
        }

    }

    public function destroy($widget) {
        $widgets = Widget::find($widget)->forceDelete();
        return response()->json(['message' => 'Widget successfully moved to trash']);
    }
}
