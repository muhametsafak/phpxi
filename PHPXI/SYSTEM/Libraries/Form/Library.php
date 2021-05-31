<?php
/**
 * Form/Library.php
 *
 * This file is part of PHPXI.
 *
 * @package    From/Library.php @ 2021-05-11T21:25:57.718Z
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

namespace PHPXI\Libraries\Form;

class Library
{
    /**
     * @var mixed
     */
    private $output;

    /**
     * @param array $form
     * @return mixed
     */
    public function start(array $form = []): self
    {
        $this->output = '<form';
        foreach ($form as $key => $value) {
            if ($key == "action") {
                $this->output .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
            } else {
                $this->output .= ' ' . $key . '="' . $value . '"';
            }
        }
        $this->output .= '>';

        return $this;
    }

    /**
     * @param array $div
     * @return mixed
     */
    public function open_div(array $div = []): self
    {
        $this->output .= ' <div';
        foreach ($div as $key => $value) {
            $this->output .= ' ' . $key . '="' . $value . '" ';
        }
        $this->output .= '> ';

        return $this;
    }

    /**
     * @return mixed
     */
    public function close_div(): self
    {
        $this->output .= ' </div> ';

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return mixed
     */
    public function label(string $key = "", string $value = ""): self
    {
        $this->output .= '<label for="' . $key . '">' . $value . '</label>';

        return $this;
    }

    /**
     * @param array $input
     * @return mixed
     */
    public function input(array $input = []): self
    {
        $return = '<input ';
        if (sizeof($input) > 0) {
            if (!isset($input['type'])) {
                $return .= ' type="text" ';
            }
            foreach ($input as $key => $value) {
                $return .= $key . '="' . $value . '" ';
            }
        } else {
            $return .= ' type="text" ';
        }
        $return .= ' />';
        $this->output .= $return;

        return $this;
    }

    /**
     * @param array $select
     * @param array $options
     * @param $selected_id
     * @return mixed
     */
    public function select(array $select = [], array $options = [], $selected_id = null): self
    {
        $this->output .= '<select ';
        foreach ($select as $key => $value) {
            $this->output .= $key . '="' . $value . '" ';
        }
        $this->output .= '> ';
        foreach ($options as $key => $value) {
            $this->output .= $this->option($key, $value, $selected_id);
        }
        $this->output .= ' </select>';

        return $this;
    }

    /**
     * @param array $textarea
     * @param string $textarea_value
     * @return mixed
     */
    public function textarea(array $textarea = [], string $textarea_value = ""): self
    {
        $return = ' <textarea ';
        foreach ($textarea as $key => $value) {
            $return .= $key . '="' . $value . '" ';
        }
        $return .= '>' . $textarea_value . '</textarea>';
        $this->output .= $return;

        return $this;
    }

    /**
     * @param array $button
     * @param string $text
     * @return mixed
     */
    public function button(array $button = [], string $text = ""): self
    {
        $return = '<button';
        foreach ($button as $key => $value) {
            $return .= ' ' . $key . '="' . $value . '"';
        }
        $return .= '>' . $text . '</button>';
        $this->output .= $return;

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @param $selected_id
     * @return mixed
     */
    public function option(string $key, string $value, $selected_id = ""): self
    {
        if ($selected_id == $key) {
            $return = '<option value="' . $key . '" selected>' . $value . '</option>';
        } else {
            $return = '<option value="' . $key . '">' . $value . '</option>';
        }
        $this->output .= $return;

        return $this;
    }

    /**
     * @param string $html_code
     * @return mixed
     */
    public function html(string $html_code = ""): self
    {
        $this->output .= $html_code;

        return $this;
    }

    /**
     * @return mixed
     */
    public function output(): string
    {
        $this->output .= '</form>';
        $return = $this->output;
        $this->output = null;

        return $return;
    }
}
