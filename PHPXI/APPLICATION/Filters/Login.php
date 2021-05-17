<?php
namespace Application\Filters;

use \PHPXI\Libraries\Request\Request;
use \PHPXI\Libraries\Response\Response;

use \PHPXI\Libraries\Input\Input as Input;
use \PHPXI\Libraries\Session\Session as Session;

class Login implements \Interfaces\Filter
{
    /**
     * @param $request
     */
    public function before(Request $request): bool
    {
        $mail = "webmaster@localhost.net";
        $password = "123456";
        
        if(Input::post("mail", ["required", "mail"]) == $mail && Input::post("password") == $password){
            Session::set("auth", true);
            Session::set("mail", Input::post("mail"));
            $dashboard_url = base_url(route("dashboard"));
            redirect($dashboard_url, 3);
            $request->Login_Status = true;
        }else{
            $request->Login_Status = false;
        }
        return true;
    }

    /**
     * @param $request
     * @param $response
     */
    public function after(Request $request, Response $response): bool
    {
        return true;
    }

}
