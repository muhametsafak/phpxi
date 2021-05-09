<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace Application\Filters;

interface FilterInterface
{
    public function before($request);

    public function after($request, $responsive);
}
