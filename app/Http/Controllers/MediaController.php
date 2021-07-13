<?php

namespace App\Http\Controllers;

use App\Helpers\Media\featuredImage;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public  function index() {
        $media = Media::paginate();

        return response()->json($media);
    }

    public function store(Request $request) {
        $featured_image = $request->file('file');
        FeaturedImage::uploadFeaturedImage($featured_image);

        return response()->json(['message' => 'Media upload successfully']);
    }

    public function update(Request $request, int $media_id) {
        $media_alt = $request->alt;
        $media = Media::findOrFail($media_id);

        $media->update([
            'alt' => $media_alt
        ]);

        if($media){
            return response()->json(['message' => 'Media updated successfully']);
        }else{
            return response()->json(['error' => 'Internal Server Error'], 500);
        }

    }

    public function show(int $media_id) {
        $media = Media::findOrFail($media_id);

        return response()->json($media);
    }

    public function destroy(int $media_id) {
        Media::findOrFail($media_id)->delete();

        return response()->json(['message' => 'Media deleted successfully']);
    }
}
