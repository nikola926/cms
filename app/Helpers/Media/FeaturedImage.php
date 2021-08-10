<?php
    namespace App\Helpers\Media;

    use App\Models\Media;
    use Intervention\Image\Facades\Image;

    class featuredImage {

        public static function uploadFeaturedImage($featured_image) {
            $name_gen = hexdec(uniqid());
            $image_ext = strtolower($featured_image->getClientOriginalExtension());
            $img_slug = $name_gen.'.'.$image_ext;
            $up_location = 'image/';
            $last_image = $up_location.$img_slug;
            $featured_image->move($up_location,$img_slug);
            $alt = substr($featured_image->getClientOriginalName(), 0, strpos($featured_image->getClientOriginalName(), "."));

            $thumbnail = Image::make($last_image);
            $medium = Image::make($last_image);

            $thumbnail->resize(170, 120, function ($constraint) {
                $constraint->aspectRatio();
            })->save($up_location.'thumbnail-'.$img_slug);
            $thumbnail_location = $up_location.'thumbnail-'.$img_slug;

            $medium->resize(450, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($up_location.'medium-'.$img_slug);
            $medium_location = $up_location.'medium-'.$img_slug;

            $properties = [
                'thumbnail' => $thumbnail_location,
                'medium' => $medium_location,
            ];

            $image = Media::create([
                'alt' => $alt,
                'slug' => $last_image,
                'properties' => $properties ,
                'type' => $image_ext,
            ]);

            return $image->id;
        }
    }
