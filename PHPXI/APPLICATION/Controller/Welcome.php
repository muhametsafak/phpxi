<?php
namespace Application\Controller;

if(!defined("INDEX")){ die("You are not authorized to access"); }


class Welcome extends \PHPXI\Controller
{
    public function main()
    {
        $data = [];
        $data['starting'] = $this->mymodel->starting();
        $this->load->view("index", $data);
    }

}
