<?php
/**
 * Cache/Library.php
 *
 * This file is part of PHPXI.
 *
 * @package    Cache/Library.php @ 2021-05-11T19:48:00.725Z
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

namespace PHPXI\Libraries\Cache;

use PHPXI\Libraries\Base\Base as Base;

class Library
{

    /**
     * @param string $path
     * @return mixed
     */
    public function path(string $path): self
    {
        Base::set("path", $path, "cache");
        Base::set(
            "fullpath",
            Base::get("path", "cache") . '/' . Base::get("file", "cache"),
            "cache"
        );

        return $this;
    }

    /**
     * @param int $second
     * @return mixed
     */
    public function timeout(int $second = 86400): self
    {
        Base::set("timeout", $second, "cache");

        return $this;
    }

    /**
     * @param string $content
     * @return mixed
     */
    public function content(string $content = ""): self
    {
        Base::set("content", $content, "cache");

        return $this;
    }

    /**
     * @param string $file
     * @return mixed
     */
    public function file(string $file): self
    {
        Base::set("file", $file, "cache");
        Base::set(
            "fullpath",
            Base::get("path", "cache") . '/' . Base::get("file", "cache"),
            "cache"
        );

        return $this;
    }

    /**
     * @param bool $gzip
     * @return mixed
     */
    public function gzip(bool $gzip): self
    {
        Base::set("gzip", $gzip, "cache");

        return $this;
    }

    /**
     * @return mixed
     */
    public function cache(): string
    {
        if ($this->is()) {
            if ($this->is_timeout()) {
                return $this->write();
            } else {
                return $this->read();
            }
        } else {
            $this->create();
        }

        return Base::get("content", "cache");
    }

    /**
     * @return mixed
     */
    public function create(): string
    {
        $fullpath = Base::get("fullpath", "cache");
        $content = Base::get("content", "cache");
        touch($fullpath);
        $open = fopen($fullpath, "w+");
        fwrite($open, $content);
        fclose($open);

        return $content;
    }

    public function is(): bool
    {
        if (file_exists(Base::get("fullpath", "cache"))) {
            return true;
        } else {
            return false;
        }
    }

    public function is_timeout(): bool
    {
        if ((time() - filemtime(Base::get("fullpath", "cache"))) > Base::get("timeout", "cache")) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public static function read(): string
    {
        $open = fopen(Base::get("fullpath", "cache"), "r");
        $content = fread($open, filesize(Base::get("fullpath", "cache")));
        fclose($open);
        Base::set("content", $content, "cache");

        return $content;
    }

    public static function write(): string
    {
        $open = fopen(Base::get("fullpath", "cache"), "w+");
        fwrite($open, Base::get("content", "cache"));
        fclose($open);

        return Base::get("content", "cache");
    }

}
