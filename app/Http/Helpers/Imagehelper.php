<?php

use Illuminate\Support\Facades\Storage;
use Image as thumbimage;
use Illuminate\Support\Facades\File;

if (!function_exists('getFile')) {
    function getFile($name, $type, $isBanner = "false", $for = "image")
    {

        $expiryDate = now()->addDay(); //The link will be expire after 1 day
        $defaultImagePath = "";
        if ($isBanner) {
            // $defaultImagePath =  URL::to('/') . '/storage/app/public' . '/uploads/default_banner.jpg?d=' . time();
            $src = $type . '/' . 'default_banner.jpg';

        } else {
            // $defaultImagePath =  URL::to('/') . '/storage/app/public' . '/uploads/default.jpg?d=' . time();
            $src = 'default.jpg';

        }

        if ($for == 'thumb') {
            if (!empty($name) && \Storage::disk('s3')->exists($type . '/' . 'thumb' . '/' . $name)) {
                // return URL::to('/') . '/storage/app/public' . '/uploads/' . $type . '/' . 'thumb' . '/' . $name . '?d=' . time();
                 $src = $type . '/' . 'thumb' . '/' . $name;
                 return \Storage::disk('s3')->temporaryUrl($src, $expiryDate);

            } else {
                return \Storage::disk('s3')->temporaryUrl($src, $expiryDate);
              
            }
        } elseif ($for == 'unlink') {
            if (!empty($name) && \Storage::disk('s3')->exists($type . '/' . $name)) {
                $src =  $type . '/' . $name;
                return \Storage::disk('s3')->temporaryUrl($src, $expiryDate);

            } else {
                return 'file_not_found';
            }
        } elseif ($for == 'front' || $for == 'back') {
            if (!empty($name) && \Storage::disk('s3')->exists($type . '/' . $name)) {
                $src = $type . '/' . $name . '?d=' . time();
                return \Storage::disk('s3')->temporaryUrl($src, $expiryDate);
            } else {
                return  'file_not_found';
            }
        } elseif ($for == 'gst_certificate') {
            if (!empty($name) && \Storage::disk('s3')->exists($type . '/' . $name)) {
                $src = $type . '/' . $name;
                return \Storage::disk('s3')->temporaryUrl($src, $expiryDate);
            } else {
                $src = 'default_user_gst_file.png';
                return \Storage::disk('s3')->temporaryUrl($src, $expiryDate);
            }
        } elseif ($for == 'vendor_gst_certificate') {
            if (!empty($name) && \Storage::disk('s3')->exists($type . '/' . $name)) {
                $src = $type . '/' . $name;
                return \Storage::disk('s3')->temporaryUrl($src, $expiryDate);
            } else {
                $src = 'default_vendor_gst_file.png';
                return \Storage::disk('s3')->temporaryUrl($src, $expiryDate);
            }
        } else {
            if (!empty($name) && \Storage::disk('s3')->exists($type . '/' . $name)) {
                $src = $type . '/' . $name;
                return \Storage::disk('s3')->temporaryUrl($src, $expiryDate);
            } else {
                return \Storage::disk('s3')->temporaryUrl($src, $expiryDate);
            }
        }
    }
}


if (!function_exists('ListingImageUrl')) {
    function ListingImageUrl($type, $name, $for = "image", $isBanner = "false")
    {
        $src = '';
        $expiryDate = now()->addDay(); //The link will be expire after 1 day
        $defaultImagePath = "";
        if ($isBanner) {
            $defaultImagePath = 'default_banner.jpg';
        } else {
            $defaultImagePath =  'default.jpg';
        }
        if ($for == 'thumb') {
            
            if (!empty($name) && \Storage::disk('s3')->exists($type . '/' . 'thumb' . '/' . $name)) {
                $src = $type . '/' . 'thumb' . '/' . $name;
            } else {
                //default image path
                $src = $type . '/' .$defaultImagePath;
            }
        } else {
            if (!empty($name) && \Storage::disk('s3')->exists($type . '/' . $name)) {
                $src = $type . '/' . $name;
            } else {
                //default image path
                $src = $type . '/' .$defaultImagePath;
            }
        }
        // return url($src);
        return \Storage::disk('s3')->temporaryUrl($src, $expiryDate);

    }
}

if (!function_exists('file_exist_ret')) {
    function file_exist_ret($type, $id, $ib, $ext)
    {
        if (file_exists(\Storage::disk('s3')->exists($type . '/' . $type . '_' . $id . '_' . $ib . '.' . $ext))) {
            $ib = $ib + 1;
            return file_exist_ret($type, $id, $ib, $ext);
        } else {
            return $ib;
        }
    }
}

//if (!function_exists('file_view')) {
//    function file_view($type, $id, $num_of_imgs)
    {
//        $files = explode(',', implode(',', preg_grep('~^' . $type . '_' . $id . '_*~', scandir("storage/app/public/uploads/" . $type))));
//        $src = '';
//        $src_array = array();
//        if ($files[0] != '') {
//            for ($i = 0; $i < count($files); $i++) {
//                if ($src == '') {
//                    $src = 'storage/app/public/uploads/' . $type . '/' . $files[$i] . '||' . str_replace($type . '_', '', (explode('.', $files[$i]))[0]);
                    	    	
//		    array_push($src_array, url($src));
//                } else {
//                    $src = 'storage/app/public/uploads/' . $type . '/' . $files[$i] . '||' . str_replace($type . '_', '', (explode('.', $files[$i]))[0]);
//                    array_push($src_array, url($src));
//                }
//            }
//        }
//        return $src_array;
//    }
//}


if (!function_exists('saveSingleImage')) {
    function saveSingleImage($file, $type = "", $id = "")
    {
        $actualImagePath = $type;
        $extension = $file->extension();
        $originalImageName = $type . '_' . $id . '.' . $extension;
        \Storage::disk("s3")->putFileAs($actualImagePath, $file, $originalImageName);
        return $originalImageName;
    }
}

if (!function_exists('createThumbnail')) {
    function createThumbnail($file, $type = "", $id = "", $for = "image")
    {
        $avatar = $file;
        $extension = $file->getClientOriginalExtension();
        $filename =  $type . '_thumb_' . $id . '.' . $file->extension();
        $thumbnailFilePath =  $type . '/thumb';

        $width = config('global.DEFAULT_THUMB_IMAGE_WIDTH');
        $height = config('global.DEFAULT_THUMB_IMAGE_HEIGHT');
        if ($for == 'banner') {
            $width = config('global.BANNER_THUMB_IMAGE_WIDTH');
            $height = config('global.BANNER_THUMB_IMAGE_HEIGHT');
        }

        $normal = thumbimage::make($avatar)->resize($width, $height)->encode($extension);
        \Storage::disk('s3')->put($thumbnailFilePath . '/' . $filename, (string)$normal);

        return $filename;
    }
}

if (!function_exists('createThumbnailMultiple')) {
    function createThumbnailMultiple($file, $type = "", $id = "", $for = "image",$i = "")
    {

        $avatar = $file;
        $extension = $file->getClientOriginalExtension();
        $filename =  $type . '_thumb_' . $id . '_' . $i  . '.' . $file->extension();
        $thumbnailFilePath =  $type.'/'.$type . '_' . $id . '/thumb';

        $width = config('global.DEFAULT_THUMB_IMAGE_WIDTH');
        $height = config('global.DEFAULT_THUMB_IMAGE_HEIGHT');
        if ($for == 'banner') {
            $width = config('global.BANNER_THUMB_IMAGE_WIDTH');
            $height = config('global.BANNER_THUMB_IMAGE_HEIGHT');
        }

        $normal = thumbimage::make($avatar)->resize($width, $height)->encode($extension);
        \Storage::disk('s3')->put($thumbnailFilePath . '/' . $filename, (string)$normal);

        return $filename;
    }
}


if (!function_exists('file_view')) {
    function file_view($type, $id, $num_of_imgs)
    {
        $expiryDate = now()->addDay(); //The link will be expire after 1 day
        $src_files = $type.'/'.$type.'_'.$id;
        $files = \Storage::disk('s3')->allFiles($src_files, $expiryDate);
        $src = '';
        $src_array = array();
        $i=1;
        foreach($files as $file) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            if ($src == '') {
                if (in_array($type.'/'.$type.'_'.$id.'/'.$type.'_'.$id.'_'.$i.'.'.$ext, $files)){
                    $src = \Storage::disk('s3')->temporaryUrl($type.'/'.$type.'_'.$id.'/'.$type.'_'.$id.'_'.$i.'.'.$ext, $expiryDate);
                    array_push($src_array, $src);
                }
            } else {
                if (in_array($type.'/'.$type.'_'.$id.'/'.$type.'_'.$id.'_'.$i.'.'.$ext, $files)){
                    $src = \Storage::disk('s3')->temporaryUrl($type.'/'.$type.'_'.$id.'/'.$type.'_'.$id.'_'.$i.'.'.$ext, $expiryDate);
                    array_push($src_array, $src);
                }
            }
            $i++;
        }
        return $src_array;
    }
}
    }

