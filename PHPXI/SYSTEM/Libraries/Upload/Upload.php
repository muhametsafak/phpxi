<?php
namespace PHPXI\Libraries\Upload;

class Upload{

    private $file;
    private $error;
    private $continue = true;

    private $sizeLimit;
    private $fileName = "";
    private $path;
    private $dir_url;
    private $file_type;
    private $file_extension;
    private $jpg_compress;
    private $png_compress;
    private $resize = ["width" => "300", "height" => "300"];

    private $pathinfo;
    private $filePath;
    private $fileUrl;
    private $thumbnail = [];

    public function config($config){
        foreach($config as $key => $value){
            $this->$key = $value;
        }
        return $this;
    }

    public function path($path){
        $this->path = $path;
    }

    public function url($url){
        $this->dir_url = $url;
    }

    public function name($name){
        $this->fileName = $name;
    }

    public function maxSize($size){
        $this->sizeLimit = $size;
    }


    public function return(){
        if($this->error != ""){
            return $this->error;
        }else{
            $return = array(
                "name"  => $this->fileName,
                "size"  => $this->file["size"],
                "type"  => $this->file["type"],
                "path"  => $this->filePath,
                "url"   => $this->fileUrl,
                "thumbnail" => $this->thumbnail,
                "status"    => $this->continue
            );
            return $return;
        }
    }

    public function file($file){
        $this->file = $file;
        if(!is_dir($this->path)){
            if(!mkdir($this->path)){
                $this->error = "ERROR : Could not create path : " . $this->path;
                $this->continue = false;
            }
        }
        if($this->continue){
            if(isset($file['error']) and $file['error'] != 0){
                $this->error = "ERROR : File Error : " . $file['error'];
                $this->continue = false;
            }elseif($file['size'] > $this->sizeLimit){
                $this->error = "ERROR : Max File Limit : " . $this->sizeLimit;
                $this->continue = false;
            }elseif(!in_array(strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)), $this->file_extension)){
                $this->error = "ERROR : Unsupported File Extension! Supported : ".implode(", ", $this->file_extension);
                $this->continue = false;
            }elseif(!in_array($file['type'], $this->file_type) and !in_array(mime_content_type($file["tmp_name"]), $this->file_type)){
                $this->error = "ERROR : Unsupported File Type! Supported : ".implode(", ", $this->file_type);
                $this->continue = false;
            }else{
                if($this->fileName == ""){
                    $this->fileName = basename($this->file["name"]);
                }
                $this->filePath = $this->path . $this->fileName;
                $this->pathinfo = pathinfo($this->filePath);
                if(file_exists($this->filePath)){
                    $i = 0;
                    do{
                        $i++;
                        $this->fileName = $this->pathinfo["filename"] . '-' . $i . '.' . $this->pathinfo["extension"];
                        $this->filePath = $this->path . $this->fileName;
                        if(file_exists($this->filePath)){
                        $re_file_path_while = true;
                        }else{
                            $re_file_path_while = false;
                        }
                    }while($re_file_path_while);
                }
                $this->pathinfo = pathinfo($this->filePath);
                $this->file_valid_ext = $this->pathinfo['extension'];
                $this->fileUrl = $this->dir_url . "/" . $this->fileName;
                $this->continue = true;
            }
        }
        return $this;
    }

    public function handle(){
        if($this->continue){
            if($this->file["type"] == "image/jpeg" || $this->file["type"] == "image/jpg" || $this->file["type"] == "image/png"){
                if($this->file["type"] == "image/jpeg" || $this->file["type"] == "image/jpg"){
                    $upload = $this->jpg_compress($this->file['tmp_name'], $this->filePath, $this->jpg_compress);
                }else{
                    $upload = $this->png_compress($this->file['tmp_name'], $this->filePath, $this->png_compress);
                }
            }else{
                $upload = move_uploaded_file($this->file['tmp_name'], $this->filePath);
            }
            if($upload){
                $this->continue = true;
            }else{
                $this->error = "";
                $this->continue = false;
            }
        }
        return $this;
    }

    public function thumbnail($width = 300, $height = 300, $prefix = "thumb_"){
        if($this->continue){
            $this->thumbnail = $this->image_resize($this->filePath, ["width" => $width, "height" => $height], $prefix);
        }
    }

    public function jpg_compress($source, $destination, $quality) {
        $image = imagecreatefromjpeg($source);
        return imagejpeg($image, $destination, $quality);
    }
  
    public function png_compress($source, $destination, $quality){
        if($quality > 9){
            $quality = 9;
        }
        $image = imagecreatefrompng($source);
        imageAlphaBlending($image, false);
        imageSaveAlpha($image, true);
        return imagepng($image, $destination, $quality);
    }


    public function image_resize($path, $resize = ["width" => "300", "height" => "300"], $prefix = "thumb_"){
        $pathinfo = pathinfo($path);
    
        $dirname = $pathinfo["dirname"];
        $basename = $pathinfo["basename"];
    
        $re_basename = $prefix . $basename;
        $re_file_path = $dirname . '/' . $re_basename;
    
        if(file_exists($re_file_path)){
            $i = 0;
            do{
                $i++;
                $re_basename = $prefix . $pathinfo["filename"] . '-' . $i . '.' . $pathinfo["extension"];
                $re_file_path = $dirname . '/' . $re_basename;
                if(file_exists($re_file_path)){
                    $re_file_path_while = true;
                }else{
                    $re_file_path_while = false;
                }
            }while($re_file_path_while);
        }
        $return = array("path" => $re_file_path, "url" => $this->dir_url . "/" . $re_basename);
    
        $getImageSize = getimagesize($path);
        if ($getImageSize[0] > $getImageSize[1]) {
            $new_width = $resize['width'];
            $new_height = intval($getImageSize[1] * $new_width / $getImageSize[0]);
        } else {
            $new_height = $resize['height'];
            $new_width = intval($getImageSize[0] * $new_height / $getImageSize[1]);
        }
        $dest_x = intval(($resize['width'] - $new_width) / 2);
        $dest_y = intval(($resize['height'] - $new_height) / 2);
    
        if ($getImageSize['mime'] == 'image/jpeg' || $getImageSize['mime'] == 'image/jpg') {
            $old_image = imagecreatefromjpeg($path);
            $new_image = imagecreatetruecolor($new_width, $new_height);
            imagecopyresized($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $getImageSize[0], $getImageSize[1]);
            imagejpeg($new_image, $re_file_path);
        }elseif($getImageSize['mime'] == 'image/gif') {
            $old_image = imagecreatefromgif($path);
            $new_image = imagecreatetruecolor($new_width, $new_height);
            imagecopyresized($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $getImageSize[0], $getImageSize[1]);
            imagegif($new_image, $re_file_path);
        }elseif($getImageSize['mime'] == 'image/png') {
            $old_image = imagecreatefrompng($path);
            $new_image = imagecreatetruecolor($new_width, $new_height);
            imageAlphaBlending($new_image, false);
            imageSaveAlpha($new_image, true);
            imagecopyresized($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $getImageSize[0], $getImageSize[1]);
            imagepng($new_image, $re_file_path);
        }
        return $return;
      }



}