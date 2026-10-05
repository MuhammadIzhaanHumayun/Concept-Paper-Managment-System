<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	protected $user;

	public function __construct()
	{
		parent::__construct();
		$this->user = $this->session->userdata('user');
	}

	protected function require_login()
	{
		if (!$this->user) redirect('login');
	}

	protected function has_role($roleName)
	{
		return strcasecmp(trim((string)($this->user['role_name'] ?? '')), trim((string)$roleName)) === 0;
	}

	protected function is_admin()
	{
		return $this->has_role('Administrator');
	}

	protected function is_requestor()
	{
		return $this->has_role('Requestor');
	}


	protected function require_requestor()
	{
		$this->require_login();
		if (!$this->is_requestor()) show_error('Access denied.', 403);
	}

	protected function is_approver()
	{
		if (!$this->user) return false;

		if ($this->db->where('approver_id', (int)$this->user['id'])
			->count_all_results('project_approvals') > 0
		) {
			return true;
		}

		$roleId = (int)($this->user['role_id'] ?? 0);
		if (!$roleId) return false;

		return $this->db
			->where('role_id', $roleId)
			->where('is_required', 1)
			->where('department_id IS NOT NULL', null, false)
			->count_all_results('approval_workflows') > 0;
	}

	protected function can_manage_workflows()
	{
		if (!$this->user) return false;

		$roleId = (int)($this->user['role_id'] ?? 0);
		$departmentId = (int)($this->user['department_id'] ?? 0);
		if (!$roleId || !$departmentId) return false;

		// Workflow-definition permission is controlled dynamically by Administrator
		// on the Roles page. No role name (GM/NM/etc.) is hardcoded here.
		$role = $this->db->select('can_define_workflow')
			->where('id', $roleId)
			->get('roles')->row_array();

		if (empty($role['can_define_workflow'])) return false;

		// A permitted user can define workflows only for categories owned by
		// that user's own department.
		return $this->db->where('department_id', $departmentId)
			->count_all_results('department_project_categories') > 0;
	}

	protected function require_approver()
	{
		$this->require_login();
		if (!$this->is_approver()) {
			show_error('Access denied. You are not authorized to access the approval system.', 403);
		}
	}

	protected function require_admin()
	{
		$this->require_login();
		if (!$this->is_admin()) show_error('Access denied.', 403);
	}

	protected function render($view, $data = array())
	{
		$data['user'] = $this->user;
		$data['nav'] = array(
			'is_admin' => $this->is_admin(),
			'is_requestor' => $this->is_requestor(),
			'is_approver' => $this->is_approver(),
			'can_manage_workflows' => $this->can_manage_workflows()
		);

		$data['page_title'] = $data['page_title'] ?? 'Concept Paper Management System';
		$data['meta_description'] = $data['meta_description']
			?? $data['page_title'] . ' - Concept Paper Management System';

		$this->load->view('templates/header', $data);
		$this->load->view($view, $data);
		$this->load->view('templates/footer', $data);
	}
}
