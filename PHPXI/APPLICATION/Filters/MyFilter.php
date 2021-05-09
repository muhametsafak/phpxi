<?php
namespace Application\Filters;

class MyFilter implements FilterInterface
{
    public function before($request)
    {
        $request->seo = "google";
        return true;
    }

    public function after($request, $responsive)
    {
        return true;
    }

}
