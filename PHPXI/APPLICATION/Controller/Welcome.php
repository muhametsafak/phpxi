<?php
namespace Application\Controller;

use Load, Lang;
use MyModel;

class Welcome
{
    public function main()
    {
        $data = [];
        $data['starting'] = MyModel::starting();
        Lang::set("en");
        Load::view("index", $data);
    }


}
