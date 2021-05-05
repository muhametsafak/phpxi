<?php
namespace PHPXI\Libraries\Validation;

class Validation
{
    public static $patterns = [
        'uri'           => '[A-Za-z0-9-\/_?&=]+',
        'slug'          => '[-a-z0-9_-]',
        'url'           => '[A-Za-z0-9-:.\/_?&=#]+',
        'alpha'         => '[\p{L}]+',
        'words'         => '[\p{L}\s]+',
        'alphanum'      => '[\p{L}0-9]+',
        'int'           => '[0-9]+',
        'float'         => '[0-9\.,]+',
        'tel'           => '[0-9+\s()-]+',
        'text'          => '[\p{L}0-9\s-.,;:!"%&()?+\'°#\/@]+',
        'file'          => '[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+\.[A-Za-z0-9]{2,4}',
        'folder'        => '[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+',
        'address'       => '[\p{L}0-9\s.,()°-]+',
        'date_dmy'      => '[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4}',
        'date_ymd'      => '[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}',
        'email'         => '[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+[.]+[a-z-A-Z]'
    ];

    public static $error = [];

    public static $data = [];

    public static $rule = [];

    public static function load($data = [])
    {
        self::$data = $data;
    }

    public static function pattern($name, $pattern)
    {
        self::$patterns[$name] = $pattern;
    }

    private static function pattern_regex($pattern_name)
    {
        return '/^('.self::$patterns[$pattern_name].')$/u';
    }

    public static function mail($mail)
    {
        if(filter_var($mail, \FILTER_VALIDATE_EMAIL)){
            return true;
        }else{
            self::$error[] = "e-mail address is not valid";
            return false;
        }
    }

    public static function url($url)
    {
        if(filter_var($url, \FILTER_VALIDATE_URL)){
            return true;
        }else{
            self::$error[] = "URL address is not valid";
            return false;
        }
    }

    public static function ip($ip)
    {
        if(filter_var($ip, \FILTER_VALIDATE_IP)){
            return true;
        }else{
            self::$error[] = "IP address is not valid";
            return false;
        }
    }

    public static function ipv4($ip)
    {
        if(filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV4)){
            return true;
        }else{
            self::$error[] = "IPv4 address is not valid";
            return false;
        }
    }

    public static function ipv6($ip)
    {
        if(filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV6)){
            return true;
        }else{
            self::$error[] = "IPv6 address is not valid";
            return false;
        }
    }

    private static function stringLength($str)
    {
        if(!is_string($str)){
            return false;
        }else{
            return mb_strlen($str);
        }
    }

    public static function minLength($value, int $min_length)
    {
        if(self::stringLength($value) >= $min_length){
            return true;
        }else{
            self::$error[] = "The text must contain at least ".$min_length." characters.";
            return false;
        }
    }


    public static function maxLength($value, int $max_length)
    {
        if(self::stringLength($value) <= $max_length){
            return true;
        }else{
            self::$error[] = "Text can contain up to ".$max_length." characters.";
            return false;
        }
    }

    public static function min($value, $min)
    {
        if($min >= $value){
            return true;
        }else{
            self::$error[] = "Must be a number less than or equal to ".$min.".";
            return false;
        }
    }

    public static function max($value, $max)
    {
        if($max <= $value){
            return true;
        }else{
            self::$error[] = "Must be a number greater than or equal to ".$max.".";
            return false;
        }
    }

    public static function regex($value, $pattern){
        if(preg_match(self::pattern_regex($pattern), $value)){
            return true;
        }else{
            self::$error[] = "Data is not in valid format";
            return false;
        }
    }

    public static function alpha($value){
        return self::regex($value, "alpha");
    }

    public static function date($value){
        $isDate = false;
        if ($value instanceof \DateTime) {
            $isDate = true;
        }else{
            $isDate = strtotime($value) !== false;
        }
        if(!$isDate){
            self::$error[] = "Is not a valid date";
        }
        return $isDate;
    }

    public static function dateFormat($value, $format){
        $dateFormat = date_parse_from_format($format, $value);

        if($dateFormat['error_count'] === 0 && $dateFormat['warning_count'] === 0){
            return true;
        }else{
            self::$error[] = "Not a valid date format";
            return false;
        }
    }

    public static function required($value){
        if(trim($value) != ""){
            return true;
        }else{
            self::$error[] = "Cannot be left blank";
            return false;
        }
    }

    public static function rule($rule, $dataId)
    {
        if(is_string($rule)){
            $rule = [$rule];
        }
        if(is_string($dataId)){
            $dataId = [$dataId];
        }


        self::$rule[] = [
            "rule" => $rule,
            "dataId" => $dataId
        ];
    }

    public static function ruleExecutive($rule, $data){
        if(is_string($data)){
            $data = [$data];
        }
        preg_match("/\((.*)\)/u", $rule, $params);
        if(isset($params[0])){
            $method = preg_replace("/\((.*)\)/u", null, $rule);
            $data[] = $params[0];
        }else{
            $method = $rule;
        }
        call_user_func_array([__CLASS__, $method], $data);
    }

    public static function validation(){
        self::$error = [];
        foreach(self::$rule as $rule){
            foreach($rule['rule'] as $rule_row){
                foreach($rule['dataID'] as $data_row){
                    self::ruleExecutive($rule_row, $data_row);
                }
            }
        }
        if(sizeof(self::$error) > 0){
            return false;
        }else{
            return true;
        }
        self::clear();
    }

    public static function error()
    {
        return self::$error;
    }

    private static function clear(){
        self::$error = [];
        self::$rule = [];
    }

}
