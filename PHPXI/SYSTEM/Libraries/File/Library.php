<?php
/**
 * File/Library.php
 *
 * This file is part of PHPXI.
 *
 * @package    File/Library.php @ 2021-05-11T21:08:10.891Z
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
namespace PHPXI\Libraries\File;

class Library
{
    /**
     * @var mixed
     */
    public $path;

    /**
     * @var mixed
     */
    private $file;

    /**
     * @param sting $path
     * @return mixed
     */
    public function load(sting $path): self
    {
        if ($this->exists($path)) {
            $this->path = $path;
        }

        return $this;
    }

    /**
     * @param string $path
     */
    public function exists(string $path = "")
    {
        if ($path == "") {
            $path = $this->path;
        }
        if (file_exists($path)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $path
     * @return mixed
     */
    public function read(string $path = "")
    {
        if ($path == "") {
            $path = $this->path;
        }
        if ($this->exists($path)) {
            $this->path = $path;
            $this->filesize = filesize($this->path);
            $this->file = fopen($this->path);
            $return = fread($this->file, $this->size());
            fclose($this->file);

            return $return;
        } else {
            return false;
        }
    }

    public function empty(string $path = "") {
        if ($path == "") {
            $path = $this->path;
        }
        if ($this->exists($path)) {
            return $this->rewrite("", $path);
        } else {
            return false;
        }
    }

    /**
     * @param string $content
     * @param string $path
     */
    public function write(string $content = "", string $path = ""): bool
    {
        if ($path == "") {
            $path = $this->path;
        }
        if ($this->exists($path)) {
            $this->file = fopen($path, 'a');
            if (fwrite($path, $content) !== false) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param string $content
     * @param string $path
     */
    public function rewrite(string $content = "", string $path = ""): bool
    {
        if ($path == "") {
            $path = $this->path;
        }
        if ($this->exists($path)) {
            $this->file = fopen($path, 'w+');
            if (fwrite($path, $content) !== false) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param string $path
     */
    public function time(string $path = "")
    {
        if ($path == "") {
            $path = $this->path;
        }
        if ($this->exists($path)) {
            return filemtime($path);
        } else {
            return false;
        }
    }

    /**
     * @param string $path
     */
    public function size(string $path = "")
    {
        if ($path == "") {
            $path = $this->path;
        }
        if ($this->exists($path)) {
            return filesize($path);
        } else {
            return false;
        }
    }

    /**
     * @param string $path
     */
    public function mime(string $path = "")
    {
        if ($path == "") {
            $path = $this->path;
        }
        if ($this->exists($path)) {
            return mime_content_type($path);
        } else {
            return false;
        }
    }

    /**
     * @param string $copy_path
     * @param string $file_path
     */
    public function copy(string $copy_path, string $file_path = ""): bool
    {
        if ($file_path == "") {
            $file_path = $this->path;
        }
        if (copy($file_path, $copy_path)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $new_path
     * @param string $path
     */
    public function rename(string $new_path, string $path = ""): bool
    {
        if ($path == "") {
            $path = $this->path;
        }
        if (rename($path, $new_path)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $new_path
     * @param string $path
     */
    public function move(string $new_path, string $path = ""): bool
    {
        if ($path == "") {
            $path = $this->path;
        }
        if (rename($path, $new_path)) {
            return true;
        } else {
            return false;
        }
    }

}
