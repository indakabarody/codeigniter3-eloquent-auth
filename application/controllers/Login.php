<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    /**
     * Check HTTP request method.
     */
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->store();
        } else {
            $this->create();
        }
    }

    /**
     * Display the login view.
     */
    public function create()
    {
        if (!$this->auth->guest()) {
            redirect(base_url('dashboard'));
        }

        $this->load->view('auth/login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store()
    {
        if (!$this->auth->guest()) {
            redirect(base_url('dashboard'));
        }

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/login');
        } else {
            $email = $this->input->post('email');
            $password = $this->input->post('password');
    
            if ($this->auth->authenticate_user($email, $password)) {
                redirect(base_url('dashboard'));
            } else {
                $data['errors'] = 'These credentials do not match our records.';
                $this->load->view('auth/login', $data);
            }
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout()
    {
        if (!$this->auth->authenticated()) {
            redirect(base_url('login'));
        }
        $this->auth->logout();

        redirect(base_url('login'));
    }
}
