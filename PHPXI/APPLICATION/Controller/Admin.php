<?php
namespace Application\Controller;

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
