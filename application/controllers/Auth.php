<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Auth extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
	}
	public function login()
	{
		if ($this->user) {
			$isAdmin = (($this->user['role_name'] ?? '') === 'Administrator');
			redirect($isAdmin ? 'admin/users' : 'dashboard');
		}
		$data = array('error' => '');
		if ($this->input->method(TRUE) === 'POST') {
			$email = trim($this->input->post('email', TRUE));
			$password = (string)$this->input->post('password', FALSE);
			$user = $this->User_model->find_active_by_email($email);
			if ($user && password_verify($password, $user['password'])) {
				unset($user['password']);
				$this->session->sess_regenerate(TRUE);
				$this->session->set_userdata('user', $user);

				$isAdmin = (($user['role_name'] ?? '') === 'Administrator');
				redirect($isAdmin ? 'admin/users' : 'dashboard');
			}
			$data['error'] = 'Invalid email or password.';
		}
		$this->load->view('auth/login', $data);
	}
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
}
