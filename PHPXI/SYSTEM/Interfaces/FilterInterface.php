<?php
namespace Application\Filters;

interface FilterInterface
{
    public function before();

    public function after();
}
