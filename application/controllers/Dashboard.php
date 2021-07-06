<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if ($this->auth->authenticated() != TRUE) {
            redirect(base_url('login'));
        }
    }

    /**
     * Display view
     */
    public function index()
    {
        $this->load->view('user/dashboard');
    }
}
