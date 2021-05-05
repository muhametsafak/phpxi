<?php
namespace Application\Controller;

use Load;
use MyTwoModel;

class Welcome
{
    public function main()
    {
        $data = [];
        MyTwoModel::main();
        Load::view("index", $data);
    }

    public function user($userid){
        echo $userid;
    }

}
