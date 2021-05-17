<?php
namespace Application\Model;

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
