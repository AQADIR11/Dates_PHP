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
        $this->view('Welcome');
    }

}
