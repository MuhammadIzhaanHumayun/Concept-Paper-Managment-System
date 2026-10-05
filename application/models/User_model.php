<?php
defined('BASEPATH') or exit('No direct script access allowed');
class User_model extends CI_Model
{
	public function find_active_by_email($email)
	{
		return $this->db->select('u.*, r.role_name, d.department_name')
			->from('users u')->join('roles r', 'r.id=u.role_id')->join('departments d', 'd.id=u.department_id', 'left')
			->where('u.email', $email)->where('u.status', 'ACTIVE')->limit(1)->get()->row_array();
	}
}
