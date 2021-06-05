<?php
/**
 * ResponseLibrary.php
 *
 * This file is part of PHPXI.
 *
 * @package    ResponseLibrary.php @ 2021-05-17T09:12:12.398Z
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


namespace PHPXI\Libraries\Http;

if(!defined("INDEX")){ die("You are not authorized to access"); }

class ResponseLibrary
{

    public function getStatus()
    {
        return http_response_code();
    }


    public function setStatus(int $response = 200)
    {
        return http_response_code($response);
    }

    public function header(string $name, string $value): self
    {
        header($name . ": " . $value);
        return $this;
    }

    public function content(string $content = "", int $response = 200)
    {
        $this->setStatus($response);
        echo $content;
        return $this;
    }

    public function download($path)
    {
        if(file_exists($path)){
            $this->header("Content-length", filesize($path));
            $this->header("Content-Type", "application/octet-stream");
            $this->header("Content-Disposition", 'attachment; filename="' . $path . '"');
            readfile($path);
        }
    }

}
