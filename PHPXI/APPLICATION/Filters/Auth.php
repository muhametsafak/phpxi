<?php
namespace Application\Filters;

use \PHPXI\Libraries\Request\Request;
use \PHPXI\Libraries\Response\Response;

use \PHPXI\Libraries\Session\Session as Session;

class Auth implements \Interfaces\Filter
{
    /**
     * @param $request
     */
    public function before(Request $request): bool
    {
        if(Session::get("auth")){
            return true;
        }else{
            redirect(base_url(route("login")));
            return false;
        }
    }

    /**
     * @param $request
     * @param $response
     */
    public function after(Request $request, Response $response): bool
    {
        if(Session::get("auth")){
            return true;
        }else{
            redirect(base_url(route("login")));
            return false;
        }
    }

}