<?php

use Illuminate\Support\Facades\Storage;
use Image as thumbimage;

if (! function_exists('getFile')) {
    function getFile($name, $type,$isBanner="false",$for="image") {
        
        $defaultImagePath = "";
        if($isBanner){
            $defaultImagePath =  URL::to('/').'/storage/app/public'.'/uploads/default_banner.jpg?d='.time();
        }else{
            $defaultImagePath =  URL::to('/').'/storage/app/public'.'/uploads/default.jpg?d='.time();
        }

        if($for == 'thumb'){
            if(!empty($name) && file_exists(storage_path('app/public').'/uploads/'.$type.'/'.'thumb'.'/'.$name)) {
                return URL::to('/').'/storage/app/public'.'/uploads/'.$type.'/'.'thumb'.'/'.$name.'?d='.time();
            } else {
                return $defaultImagePath;
            }
        }else{
            if(!empty($name) && file_exists(storage_path('app/public').'/uploads/'.$type.'/'.$name)) {
                return URL::to('/').'/storage/app/public'.'/uploads/'.$type.'/'.$name.'?d='.time();
            } else {
                return  $defaultImagePath;
            }
        }
        
    }
}


if (! function_exists('ListingImageUrl')) {
    function ListingImageUrl($type,$imageName,$for="image",$isBanner="false") {
        $src = '';
        $defaultImagePath = "";
        if($isBanner){
            $defaultImagePath =  'storage/app/public/uploads/default_banner.jpg?d='.time();
        }else{
            $defaultImagePath =  'storage/app/public/uploads/default.jpg?d='.time();
        }
        if($for == 'thumb'){
            if (!empty($imageName) && file_exists('storage/app/public/uploads/'.$type.'/'.'thumb'.'/'.$imageName)) {
                $src =  'storage/app/public/uploads/'.$type.'/'.'thumb'.'/'.$imageName.'?d='.time();
            }else{
                //default image path
                $src = $defaultImagePath;
            }
            
        }else{
            if (!empty($imageName) && file_exists('storage/app/public/uploads/'.$type.'/'.$imageName)) {
                $src =  'storage/app/public/uploads/'.$type.'/'.$imageName.'?d='.time();
            }else{
                //default image path
                $src = $defaultImagePath;
            }
        }
        return url($src);
    }
}


if (! function_exists('file_exist_ret')) {
    function file_exist_ret($type, $id, $ib, $ext) {
        if (file_exists('storage/app/public/uploads/'.$type.'/'.$type.'_'.$id.'_'.$ib.'.'.$ext)) {
            $ib = $ib + 1;
            return file_exist_ret($type,$id,$ib,$ext);
        } else {
            return $ib;
        }
    }
}

if (! function_exists('file_view')) {
    function file_view($type, $id, $num_of_imgs) {
        $files = explode(',',implode(',',preg_grep('~^'.$type.'_'.$id.'_*~', scandir("storage/app/public/uploads/".$type))));
        $src = '';
        $src_array = array();
        if($files[0] != '') {
            for($i=0; $i < count($files); $i++) {
                if($src == '') {
                    $src = 'storage/app/public/uploads/'.$type.'/'.$files[$i].'||'.str_replace($type.'_','',(explode('.',$files[$i]))[0]);
                    array_push($src_array, url($src) );
                } else {
                    $src = 'storage/app/public/uploads/'.$type.'/'.$files[$i].'||'.str_replace($type.'_','',(explode('.',$files[$i]))[0]);
                    array_push($src_array, url($src));
                } 
            }
        }
        return $src_array;
    }
}


if (! function_exists('saveSingleImage')) {
    function saveSingleImage($file,$type="",$id="") {
        //$image = $request->file('attachment');
        $actualImagePath = 'uploads/'.$type;
        $extension = $file->extension();
        $originalImageName = $type.'_'.$id.'.'.$extension;
        $file->storeAs($actualImagePath,$originalImageName,'public');
        return $originalImageName;
    }
}

if (! function_exists('createThumbnail')) {
    function createThumbnail($file,$type="",$id="",$for="image") {
        $extension = $file->extension();
        $originalThumbImageName = $type .'_thumb_'.$id .'.'. $file->extension();
        $thumbnailFilePath = storage_path('app/public/uploads/'.$type.'/thumb');
        $img = thumbimage::make($file->path());
        $width="100";
        $height="100";
        if($for == 'banner'){
            $width="100";
            $height="70";  
        }
        $img->resize($width, $height, function ($const) {
            //$const->aspectRatio();
        })->save($thumbnailFilePath . '/' . $originalThumbImageName);
        return $originalThumbImageName;
    }
} 


if (! function_exists('file_view')) {
    function file_view($type, $id, $num_of_imgs) {
        $files = explode(',',implode(',',preg_grep('~^'.$type.'_'.$id.'_*~', scandir("storage/app/public/uploads/".$type))));
        $src = '';
        if($files[0] != '') {
            for($i=0; $i < count($files); $i++) {
                if($src == '') {
                    $src = 'storage/app/public/uploads/'.$type.'/'.$files[$i].'||'.str_replace('products_','',(explode('.',$files[$i]))[0]);
                } else {
                    $src = $src.',storage/app/public/uploads/'.$type.'/'.$files[$i].'||'.str_replace('products_','',(explode('.',$files[$i]))[0]);
                } 
            }
        }
        return $src;
    }
}

  