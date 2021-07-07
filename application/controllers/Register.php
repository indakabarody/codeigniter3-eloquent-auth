<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Register extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('User_model');
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
     * Display the registration view.
     */
    public function create()
    {
        if (!$this->auth->guest()) {
            redirect(base_url('dashboard'));
        }

        $this->load->view('auth/register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store()
    {
        if (!$this->auth->guest()) {
            redirect(base_url('dashboard'));
        }

        $this->form_validation->set_rules('name', 'Name', 'required|max_length[255]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]|max_length[255]');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('password_confirmation', 'Password Confirmation', 'required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/register');
        } else {
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
    
            $user = User_model::create([
                'name' => $name,
                'email' => $email,
                'password' => $this->bcrypt->hash_password($password),
            ]);
    
            $this->auth->login($user);
    
            redirect(base_url('dashboard'));
        }
    }
}
