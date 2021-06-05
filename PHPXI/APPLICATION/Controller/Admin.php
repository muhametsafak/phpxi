<?php
namespace Application\Controller;

if(!defined("INDEX")){ die("You are not authorized to access"); }

class Admin extends \PHPXI\Controller
{
    public function dashboard()
    {
        $data = [];
        $data["title"] = "Dashboard";
        $this->load->view("/admin/dashboard", $data);
    }


    public function login()
    {
        $data = [];
        $data['title'] = "Login Page";
        if($this->request->method() == "post"){
            $data['login_status'] = $this->request->Login_Status;
        }
        $this->load->view("admin/login", $data);
    }

    public function logout()
    {
        $this->session->set("auth", false);
        $this->session->unset();
        $this->session->destroy();
        redirect(base_url(route("dashboard")));
    }

}
