<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Form;

class Form
{
    private static $output;

    public static function start($form = [])
    {
        self::$output = '<form';
        foreach ($form as $key => $value) {
            if ($key == "action") {
                self::$output .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
            } else {
                self::$output .= ' ' . $key . '="' . $value . '"';
            }
        }
        self::$output .= '>';
    }

    public static function open_div($div = [])
    {
        self::$output .= ' <div';
        foreach ($div as $key => $value) {
            self::$output .= ' ' . $key . '="' . $value . '" ';
        }
        self::$output .= '> ';
    }

    public static function close_div()
    {
        self::$output .= ' </div> ';
    }

    public static function label($key = "", $value = "")
    {
        $return = '<label for="' . $key . '">' . $value . '</label>';
        self::$output .= $return;
    }

    public static function input($input = "")
    {
        $return = '<input ';
        if (is_array($input)) {
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
        self::$output .= $return;
    }

    public static function select($select = [], $options = [], $selected_id = null)
    {
        self::$output .= '<select ';
        foreach ($select as $key => $value) {
            self::$output .= $key . '="' . $value . '" ';
        }
        self::$output .= '> ';
        foreach ($options as $key => $value) {
            self::$output .= self::option($key, $value, $selected_id);
        }
        self::$output .= ' </select>';
    }

    public static function textarea($textarea = [], $textarea_value = "")
    {
        $return = ' <textarea ';
        foreach ($textarea as $key => $value) {
            $return .= $key . '="' . $value . '" ';
        }
        $return .= '>' . $textarea_value . '</textarea>';
        self::$output .= $return;
    }

    public static function button($button = [], $text = "")
    {
        $return = '<button';
        foreach ($button as $key => $value) {
            $return .= ' ' . $key . '="' . $value . '"';
        }
        $return .= '>' . $text . '</button>';
        self::$output .= $return;
    }

    public static function option($key, $value, $selected_id = "")
    {
        if ($selected_id == $key) {
            $return = '<option value="' . $key . '" selected>' . $value . '</option>';
        } else {
            $return = '<option value="' . $key . '">' . $value . '</option>';
        }
        self::$output .= $return;
    }

    public static function html($html = "")
    {
        self::$output .= $html;
    }

    public static function output()
    {
        self::$output .= '</form>';
        $return = self::$output;
        self::$output = null;
        return $return;
    }
}
