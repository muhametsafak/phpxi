<?php
namespace Demo;

class Sabit
{
    public static $data = [];

    public static function add($key, $value)
    {
        self::$data[$key] = $value;
    }
}

class Al extends Sabit
{

    public function __set($key, $value)
    {
        Sabit::add($key, $value);
    }

    public function __get($key)
    {
        if (isset(Sabit::$data[$key])) {
            return Sabit::$data[$key];
        } else {
            return $key . ' bulunamadı.';
        }
    }

    public function data()
    {
        return Sabit::$data;
    }

}

$al = new \Demo\Al();

$al->demo = "Deneme Value";
$al->bardak = "Kahve";

var_dump($al->data());

$db = new \Demo\Al();

var_dump($db->demo);

/*
$al::$data["ali"] = "veri";

var_dump($al::$data);

echo "<br />";
echo "<br />";
echo "<br />";
echo "<br />";
echo "<br />";

$al = new \Demo\Al();
var_dump($al::$data);
 */
