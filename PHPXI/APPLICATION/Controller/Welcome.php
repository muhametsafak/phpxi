<?php
namespace Application\Controller;

use Config;
use Lang;
use Load;
use MyModel;

class Welcome extends Controller
{
    public function main()
    {
        $data = [];
        $data['starting'] = MyModel::starting();
        Lang::set("en");
        Load::view("index", $data);
    }

}
