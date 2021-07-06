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
     * Display the registration view.
     */
    public function create()
    {
        $this->load->view('auth/register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store()
    {
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $passwordConfirmation = $this->input->post('password_confirmation');

        if ($password != $passwordConfirmation) {
            redirect(base_url('register'));
        }

        $user = User_model::create([
            'name' => $name,
            'email' => $email,
            'password' => $this->bcrypt->hash_password($password),
        ]);

        $this->auth->login($user);

        redirect(base_url('dashboard'));
    }
}
