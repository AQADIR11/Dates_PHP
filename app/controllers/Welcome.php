<?php

class Welcome extends DP_Controller
{
    public $model;
    
    public function __construct()
    {
        $this->model = $this->model('WelcomeModel');
        $this->helper('Welcome');
    }

    public function index()
    {
        print_r($_SESSION);
        echo $this->verify_csrf("/Dates_PHP/", true, 'QVhkdHIzTTNkSk9KbVVrQ2FHZGZSTkFlUjZheGR4UjdXRGRyR2pZdjdqMkQ0dnpVTWs=');
        $this->view('Welcome');
    }

}
