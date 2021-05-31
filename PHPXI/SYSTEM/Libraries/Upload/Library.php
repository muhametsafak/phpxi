<?php
/**
 * Upload/Library.php
 *
 * This file is part of PHPXI.
 *
 * @package    Upload/Library.php @ 2021-05-11T22:10:52.483Z
 * @author     Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * @copyright  Copyright © 2021 PHPXI Open Source MVC Framework
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt  GNU GPL 3.0
 * @version    1.6.2
 * @link       http://phpxi.net
 *
 * PHPXI is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PHPXI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PHPXI.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace PHPXI\Libraries\Upload;

class Library
{

    /**
     * @var mixed
     */
    private $file;
    /**
     * @var mixed
     */
    private $error;
    /**
     * @var mixed
     */
    private $continue = true;

    /**
     * @var mixed
     */
    private $sizeLimit;
    /**
     * @var string
     */
    private $fileName = "";
    /**
     * @var mixed
     */
    private $path;
    /**
     * @var mixed
     */
    private $dir_url;
    /**
     * @var mixed
     */
    private $file_type;
    /**
     * @var mixed
     */
    private $file_extension;
    /**
     * @var mixed
     */
    private $jpg_compress;
    /**
     * @var mixed
     */
    private $png_compress;
    /**
     * @var array
     */
    private $resize = ["width" => "300", "height" => "300"];

    /**
     * @var mixed
     */
    private $pathinfo;
    /**
     * @var mixed
     */
    private $filePath;
    /**
     * @var mixed
     */
    private $fileUrl;
    /**
     * @var array
     */
    private $thumbnail = [];

    /**
     * @param array $config
     * @return mixed
     */
    public function config(array $config = []): self
    {
        foreach ($config as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
    }

    /**
     * @param string $path
     * @return mixed
     */
    public function path(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param string $url
     * @return mixed
     */
    public function url(string $url): self
    {
        $this->dir_url = $url;

        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function name(string $name): self
    {
        $this->fileName = $name;

        return $this;
    }

    /**
     * @param int $size
     * @return mixed
     */
    public function maxSize(int $size): self
    {
        $this->sizeLimit = $size;

        return $this;
    }

    function

    return () {
        if ($this->error != "") {
            return $this->error;
        } else {
            $return = array(
                "name" => $this->fileName,
                "size" => $this->file["size"],
                "type" => $this->file["type"],
                "path" => $this->filePath,
                "url" => $this->fileUrl,
                "thumbnail" => $this->thumbnail,
                "status" => $this->continue
            );

            return $return;
        }
    }

    /**
     * @param $file
     * @return mixed
     */
    public function file($file): self
    {
        $this->file = $file;
        if (!is_dir($this->path)) {
            if (!mkdir($self::path)) {
                $this->error = __r("upload_error_not_create_path", "ERROR : Could not create path : {path}", ["path" => $path]);
                $this->continue = false;
            }
        }
        if ($this->continue) {
            if (isset($file['error']) and $file['error'] != 0) {
                $this->error = __r("upload_error_file_error", "ERROR : File Error : {error}", ["error" => $file['error']]);
                $this->continue = false;
            } elseif ($file['size'] > $this->sizeLimit) {
                $this->error = __r("upload_error_file_max_limit", "ERROR : Max File Limit : {limit}", ["limit" => $this->sizeLimit]);
                $this->continue = false;
            } elseif (!in_array(strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)), $this->file_extension)) {
                $this->error = __r("upload_error_unsupported_extension", "ERROR : Unsupported File Extension! Supported : {file_extensions}", ["file_extensions" => implode(", ", $this->file_extension)]);
                $this->continue = false;
            } elseif (!in_array($file['type'], $this->file_type) and !in_array(mime_content_type($file["tmp_name"]), $this->file_type)) {
                $this->error = __r("upload_error_unsupported_type", "ERROR : Unsupported File Type! Supported : {file_types}", ["file_types" => implode(", ", $this->file_type)]);
                $this->continue = false;
            } else {
                if ($this->fileName == "") {
                    $this->fileName = basename($this->file["name"]);
                }
                $this->filePath = $this->path . $this->fileName;
                $this->pathinfo = pathinfo($this->filePath);
                if (file_exists($this->filePath)) {
                    $i = 0;
                    do {
                        $i++;
                        $this->fileName = $this->pathinfo["filename"] . '-' . $i . '.' . $this->pathinfo["extension"];
                        $this->filePath = $this->path . $this->fileName;
                        if (file_exists($this->filePath)) {
                            $re_file_path_while = true;
                        } else {
                            $re_file_path_while = false;
                        }
                    } while ($re_file_path_while);
                }
                $this->pathinfo = pathinfo($this->filePath);
                $this->file_valid_ext = $this->pathinfo['extension'];
                $this->fileUrl = $this->dir_url . "/" . $this->fileName;
                $this->continue = true;
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function handle(): self
    {
        if ($this->continue) {
            if ($this->file["type"] == "image/jpeg" || $this->file["type"] == "image/jpg" || $this->file["type"] == "image/png") {
                if ($this->file["type"] == "image/jpeg" || $this->file["type"] == "image/jpg") {
                    $upload = self::jpg_compress($this->file['tmp_name'], $this->filePath, $this->jpg_compress);
                } else {
                    $upload = self::png_compress($this->file['tmp_name'], $this->filePath, $this->png_compress);
                }
            } else {
                $upload = move_uploaded_file($this->file['tmp_name'], $this->filePath);
            }
            if ($upload) {
                $this->continue = true;
            } else {
                $this->error = __r("upload_error_handler_error", "An error occurred during the uploaded process.");
                $this->continue = false;
            }
        }

        return $this;
    }

    /**
     * @param int $width
     * @param int $height
     * @param string $prefix
     * @return mixed
     */
    public function thumbnail(int $width = 300, int $height = 300, string $prefix = "thumb_"): self
    {
        if ($this->continue) {
            $this->thumbnail = self::image_resize($this->filePath, ["width" => $width, "height" => $height], $prefix);
        }

        return $this;
    }

    /**
     * @param $source
     * @param $destination
     * @param $quality
     */
    private function jpg_compress($source, $destination, $quality)
    {
        $image = imagecreatefromjpeg($source);

        return imagejpeg($image, $destination, $quality);
    }

    /**
     * @param $source
     * @param $destination
     * @param $quality
     */
    private function png_compress($source, $destination, $quality)
    {
        if ($quality > 9) {
            $quality = 9;
        }
        $image = imagecreatefrompng($source);
        imageAlphaBlending($image, false);
        imageSaveAlpha($image, true);

        return imagepng($image, $destination, $quality);
    }

    /**
     * @param $path
     * @param array $resize
     * @param $prefix
     * @return mixed
     */
    private function image_resize($path, $resize = ["width" => "300", "height" => "300"], $prefix = "thumb_")
    {
        $pathinfo = pathinfo($path);

        $dirname = $pathinfo["dirname"];
        $basename = $pathinfo["basename"];

        $re_basename = $prefix . $basename;
        $re_file_path = $dirname . '/' . $re_basename;

        if (file_exists($re_file_path)) {
            $i = 0;
            do {
                $i++;
                $re_basename = $prefix . $pathinfo["filename"] . '-' . $i . '.' . $pathinfo["extension"];
                $re_file_path = $dirname . '/' . $re_basename;
                if (file_exists($re_file_path)) {
                    $re_file_path_while = true;
                } else {
                    $re_file_path_while = false;
                }
            } while ($re_file_path_while);
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
        } elseif ($getImageSize['mime'] == 'image/gif') {
            $old_image = imagecreatefromgif($path);
            $new_image = imagecreatetruecolor($new_width, $new_height);
            imagecopyresized($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $getImageSize[0], $getImageSize[1]);
            imagegif($new_image, $re_file_path);
        } elseif ($getImageSize['mime'] == 'image/png') {
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
