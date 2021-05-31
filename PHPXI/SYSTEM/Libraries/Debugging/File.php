<?php
/**
 * File.php
 *
 * This file is part of PHPXI.
 *
 * @package    File.php @ 2021-05-15T08:15:17.540Z
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

namespace PHPXI\Libraries\Debugging;

class File
{

    private string $file = '';

    private int $line = 1;

    private string $code = '';

    private int $before_line = 8;

    private int $after_line = 8;

    /**
     * @param string $file
     * @param int $line
     * @param int $before_line
     * @param int $after_line
     * @return mixed
     */
    public function read(string $file, int $line, int $before_line = 8, int $after_line = 8)
    {
        if (file_exists($file)) {
            $this->file = $file;
            $this->line = $line;
            $this->before_line = $before_line;
            $this->after_line = $after_line;
            $return = $this->prepare();
            $this->before_line = 8;
            $this->after_line = 8;
            $this->code = '';

            return $return;
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    private function line_numbers_to_read()
    {
        $lines = array();
        for ($i = ($this->line - 1); $i >= ($this->line - $this->before_line); $i--) {
            if ($i >= 0) {
                $lines[] = $i;
            }
        }
        $lines = array_reverse($lines);
        $lines[] = $this->line;
        for ($i = ($this->line + 1); $i <= ($this->line + $this->after_line); $i++) {
            if (!in_array($i, $lines)) {
                $lines[] = $i;
            }
        }

        return $lines;
    }

    /**
     * @return mixed
     */
    private function prepare(): string
    {
        $file_error_lines = $this->line_numbers_to_read();
        $open = file($this->file);
        $this->code .= '<pre class="first language-php">';
        foreach ($file_error_lines as $row) {
            $line = $row - 1;
            if (isset($open[$line])) {
                $line_code = str_replace(array("<", ">"), array("&lt;", "&gt;"), $open[$line]);
                switch ($line) {
                    case $this->line:
                        $this->code .= '<code class="error"><div class="line">' . $line . '</div>' . $line_code . '</code>';
                        break;
                    case $this->line - 1:
                        $this->code .= '<code class="error-rim"><div class="line">' . $line . '</div>' . $line_code . '</code>';
                        break;
                    case $this->line + 1:
                        $this->code .= '<code class="error-rim"><div class="line">' . $line . '</div>' . $line_code . '</code>';
                        break;
                    default:
                        $this->code .= '<code ><div class="line">' . $line . '</div>' . $line_code . '</code>';
                        break;
                }
            }
        }
        $this->code .= '</pre>';

        return $this->code;
    }

}
