<?php
namespace Application\Controller;


class Welcome extends \PHPXI\Controller
{
    public function main()
    {
        $data = [];
        $data['starting'] = $this->mymodel->starting();
        $this->load->view("index", $data);
    }

}
