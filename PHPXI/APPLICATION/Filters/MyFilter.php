<?php
namespace Application\Filters;

class MyFilter implements FilterInterface
{
    public function before()
    {
        return true;
    }

    public function after()
    {
        return true;
    }
    
}
