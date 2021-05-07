<?php
namespace Application\Model;

use Config;

class MyModel
{

    public static function starting(){
        $starting = [
            "You can start developing from the <code title=\"".APPLICATION_PATH."\">/PHPXI/APPLICATION/</code> directory.",
            "PHPXI; it is a simple, straightforward and powerful framework.",
            "It can be learned in less than 1 hour."
        ];
        $id = rand(0, 2);
        return $starting[$id];
    }

}
