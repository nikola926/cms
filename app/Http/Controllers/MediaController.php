<?php

namespace App\Http\Controllers;

use App\Helpers\Media\featuredImage;
use App\Models\Media;
use Illuminate\Http\Request;
use App\Helpers\Media\MediaUpload;

class MediaController extends Controller
{
    public  function index(Request $request) {
        $per_page = $request->per_page;

        $media = Media::paginate($per_page);

        return response()->json($media);
    }

    public function store(Request $request) {
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,mp3,mp4,doc,docx,pdf',
        ]);
        $media = $request->file('file');
        $media_ext = strtolower($media->getClientOriginalExtension());

        if ($media_ext === 'jpg' || $media_ext === 'jpeg' || $media_ext === 'png'){
            $media = FeaturedImage::uploadFeaturedImage($media);
        }else{
            $media = MediaUpload::uploadMedia($media);
        }

        if ($media){
            return response()->json(['message' => 'Media upload successfully']);
        }else{
            return response()->json(['message' => 'Media upload failed'], 500);
        }

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

    public  function images(Request $request) {
        $image_ext = ['jpg', 'jpeg', 'png'];
        $per_page = $request->per_page;

        $media = Media::whereIn('type', $image_ext)->paginate($per_page);

        return response()->json($media);
    }

    public  function documents(Request $request) {
        $document_ext = ['doc', 'docx', 'pdf'];
        $per_page = $request->per_page;

        $media = Media::whereIn('type', $document_ext)->paginate($per_page);

        return response()->json($media);
    }

    public  function audio(Request $request) {
        $audio_ext = ['mp3', 'mp4'];
        $per_page = $request->per_page;

        $media = Media::whereIn('type', $audio_ext)->paginate($per_page);

        return response()->json($media);
    }

    public function destroy(int $media_id) {
        Media::findOrFail($media_id)->delete();

        return response()->json(['message' => 'Media deleted successfully']);
    }
}
