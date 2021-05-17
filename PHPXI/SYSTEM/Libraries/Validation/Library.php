<?php
/**
 * Validation/Library.php
 *
 * This file is part of PHPXI.
 *
 * @package    Validation/Library.php @ 2021-05-11T22:22:33.755Z
 * @author     Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * @copyright  Copyright © 2021 PHPXI Open Source MVC Framework
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt  GNU GPL 3.0
 * @version    1.6
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

namespace PHPXI\Libraries\Validation;

class Library
{
    /**
     * @var array
     */
    public $patterns = [
        'uri' => '[A-Za-z0-9-\/_?&=]+',
        'slug' => '[-a-z0-9_-]',
        'url' => '[A-Za-z0-9-:.\/_?&=#]+',
        'alpha' => '[\p{L}]+',
        'words' => '[\p{L}\s]+',
        'alphanum' => '[\p{L}0-9]+',
        'int' => '[0-9]+',
        'float' => '[0-9\.,]+',
        'tel' => '[0-9+\s()-]+',
        'text' => '[\p{L}0-9\s-.,;:!"%&()?+\'°#\/@]+',
        'file' => '[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+\.[A-Za-z0-9]{2,4}',
        'folder' => '[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+',
        'address' => '[\p{L}0-9\s.,()°-]+',
        'date_dmy' => '[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4}',
        'date_ymd' => '[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}',
        'email' => '[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+[.]+[a-z-A-Z]'
    ];

    /**
     * @var array
     */
    public $error = [];

    /**
     * @var array
     */
    public $data = [];

    /**
     * @var array
     */
    public $rule = [];

    /**
     * @param array $data
     * @return mixed
     */
    public function load(array $data = []): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param string $name
     * @param string $pattern
     * @return mixed
     */
    public function pattern(string $name, string $pattern): self
    {
        $this->patterns[$name] = $pattern;

        return $this;
    }

    /**
     * @param string $pattern_name
     */
    private function pattern_regex(string $pattern_name)
    {
        return '/^(' . $this->patterns[$pattern_name] . ')$/u';
    }

    /**
     * @param string $mail
     */
    public function mail(string $mail): bool
    {
        if (filter_var($mail, \FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            $this->error[] = $mail . "e-mail address is not valid";

            return false;
        }
    }

    /**
     * @param string $url
     */
    public function url(string $url): bool
    {
        if (filter_var($url, \FILTER_VALIDATE_URL)) {
            return true;
        } else {
            $this->error[] = "URL address is not valid";

            return false;
        }
    }

    /**
     * @param string $ip
     */
    public function ip(string $ip): bool
    {
        if (filter_var($ip, \FILTER_VALIDATE_IP)) {
            return true;
        } else {
            $this->error[] = "IP address is not valid";

            return false;
        }
    }

    /**
     * @param string $ip
     */
    public function ipv4(string $ip): bool
    {
        if (filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV4)) {
            return true;
        } else {
            $this->error[] = "IPv4 address is not valid";

            return false;
        }
    }

    /**
     * @param string $ip
     */
    public function ipv6(string $ip): bool
    {
        if (filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV6)) {
            return true;
        } else {
            $this->error[] = "IPv6 address is not valid";

            return false;
        }
    }

    /**
     * @param string $str
     */
    private function stringLength(string $str)
    {
        if (!is_string($str)) {
            return false;
        } else {
            return mb_strlen($str);
        }
    }

    /**
     * @param string $value
     * @param int $min_length
     */
    public function minLength(string $value, int $min_length): bool
    {
        if ($this->stringLength($value) >= $min_length) {
            return true;
        } else {
            $this->error[] = "The text must contain at least " . $min_length . " characters.";

            return false;
        }
    }

    /**
     * @param string $value
     * @param int $max_length
     */
    public function maxLength(string $value, int $max_length): bool
    {
        if ($this->stringLength($value) <= $max_length) {
            return true;
        } else {
            $this->error[] = "Text can contain up to " . $max_length . " characters.";

            return false;
        }
    }

    /**
     * @param string $value
     * @param int $min
     */
    public function min(string $value, int $min): bool
    {
        if ($min >= $value) {
            return true;
        } else {
            $this->error[] = "Must be a number less than or equal to " . $min . ".";

            return false;
        }
    }

    /**
     * @param string $value
     * @param int $max
     */
    public function max(string $value, int $max): bool
    {
        if ($max <= $value) {
            return true;
        } else {
            $this->error[] = "Must be a number greater than or equal to " . $max . ".";

            return false;
        }
    }

    /**
     * @param $value
     * @param $pattern
     */
    public function regex($value, $pattern): bool
    {
        if (preg_match($this->pattern_regex($pattern), $value)) {
            return true;
        } else {
            $this->error[] = "Data is not in valid format";

            return false;
        }
    }

    /**
     * @param $value
     * @return mixed
     */
    public function alpha($value)
    {
        return $this->regex($value, "alpha");
    }

    /**
     * @param $value
     * @return mixed
     */
    public function date($value)
    {
        $isDate = false;
        if ($value instanceof \DateTime) {
            $isDate = true;
        } else {
            $isDate = strtotime($value) !== false;
        }
        if (!$isDate) {
            $this->error[] = "Is not a valid date";
        }

        return $isDate;
    }

    /**
     * @param string $value
     * @param string $format
     */
    public function dateFormat(string $value, string $format): bool
    {
        $dateFormat = date_parse_from_format($format, $value);

        if ($dateFormat['error_count'] === 0 && $dateFormat['warning_count'] === 0) {
            return true;
        } else {
            $this->error[] = "Not a valid date format";

            return false;
        }
    }

    /**
     * @param string $value
     */
    public function required(string $value): bool
    {
        if (trim($value) != "") {
            return true;
        } else {
            $this->error[] = "Cannot be left blank";

            return false;
        }
    }

    /**
     * @param $rule
     * @param $dataId
     * @return mixed
     */
    public function rule($rule, $dataId): self
    {
        if (is_string($rule)) {
            $rule = [$rule];
        }
        if (is_string($dataId)) {
            $dataId = [$dataId];
        }
        $this->rule[] = [
            "rule" => $rule,
            "dataID" => $dataId
        ];

        return $this;
    }

    /**
     * @param $rule
     * @param $data
     */
    public function ruleExecutive($rule, $data)
    {
        if (is_string($data)) {
            $data = [$this->data[$data]];
        }
        preg_match("/\((.*)\)/u", $rule, $params);
        if (isset($params[0])) {
            $method = preg_replace("/\((.*)\)/u", null, $rule);
            $data[] = trim($params[0], "()");
        } else {
            $method = $rule;
        }
        call_user_func_array([__CLASS__, $method], $data);
    }

    public function validation(): bool
    {
        $this->error = [];
        foreach ($this->rule as $rule) {
            foreach ($rule['rule'] as $rule_row) {
                foreach ($rule['dataID'] as $data_row) {
                    $this->ruleExecutive($rule_row, $data_row);
                }
            }
        }
        if (sizeof($this->error) > 0) {
            return false;
        } else {
            return true;
        }
        $this->clear();
    }

    /**
     * @return mixed
     */
    public function error(): array
    {
        return $this->error;
    }

    private function clear(): void
    {
        $this->error = [];
        $this->rule = [];
    }

}
