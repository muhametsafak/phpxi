<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace Application\Filters;

interface FilterInterface
{
    public function before();

    public function after();
}
