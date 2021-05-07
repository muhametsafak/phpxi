<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Upload;

class Upload
{

    private static $file;
    private static $error;
    private static $continue = true;

    private static $sizeLimit;
    private static $fileName = "";
    private static $path;
    private static $dir_url;
    private static $file_type;
    private static $file_extension;
    private static $jpg_compress;
    private static $png_compress;
    private static $resize = ["width" => "300", "height" => "300"];

    private static $pathinfo;
    private static $filePath;
    private static $fileUrl;
    private static $thumbnail = [];

    public static function config($config)
    {
        foreach($config as $key => $value){
            self::$$key = $value;
        }
        return new self;
    }

    public static function path($path)
    {
        self::$path = $path;
        return new self;
    }

    public static function url($url)
    {
        self::$dir_url = $url;
        return new self;
    }

    public static function name($name)
    {
        self::$fileName = $name;
        return new self;
    }

    public static function maxSize($size)
    {
        self::$sizeLimit = $size;
        return new self;
    }


    public static function return()
    {
        if(self::$error != ""){
            return self::$error;
        }else{
            $return = array(
                "name"  => self::$fileName,
                "size"  => self::$file["size"],
                "type"  => self::$file["type"],
                "path"  => self::$filePath,
                "url"   => self::$fileUrl,
                "thumbnail" => self::$thumbnail,
                "status"    => self::$continue
            );
            return $return;
        }
    }

    public static function file($file)
    {
        self::$file = $file;
        if(!is_dir(self::$path)){
            if(!mkdir($self::path)){
                self::$error = "ERROR : Could not create path : " . self::$path;
                self::$continue = false;
            }
        }
        if(self::$continue){
            if(isset($file['error']) and $file['error'] != 0){
                self::$error = "ERROR : File Error : " . $file['error'];
                self::$continue = false;
            }elseif($file['size'] > self::$sizeLimit){
                self::$error = "ERROR : Max File Limit : " . self::$sizeLimit;
                self::$continue = false;
            }elseif(!in_array(strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)), self::$file_extension)){
                self::$error = "ERROR : Unsupported File Extension! Supported : ".implode(", ", self::$file_extension);
                self::$continue = false;
            }elseif(!in_array($file['type'], self::$file_type) and !in_array(mime_content_type($file["tmp_name"]), self::$file_type)){
                self::$error = "ERROR : Unsupported File Type! Supported : ".implode(", ", self::$file_type);
                self::$continue = false;
            }else{
                if(self::$fileName == ""){
                    self::$fileName = basename(self::$file["name"]);
                }
                self::$filePath = self::$path . self::$fileName;
                self::$pathinfo = pathinfo(self::$filePath);
                if(file_exists(self::$filePath)){
                    $i = 0;
                    do{
                        $i++;
                        self::$fileName = self::$pathinfo["filename"] . '-' . $i . '.' . self::$pathinfo["extension"];
                        self::$filePath = self::$path . self::$fileName;
                        if(file_exists(self::$filePath)){
                            $re_file_path_while = true;
                        }else{
                            $re_file_path_while = false;
                        }
                    }while($re_file_path_while);
                }
                self::$pathinfo = pathinfo(self::$filePath);
                self::$file_valid_ext = self::$pathinfo['extension'];
                self::$fileUrl = self::$dir_url . "/" . self::$fileName;
                self::$continue = true;
            }
        }
        return new self;
    }

    public static function handle()
    {
        if(self::$continue){
            if(self::$file["type"] == "image/jpeg" || self::$file["type"] == "image/jpg" || self::$file["type"] == "image/png"){
                if(self::$file["type"] == "image/jpeg" || self::$file["type"] == "image/jpg"){
                    $upload = self::jpg_compress(self::$file['tmp_name'], self::$filePath, self::$jpg_compress);
                }else{
                    $upload = self::png_compress(self::$file['tmp_name'], self::$filePath, self::$png_compress);
                }
            }else{
                $upload = move_uploaded_file(self::$file['tmp_name'], self::$filePath);
            }
            if($upload){
                self::$continue = true;
            }else{
                self::$error = "";
                self::$continue = false;
            }
        }
        return new self;
    }

    public static function thumbnail(int $width = 300, int $height = 300, string $prefix = "thumb_")
    {
        if(self::$continue){
            self::$thumbnail = self::image_resize(self::$filePath, ["width" => $width, "height" => $height], $prefix);
        }
        return new self;
    }

    private static function jpg_compress($source, $destination, $quality)
    {
        $image = imagecreatefromjpeg($source);
        return imagejpeg($image, $destination, $quality);
    }
  
    private static function png_compress($source, $destination, $quality)
    {
        if($quality > 9){
            $quality = 9;
        }
        $image = imagecreatefrompng($source);
        imageAlphaBlending($image, false);
        imageSaveAlpha($image, true);
        return imagepng($image, $destination, $quality);
    }


    private static function image_resize($path, $resize = ["width" => "300", "height" => "300"], $prefix = "thumb_")
    {
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
        $return = array("path" => $re_file_path, "url" => self::$dir_url . "/" . $re_basename);
    
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
