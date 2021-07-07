<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth {

    /**
     * Get an instance of CodeIgniter
     *
     * @access    protected
     * @return    void
     */
    protected function ci()
    {
        return get_instance();
    }

    /**
     * Check the user credentials.
     *
     * @param  $email, $password
     * @return bool
     */
    public function authenticate_user($email, $password = null)
    {
        $this->ci()->load->model('User_model');

        $user = User_model::where('email', $email)->first();

        if (isset($user) && $this->ci()->bcrypt->check_password($password, $user->password)) {
            $this->login($user);
            return true;
        } else {
            return false;
        }

    }

    /**
     * Login the user.
     *
     * @param  $user
     * @return bool
     */
    public function login($user)
    {
        $this->ci()->session->set_userdata([
            'id' => $user->id,
        ]);

        return true;
    }

    /**
     * Check if user is logged in.
     *
     * @return bool
     */
    public function authenticated()
    {
        if ($this->ci()->session->userdata('id') != NULL) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if user is not logged in.
     *
     * @return bool
     */
    public function guest()
    {
        if ($this->ci()->session->userdata('id') == NULL) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get user login information.
     *
     * @return $user
     */
    public function user()
    {
        $this->ci()->load->model('User_model');
        
        $userId = $this->ci()->session->userdata('id');
        $user = User_model::where('id', $userId)->first();
        return $user;
    }

    /**
     * Destroy user session.
     *
     * @return bool
     */
    public function logout()
    {
        $this->ci()->session->sess_destroy();
        return true;
    }
}