<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
    }

    public function index() {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        $this->load->view('auth/login');
    }

    public function login()
{
    // CEK: harus lewat POST
    if (!$this->input->post()) {
        redirect('auth');
    }

    $username = trim($this->input->post('username', TRUE));
    $password = trim($this->input->post('password', TRUE));

    if (empty($username) || empty($password)) {
        $this->session->set_flashdata('error', 'Username dan password wajib diisi!');
        redirect('auth');
    }

    $user = $this->User_model->get_by_username($username);

    if ($user && password_verify($password, $user->password)) {

        $session = [
            'user_id'    => $user->id,
            'fullname'   => $user->fullname,
            'username'   => $user->username,
            'role_id'    => $user->role_id,
            'jurusan_id' => $user->jurusan_id,
            'logged_in'  => TRUE
        ];

        $this->session->set_userdata($session);
        redirect('dashboard');
    }

    $this->session->set_flashdata('error', 'Username atau password salah!');
    redirect('auth');
}

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth');
    }
}
