<?php
namespace Application\Model;

if(!defined("INDEX")){ die("You are not authorized to access"); }

class MyModel extends \PHPXI\Model
{

    /**
     * @return mixed
     */
    public function starting()
    {
        return starting_msg();
    }

}
