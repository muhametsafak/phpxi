<?php
namespace Application\Controller;


class Welcome extends \PHPXI\Controller
{
    public function main()
    {
        $data = [];
        $this->lang->set("en");
        $data['starting'] = $this->mymodel->starting();
        $this->load->view("index", $data);
    }

}
