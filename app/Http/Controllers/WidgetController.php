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

        $widget = Widget::create([
            'name' => $name,
            'content' => $content,
        ]);

        if($widget){
            return response()->json($widget);
        }else{
            return response()->json(['status' => false]);
        }
    }

    public function update(Request $request, int $widget_id) {

        $name = $request->name;
        $content = $request->widget_content;

        $widget = Widget::findOrFail($widget_id)->update([
            'name' => $name,
            'content' => $content,
        ]);

        if($widget){
            return response()->json($widget);
        }else{
            return response()->json(['status' => false]);
        }

    }

    public function destroy(int $widget_id) {
        Widget::find($widget_id)->forceDelete();
        return response()->json(['message' => 'Widget successfully moved to trash']);
    }
}
