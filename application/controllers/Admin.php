<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Admin extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->require_admin();
		$this->load->model('Admin_model');
	}
	public function users()
	{
		$this->render('admin/users', array('page_title' => 'Users', 'users' => $this->Admin_model->users()));
	}

	private function remember_user_form_input()
	{
		// Keep safe form values across validation redirects. Never store passwords.
		$this->session->set_flashdata('user_form_old', array(
			'employee_id' => trim((string)$this->input->post('employee_id', TRUE)),
			'name' => trim((string)$this->input->post('name', TRUE)),
			'email' => trim((string)$this->input->post('email', TRUE)),
			'department_id' => $this->input->post('department_id', TRUE),
			'team_id' => $this->input->post('team_id', TRUE),
			'role_id' => $this->input->post('role_id', TRUE),
			'status' => $this->input->post('status', TRUE),
		));
	}
	public function user_add()
	{
		if ($this->input->method(TRUE) === 'POST') {
			$employeeId = trim((string)$this->input->post('employee_id', TRUE));
			$name = trim((string)$this->input->post('name', TRUE));
			$email = strtolower(trim((string)$this->input->post('email', TRUE)));
			$password = (string)$this->input->post('password');
			$roleId = (int)$this->input->post('role_id');

			if ($employeeId === '' || $name === '' || $email === '' || $password === '') {
				$this->remember_user_form_input();
				$this->session->set_flashdata('admin_error', 'Employee ID, name, email, and password are required.');
				redirect('admin/users/add');
			}
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$this->remember_user_form_input();
				$this->session->set_flashdata('admin_error', 'Please enter a valid email address.');
				redirect('admin/users/add');
			}
			if ($this->Admin_model->employee_id_exists($employeeId)) {
				$this->remember_user_form_input();
				$this->session->set_flashdata('admin_error', 'A user with this Employee ID already exists.');
				redirect('admin/users/add');
			}
			if ($this->Admin_model->user_email_exists($email)) {
				$this->remember_user_form_input();
				$this->session->set_flashdata('admin_error', 'A user with this email address already exists.');
				redirect('admin/users/add');
			}
			if (!$this->Admin_model->role($roleId)) show_error('Invalid role.', 400);

			$departmentId = (int)$this->input->post('department_id');
			$departmentId = $departmentId > 0 ? $departmentId : null;
			if ($departmentId && !$this->Admin_model->department($departmentId)) show_error('Invalid department.', 400);
			$teamId = $departmentId ? $this->validated_team((int)$this->input->post('team_id'), $departmentId) : null;

			$data = array(
				'employee_id' => $employeeId,
				'name' => $name,
				'email' => $email,
				'password' => password_hash($password, PASSWORD_DEFAULT),
				'department_id' => $departmentId,
				'team_id' => $teamId,
				'role_id' => $roleId,
				'status' => 'ACTIVE'
			);
			$this->db->insert('users', $data);
			$this->session->set_flashdata('admin_success', 'User created successfully.');
			redirect('admin/users');
		}
		$this->render('admin/user_form', array('page_title' => 'Add User', 'mode' => 'add', 'userData' => null, 'departments' => $this->db->where('status', 'ACTIVE')->order_by('department_name')->get('departments')->result_array(), 'roles' => $this->Admin_model->assignable_user_roles(), 'teams' => $this->Admin_model->teams()));
	}

	public function user_edit($id)
	{
		$u = $this->Admin_model->user($id);
		if (!$u) show_404();
		if ($this->input->method(TRUE) === 'POST') {
			$roleId = (int)$this->input->post('role_id');
			if (!$this->Admin_model->role($roleId)) show_error('Invalid role.', 400);
			$name = trim((string)$this->input->post('name', TRUE));
			$email = strtolower(trim((string)$this->input->post('email', TRUE)));
			if ($name === '' || $email === '') {
				$this->remember_user_form_input();
				$this->session->set_flashdata('admin_error', 'Name and email are required.');
				redirect('admin/users/edit/' . $id);
			}
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$this->remember_user_form_input();
				$this->session->set_flashdata('admin_error', 'Please enter a valid email address.');
				redirect('admin/users/edit/' . $id);
			}
			if ($this->Admin_model->user_email_exists($email, (int)$id)) {
				$this->remember_user_form_input();
				$this->session->set_flashdata('admin_error', 'Another user already uses this email address.');
				redirect('admin/users/edit/' . $id);
			}
			$newStatus = strtoupper(trim((string)$this->input->post('status', TRUE)));
			if (!in_array($newStatus, array('ACTIVE', 'INACTIVE'), true)) show_error('Invalid user status.', 400);
			$currentRole = $this->Admin_model->role((int)$u['role_id']);
			$newRole = $this->Admin_model->role($roleId);
			if (($currentRole['role_name'] ?? '') === 'Administrator' && (($newRole['role_name'] ?? '') !== 'Administrator' || $newStatus !== 'ACTIVE') && $this->Admin_model->active_admin_count() <= 1) {
				$this->remember_user_form_input();
				$this->session->set_flashdata('admin_error', 'At least one active administrator is required. You cannot remove the last administrator role or make the last administrator inactive.');
				redirect('admin/users/edit/' . $id);
			}
			$departmentId = (int)$this->input->post('department_id');
			$departmentId = $departmentId > 0 ? $departmentId : null;
			$teamId = $departmentId ? $this->validated_team((int)$this->input->post('team_id'), $departmentId) : null;
			if ($departmentId && !$this->Admin_model->department($departmentId)) show_error('Invalid department.', 400);
			$data = array('name' => $name, 'email' => $email, 'department_id' => $departmentId, 'team_id' => $teamId, 'role_id' => $roleId, 'status' => $newStatus);
			if ($this->input->post('password') !== '') $data['password'] = password_hash((string)$this->input->post('password'), PASSWORD_DEFAULT);
			$this->db->where('id', (int)$id)->update('users', $data);
			redirect('admin/users');
		}
		$this->render('admin/user_form', array('page_title' => 'Edit User', 'mode' => 'edit', 'userData' => $u, 'departments' => $this->Admin_model->departments(), 'roles' => $this->Admin_model->assignable_user_roles(), 'teams' => $this->Admin_model->teams(), 'isDepartmentHead' => $this->Admin_model->is_department_head($id)));
	}
	public function categories()
	{
		if ($this->input->method(TRUE) === 'POST') {
			$categoryCode = strtoupper(trim((string)$this->input->post('category_code', TRUE)));
			$categoryName = trim((string)$this->input->post('category_name', TRUE));
			$criteria = trim((string)$this->input->post('criteria', TRUE));

			if ($categoryCode === '' || $categoryName === '' || $criteria === '') {
				$this->session->set_flashdata('admin_error', 'Category code, category name, and criteria are required.');
				redirect('admin/categories');
			}

			$duplicateCode = $this->db->where('category_code', $categoryCode)->count_all_results('project_categories') > 0;
			$duplicateName = $this->db->where('category_name', $categoryName)->count_all_results('project_categories') > 0;

			if ($duplicateCode || $duplicateName) {
				$this->session->set_flashdata('admin_error', 'A project category with the same code or name already exists.');
				redirect('admin/categories');
			}

			$this->db->insert('project_categories', array(
				'category_code' => $categoryCode,
				'category_name' => $categoryName,
				'criteria' => $criteria,
				'status' => 'ACTIVE'
			));

			$this->session->set_flashdata('admin_success', 'Project category added successfully.');
			redirect('admin/categories');
		}

		$this->render('admin/categories', array('page_title' => 'Categories', 'categories' => $this->Admin_model->categories(), 'teams' => $this->Admin_model->teams()));
	}
	public function departments()
	{
		if ($this->input->method(TRUE) === 'POST') {
			$code = strtoupper(trim((string)$this->input->post('department_code', TRUE)));
			$name = trim((string)$this->input->post('department_name', TRUE));
			if ($code === '' || $name === '') {
				$this->session->set_flashdata('admin_error', 'Department code and department name are required.');
				redirect('admin/departments');
			}
			if ($this->Admin_model->department_code_exists($code)) {
				$this->session->set_flashdata('admin_error', 'A department with this code already exists.');
				redirect('admin/departments');
			}
			if ($this->Admin_model->department_name_exists($name)) {
				$this->session->set_flashdata('admin_error', 'A department with this name already exists.');
				redirect('admin/departments');
			}
			$this->db->insert('departments', array('department_code' => $code, 'department_name' => $name, 'status' => 'ACTIVE'));
			$this->session->set_flashdata('admin_success', 'Department added successfully.');
			redirect('admin/departments');
		}
		$this->render('admin/departments', array('page_title' => 'Departments', 'departments' => $this->Admin_model->departments()));
	}


	public function roles()
	{
		if ($this->input->method(TRUE) === 'POST') {
			$roleName = trim((string)$this->input->post('role_name', TRUE));
			if ($roleName === '') {
				$this->session->set_flashdata('admin_error', 'Role name is required.');
				redirect('admin/roles');
			}
			if ($this->Admin_model->role_name_exists($roleName)) {
				$this->session->set_flashdata('admin_error', 'A role with this name already exists.');
				redirect('admin/roles');
			}
			$this->db->insert('roles', array('role_name' => $roleName, 'can_define_workflow' => $this->input->post('can_define_workflow') ? 1 : 0));
			$this->session->set_flashdata('admin_success', 'Role added successfully.');
			redirect('admin/roles');
		}
		$this->render('admin/roles', array('page_title' => 'Roles', 'roles' => $this->Admin_model->roles()));
	}

	public function role_update($id)
	{
		if ($this->input->method(TRUE) !== 'POST') show_error('Method not allowed.', 405);
		$id = (int)$id;
		$role = $this->Admin_model->role($id);
		if (!$role) show_404();

		$roleName = trim((string)$this->input->post('role_name', TRUE));
		if ($roleName === '') {
			$this->session->set_flashdata('admin_error', 'Role name is required.');
			redirect('admin/roles');
		}
		if ($this->Admin_model->role_name_exists($roleName, $id)) {
			$this->session->set_flashdata('admin_error', 'A role with this name already exists.');
			redirect('admin/roles');
		}

		$this->db->where('id', $id)->update('roles', array('role_name' => $roleName, 'can_define_workflow' => $this->input->post('can_define_workflow') ? 1 : 0));
		$this->session->set_flashdata('admin_success', 'Role updated successfully.');
		redirect('admin/roles');
	}

	public function role_delete($id)
	{
		if ($this->input->method(TRUE) !== 'POST') show_error('Method not allowed.', 405);
		$id = (int)$id;
		$role = $this->Admin_model->role($id);
		if (!$role) show_404();

		// The system must always retain at least one active administrator.
		if (($role['role_name'] ?? '') === 'Administrator' && $this->Admin_model->active_admin_count() > 0) {
			$this->session->set_flashdata('admin_error', 'The Administrator role cannot be removed while it is assigned to the system administrator(s). At least one active administrator is required.');
			redirect('admin/roles');
		}

		// A role used by an approval workflow must never be deleted.
		if ($this->Admin_model->role_used_by_workflow($id)) {
			$this->session->set_flashdata('admin_error', 'This role cannot be removed because it is assigned to an approval workflow.');
			redirect('admin/roles');
		}

		$this->db->trans_begin();

		// Users keep their accounts, but their role becomes empty.
		$this->db->where('role_id', $id)->update('users', array('role_id' => null));
		$this->db->where('id', $id)->delete('roles');

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			show_error('Unable to remove role.', 500);
		}

		$this->db->trans_commit();
		$this->session->set_flashdata('admin_success', 'Role removed successfully. Users assigned to this role now have no role.');
		redirect('admin/roles');
	}

	public function user_delete($id)
	{
		if ($this->input->method(TRUE) !== 'POST') show_error('Method not allowed.', 405);
		$id = (int)$id;
		$u = $this->Admin_model->user($id);
		if (!$u) show_404();
		$userRole = $this->Admin_model->role((int)$u['role_id']);
		if (($userRole['role_name'] ?? '') === 'Administrator' && $u['status'] === 'ACTIVE' && $this->Admin_model->active_admin_count() <= 1) {
			$this->session->set_flashdata('admin_error', 'At least one active administrator is required. The last administrator cannot be removed.');
			redirect('admin/users');
		}
		if ($id === (int)$this->user['id']) {
			$this->session->set_flashdata('admin_error', 'You cannot remove your own administrator account.');
			redirect('admin/users');
		}
		$workflowStep = $this->Admin_model->user_required_by_active_workflow($id);
		if ($workflowStep) {
			$this->session->set_flashdata(
				'admin_error',
				'This user cannot be deactivated because they are currently required by the active workflow step "' .
					($workflowStep['step_name'] ?? 'Approval') .
					'". Remove that workflow step or make another active user available for the same role and scope first.'
			);
			redirect('admin/users');
		}

		if ($this->Admin_model->user_has_pending_approvals($id)) {
			$this->session->set_flashdata(
				'admin_error',
				'This user cannot be deactivated because they still have pending approval tasks. Complete or reassign those approvals first.'
			);
			redirect('admin/users');
		}

		if ($this->Admin_model->user_is_in_use($id)) {
			$this->db->where('id', $id)->update('users', array('status' => 'INACTIVE'));
			$this->session->set_flashdata(
				'admin_success',
				'User deactivated successfully. Existing project and approval history has been preserved.'
			);
			redirect('admin/users');
		}

		$this->db->where('id', $id)->delete('users');
		$this->session->set_flashdata('admin_success', 'User removed successfully.');
		redirect('admin/users');
	}

	public function department_delete($id)
	{
		if ($this->input->method(TRUE) !== 'POST') show_error('Method not allowed.', 405);
		$id = (int)$id;
		if (!$this->db->where('id', $id)->get('departments')->row_array()) show_404();
		if ($this->Admin_model->department_is_in_use($id)) {
			$this->session->set_flashdata('admin_error', 'This department cannot be removed because it is already in use.');
			redirect('admin/departments');
		}
		$this->db->where('id', $id)->delete('departments');
		$this->session->set_flashdata('admin_success', 'Department removed successfully.');
		redirect('admin/departments');
	}

	public function category_delete($id)
	{
		if ($this->input->method(TRUE) !== 'POST') show_error('Method not allowed.', 405);
		$id = (int)$id;

		if (!$this->db->where('id', $id)->get('project_categories')->row_array()) show_404();

		if ($this->Admin_model->category_is_in_use($id)) {
			$this->session->set_flashdata(
				'admin_error',
				'This project category cannot be removed because it is currently assigned to a department or is being used by a project or approval workflow. Remove the related assignments first.'
			);
			redirect('admin/categories');
		}

		// Keep the database constraint as a final safety layer, but never expose its raw error to the user.
		$this->db->db_debug = FALSE;
		$deleted = $this->db->where('id', $id)->delete('project_categories');
		$dbError = $this->db->error();

		if (!$deleted || !empty($dbError['code'])) {
			$this->session->set_flashdata(
				'admin_error',
				'This project category cannot be removed because it is still referenced by another record. Remove the related assignment first.'
			);
			redirect('admin/categories');
		}

		$this->session->set_flashdata('admin_success', 'Project category removed successfully.');
		redirect('admin/categories');
	}

	public function workflows()
	{
		$this->render('admin/workflows', array(
			'page_title' => 'Approval Workflows',
			'workflows' => $this->Admin_model->workflows(),
			'departments' => $this->Admin_model->departments(),
			'categories' => $this->Admin_model->categories(),
			'roles' => $this->Admin_model->roles()
		));
	}

	public function workflow_add()
	{
		// Administrators have read-only access to approval workflows.
		show_error('Administrators can view approval workflows only. Workflow-definition access is assigned dynamically from the Roles page.', 403);
	}
	public function category_assignments()
	{
		if ($this->input->method(TRUE) === 'POST') {
			$d = (int)$this->input->post('department_id');
			$c = (int)$this->input->post('category_id');
			$teamId = (int)$this->input->post('team_id');
			if (!$this->Admin_model->department($d) || !$this->Admin_model->category($c)) show_error('Invalid department or category.', 400);
			if ($this->db->where('category_id', $c)->count_all_results('department_project_categories')) {
				$this->session->set_flashdata('admin_error', 'This project category already belongs to a department. Remove its current assignment before assigning it to another department.');
				redirect('admin/category-assignments');
			}
			if ($teamId && !$this->Admin_model->team_belongs_to_department($teamId, $d)) show_error('Selected team does not belong to this department.', 400);
			$this->db->insert('department_project_categories', array('department_id' => $d, 'category_id' => $c, 'team_id' => $teamId ?: null));
			$this->session->set_flashdata('admin_success', 'Project category assigned successfully.');
			redirect('admin/category-assignments');
		}
		$this->render('admin/category_assignments', array('page_title' => 'Department Project Categories', 'assignments' => $this->Admin_model->category_departments(), 'departments' => $this->Admin_model->departments(), 'categories' => $this->Admin_model->categories(), 'teams' => $this->Admin_model->teams()));
	}
	public function category_assignment_delete($d, $c)
	{
		if ($this->input->method(TRUE) !== 'POST') show_error('Method not allowed.', 405);
		$d = (int)$d;
		$c = (int)$c;
		if ($this->db->where('department_id', $d)->where('category_id', $c)->count_all_results('approval_workflows') > 0) {
			$this->session->set_flashdata('admin_error', 'Remove this department workflow first.');
			redirect('admin/category-assignments');
		}
		$this->db->where('department_id', $d)->where('category_id', $c)->delete('department_project_categories');
		$this->session->set_flashdata('admin_success', 'Assignment removed.');
		redirect('admin/category-assignments');
	}

	public function teams()
	{
		if ($this->input->method(TRUE) === 'POST') {
			$departmentId = (int)$this->input->post('department_id');
			$name = trim((string)$this->input->post('team_name', TRUE));
			if (!$this->Admin_model->department($departmentId) || $name === '') show_error('Department and team name are required.', 400);
			if ($this->Admin_model->team_name_exists($departmentId, $name)) {
				$this->session->set_flashdata('admin_error', 'That team already exists in this department.');
				redirect('admin/teams');
			}
			$this->db->insert('teams', array('department_id' => $departmentId, 'team_name' => $name, 'status' => 'ACTIVE'));
			$this->session->set_flashdata('admin_success', 'Team added successfully.');
			redirect('admin/teams');
		}
		$this->render('admin/teams', array('page_title' => 'Teams', 'teams' => $this->Admin_model->teams(), 'departments' => $this->Admin_model->departments()));
	}
	public function team_delete($id)
	{
		if ($this->input->method(TRUE) !== 'POST') show_error('Method not allowed.', 405);
		$id = (int)$id;
		if ($this->Admin_model->team_is_in_use($id)) {
			$this->session->set_flashdata('admin_error', 'This team cannot be removed because it is assigned to a user or project category.');
			redirect('admin/teams');
		}
		$this->db->where('id', $id)->delete('teams');
		$this->session->set_flashdata('admin_success', 'Team removed.');
		redirect('admin/teams');
	}
	private function validated_team($teamId, $departmentId)
	{
		if (!$teamId) return null;
		if (!$this->Admin_model->team_belongs_to_department($teamId, $departmentId)) show_error('Selected team does not belong to the selected department.', 400);
		return $teamId;
	}
}
