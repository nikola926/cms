<?php
namespace App\Helpers\Media;

use App\Models\Media;

class MediaUpload {
    public static function uploadMedia ($media_file) {
        $media_ext = strtolower($media_file->getClientOriginalExtension());
        $name_gen = hexdec(uniqid());
        if ($media_ext === 'mp3' || $media_ext === 'mp4'){
            $up_location = 'audio/';
        }elseif ($name_gen === 'doc' || $media_ext === 'docx' || $media_ext === 'pdf') {
            $up_location = 'documents/';
        }

        $media_slug = $name_gen.'.'.$media_ext;

        $last_media = $up_location.$media_slug;
        $media_file->move($up_location,$media_slug);
        $alt = substr($media_file->getClientOriginalName(), 0, strpos($media_file->getClientOriginalName(), "."));

        $media = Media::create([
            'alt' => $alt,
            'slug' => $last_media,
            'type' => $media_ext,
        ]);

        if ($media){
            return $media->id;
        }else{
            return response()->json(['message' => 'Media upload fails'], 500);
        }

    }
}
