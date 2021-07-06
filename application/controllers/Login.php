<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('User_model');
    }

    /**
     * Check HTTP method.
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
        if ($this->auth->guest() != TRUE) {
            redirect(base_url('dashboard'));
        }

        $this->load->view('auth/login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store()
    {
        if ($this->auth->guest() != TRUE) {
            redirect(base_url('dashboard'));
        }

        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $user = User_model::where('email', $email)->first();

        if (isset($user) && $this->bcrypt->check_password($password, $user->password)) {
            $this->auth->login($user);
            redirect(base_url('dashboard'));
        }

        redirect(base_url('login'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout()
    {
        if ($this->auth->authenticated() != TRUE) {
            redirect(base_url('login'));
        }
        $this->auth->logout();

        redirect(base_url('login'));
    }
}
