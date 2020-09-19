<?php
namespace PHPXI\SYSTEM;

class Upload{
  public $file;
  public $path;
  public $fileName;
  public $upload_file_path;
  public $upload_thumbnail_path;
  public $filePath;
  public $sizeLimit; //Bayt türünden yüklenebilir maksimum boyut
  public $fileUrl;
  public $file_valid_ext;
  public $pathinfo;
  public $error;
  public $upload_file_type; //Yüklenebilir dosya türleri
  public $upload_file_extension; //Yüklenebilir dosya uzantıları
  public $img_valid_ext = array('jpeg', 'jpg', 'png', 'gif', 'webp');
  public $img_thumbnail_valid_ext = array('jpeg','jpg', 'png', 'gif');
  public $thumbnail = array("width" => 300, "height" => 300, "beforeword" => "thumb_");
  public $thumbnailPath = "";
  public $thumbnailUrl = "";
  public $upload_jpg_compress;
  public $upload_png_compress;
  public $upload_dir_url;

  function __construct() {
    global $config;
    $this->sizeLimit = $config["uploads"]["sizeLimit"];
    $this->path = $config["uploads"]["path"];
    if(!file_exists($this->path)){
      mkdir($this->path);
    }
    $this->upload_file_type = $config["uploads"]["upload_file_type"];
    $this->upload_file_extension = $config["uploads"]["upload_file_extension"];
    $this->upload_jpg_compress = $config["uploads"]["upload_jpg_compress"];
    $this->upload_png_compress = $config["uploads"]["upload_png_compress"];
    $this->upload_dir_url = $config['upload']['dir_url'];
	}


  public function load($file){
    $this->file = $file;
    /*Yükleme yapılacak klasör yoksa, oluşturalım*/
    if(!is_dir($this->path)){
      mkdir($this->path);
    }


    /*Hata kontrolünü yapalım*/
    if(isset($this->file['error']) and $this->file['error'] != 0){
      $this->error = "ERROR : File Upload Error : ".$this->file['error']; 
    }elseif($this->file["size"] > $this->sizeLimit){
      $this->error = "ERROR : Max File Limit : ".$this->sizeLimit;
    }elseif(!in_array($this->file['type'], $this->upload_file_type)){
      $this->error = "ERROR : Unsupported File Type! Supported : ".implode(", ", $this->upload_file_type);
    }elseif(!in_array(strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION)), $this->upload_file_extension)){
      $this->error = "ERROR : Unsupported File Extension! Supported : ".implode(", ", $this->upload_file_extension);
    }else{
      // Hata yoksa dosyaya ait diğer değişkenleri sınıfa yükleyelim
      $this->fileName = basename($this->file['name']); //Dosyanın adını alalım
      $this->filePath = $this->path . $this->fileName;
      $this->pathinfo = pathinfo($this->filePath);
      /*Dosya konumunu kontrol edelim, sunucuda aynı isimde dosya varsa yeni dosya ismi versiyonlarını deneyelim*/
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
      $this->fileUrl = $this->upload_dir_url . "/" . $this->fileName;
    }
  
  }


  public function upload(){
    $return = false;
    if($this->error == ""){
      if($this->file["type"] == "image/jpeg" || $this->file["type"] == "image/jpg"){
        $upload = $this->jpg_compress($this->file['tmp_name'], $this->filePath, $this->upload_jpg_compress);
      }elseif($this->file["type"] == "image/png"){
        $upload = $this->png_compress($this->file['tmp_name'], $this->filePath, $this->upload_png_compress);
      }else{
        $upload = move_uploaded_file($this->file['tmp_name'], $this->filePath);
      }

      if($upload){
        $return = true;
        if(in_array(strtolower($this->file_valid_ext), $this->img_thumbnail_valid_ext)){
          $thumbnail = $this->image_resize($this->filePath, array("width" => $this->thumbnail["width"], "height" => $this->thumbnail["height"]));
          $this->thumbnailPath = $thumbnail["path"];
          $this->thumbnailUrl = $thumbnail["url"];
        }
        $root_path_character_size = strlen(PATH);
        $this->upload_file_path = substr($this->filePath, $root_path_character_size, strlen($this->filePath));
        $this->upload_thumbnail_path = substr($this->thumbnailPath, $root_path_character_size, strlen($this->thumbnailPath));
      }else{
        $this->error = "ERROR : Upload failed. Contact your System Administrator";
      }
    }
    return $return;
  }

  function jpg_compress($source, $destination, $quality) {
      $image = imagecreatefromjpeg($source);
      return imagejpeg($image, $destination, $quality);
  }

  function png_compress($source, $destination, $quality){
    if($quality > 9){
      $quality = 9;
    }
    $image = imagecreatefrompng($source);
    imageAlphaBlending($image, false);
    imageSaveAlpha($image, true);
    return imagepng($image, $destination, $quality);
  }

  function image_resize($path, $resize = array("width" => "300", "height" => "300"), $prefix = "thumb_"){
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
    $return = array("path" => $re_file_path, "url" => $this->upload_dir_url . "/" . $re_basename);

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
