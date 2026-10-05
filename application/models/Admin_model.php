<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Admin_model extends CI_Model
{
	public function categories()
	{
		return $this->db->order_by('category_name')->get('project_categories')->result_array();
	}
	public function departments()
	{
		return $this->db->order_by('department_name')->get('departments')->result_array();
	}
	public function department($id)
	{
		return $this->db->where('id', (int)$id)->get('departments')->row_array();
	}
	public function category($id)
	{
		return $this->db->where('id', (int)$id)->get('project_categories')->row_array();
	}
	public function roles()
	{
		return $this->db->order_by('id')->get('roles')->result_array();
	}
	public function assignable_user_roles()
	{
		return $this->db->order_by('role_name')->get('roles')->result_array();
	}
	public function role($id)
	{
		return $this->db->where('id', (int)$id)->get('roles')->row_array();
	}
	public function role_name_exists($roleName, $excludeId = null)
	{
		$this->db->where('role_name', $roleName);
		if ($excludeId !== null) $this->db->where('id !=', (int)$excludeId);
		return $this->db->count_all_results('roles') > 0;
	}
	public function role_used_by_workflow($id)
	{
		return $this->db->where('role_id', (int)$id)->count_all_results('approval_workflows') > 0;
	}

	public function active_admin_count()
	{
		return $this->db->from('users u')
			->join('roles r', 'r.id=u.role_id')
			->where('r.role_name', 'Administrator')
			->where('u.status', 'ACTIVE')
			->count_all_results();
	}
	public function department_heads()
	{
		return $this->db->select('dh.*, d.department_name, d.department_code, u.name AS head_name, u.employee_id AS head_employee_id, u.email AS head_email')
			->from('department_heads dh')
			->join('departments d', 'd.id=dh.department_id')
			->join('users u', 'u.id=dh.user_id')
			->where('dh.status', 'ACTIVE')
			->order_by('d.department_name')->get()->result_array();
	}
	public function is_department_head($userId)
	{
		return $this->db->where('user_id', (int)$userId)->where('status', 'ACTIVE')->count_all_results('department_heads') > 0;
	}
	public function active_users_for_head_assignment()
	{
		return $this->db->select('u.id,u.employee_id,u.name,u.department_id,d.department_name')
			->from('users u')->join('departments d', 'd.id=u.department_id', 'left')
			->join('roles r', 'r.id=u.role_id', 'left')->where('u.status', 'ACTIVE')->where("COALESCE(r.role_name,'') !=", 'Administrator')
			->order_by('u.name')->get()->result_array();
	}
	public function users()
	{
		return $this->db->select('u.*,r.role_name,d.department_name')->from('users u')->join('roles r', 'r.id=u.role_id', 'left')->join('departments d', 'd.id=u.department_id', 'left')->order_by('u.id', 'DESC')->get()->result_array();
	}
	public function user($id)
	{
		return $this->db->where('id', (int)$id)->get('users')->row_array();
	}

	public function user_email_exists($email, $excludeId = null)
	{
		$this->db->where('email', trim((string)$email));
		if ($excludeId !== null) $this->db->where('id !=', (int)$excludeId);
		return $this->db->count_all_results('users') > 0;
	}

	public function employee_id_exists($employeeId, $excludeId = null)
	{
		$this->db->where('employee_id', trim((string)$employeeId));
		if ($excludeId !== null) $this->db->where('id !=', (int)$excludeId);
		return $this->db->count_all_results('users') > 0;
	}

	public function department_code_exists($code)
	{
		return $this->db->where('department_code', trim((string)$code))->count_all_results('departments') > 0;
	}

	public function department_name_exists($name)
	{
		return $this->db->where('department_name', trim((string)$name))->count_all_results('departments') > 0;
	}

	public function user_is_in_use($id)
	{
		$id = (int)$id;
		$checks = array(
			array('projects', 'requestor_id'),
			array('project_approvals', 'approver_id'),
			array('project_history', 'user_id'),
			array('stakeholders', 'user_id'),
			array('department_heads', 'user_id'),
			array('technical_reviews', 'reviewer_id')
		);
		foreach ($checks as $check) {
			if ($this->db->where($check[1], $id)->count_all_results($check[0]) > 0) return true;
		}
		return false;
	}

	public function user_has_pending_approvals($id)
	{
		return $this->db
			->where('approver_id', (int)$id)
			->where('status', 'PENDING')
			->count_all_results('project_approvals') > 0;
	}

	public function user_required_by_active_workflow($userId)
	{
		$user = $this->db
			->select('id, role_id, department_id, team_id, status')
			->where('id', (int)$userId)
			->get('users')->row_array();

		if (!$user || strtoupper((string)$user['status']) !== 'ACTIVE') return null;

		$steps = $this->db
			->select('aw.id, aw.step_name, aw.role_id, aw.approver_scope, aw.department_id, aw.category_id, dpc.team_id AS category_team_id')
			->from('approval_workflows aw')
			->join(
				'department_project_categories dpc',
				'dpc.department_id = aw.department_id AND dpc.category_id = aw.category_id',
				'left'
			)
			->where('aw.role_id', (int)$user['role_id'])
			->where('aw.is_required', 1)
			->get()->result_array();

		foreach ($steps as $step) {
			$scope = strtoupper((string)($step['approver_scope'] ?? 'CATEGORY_OWNER_DEPARTMENT'));
			$matchesUser = false;

			if ($scope === 'ORGANIZATION') {
				$matchesUser = true;
			} elseif ($scope === 'CATEGORY_OWNER_DEPARTMENT') {
				$matchesUser = !empty($user['department_id'])
					&& (int)$user['department_id'] === (int)$step['department_id'];
			} elseif ($scope === 'CATEGORY_TEAM') {
				$matchesUser = !empty($user['department_id']) && !empty($user['team_id'])
					&& (int)$user['department_id'] === (int)$step['department_id']
					&& !empty($step['category_team_id'])
					&& (int)$user['team_id'] === (int)$step['category_team_id'];
			} elseif ($scope === 'REQUESTING_DEPARTMENT') {
				// A requesting-department step can be used by projects from this user's department.
				$matchesUser = !empty($user['department_id']);
			}

			if (!$matchesUser) continue;

			$this->db->from('users')
				->where('role_id', (int)$user['role_id'])
				->where('status', 'ACTIVE')
				->where('id !=', (int)$userId);

			if ($scope === 'CATEGORY_OWNER_DEPARTMENT') {
				$this->db->where('department_id', (int)$step['department_id']);
			} elseif ($scope === 'CATEGORY_TEAM') {
				$this->db->where('department_id', (int)$step['department_id'])
					->where('team_id', (int)$step['category_team_id']);
			} elseif ($scope === 'REQUESTING_DEPARTMENT') {
				$this->db->where('department_id', (int)$user['department_id']);
			}

			if ($this->db->count_all_results() === 0) {
				return $step;
			}
		}
		return null;
	}

	public function department_is_in_use($id)
	{
		$id = (int)$id;
		if ($this->db->where('department_id', $id)->count_all_results('users') > 0) return true;
		if ($this->db->where('department_id', $id)->count_all_results('projects') > 0) return true;
		if ($this->db->where('department_id', $id)->count_all_results('department_heads') > 0) return true;
		return false;
	}

	public function category_is_in_use($id)
	{
		$id = (int)$id;
		if ($this->db->where('category_id', $id)->count_all_results('department_project_categories') > 0) return true;
		if ($this->db->where('category_id', $id)->count_all_results('projects') > 0) return true;
		if ($this->db->where('category_id', $id)->count_all_results('approval_workflows') > 0) return true;
		return false;
	}
	public function category_departments()
	{
		return $this->db->select('d.id department_id,d.department_name,pc.id category_id,pc.category_name,dpc.team_id,t.team_name')->from('department_project_categories dpc')->join('departments d', 'd.id=dpc.department_id')->join('project_categories pc', 'pc.id=dpc.category_id')->join('teams t', 't.id=dpc.team_id', 'left')->order_by('d.department_name')->order_by('pc.category_name')->get()->result_array();
	}
	public function category_department_exists($d, $c)
	{
		return $this->db->where('department_id', (int)$d)->where('category_id', (int)$c)->count_all_results('department_project_categories') > 0;
	}
	public function workflows()
	{
		return $this->db->select('aw.*,c.category_name,r.role_name,d.department_name,aw.approver_scope,aw.requires_technical_review')
			->from('approval_workflows aw')
			->join('project_categories c', 'c.id=aw.category_id')
			->join('roles r', 'r.id=aw.role_id', 'left')
			->join('departments d', 'd.id=aw.department_id')
			->where('aw.department_id IS NOT NULL', null, FALSE)
			->where('aw.is_required', 1)
			->order_by('d.department_name', 'ASC')
			->order_by('c.category_name')->order_by('aw.step_order')
			->get()->result_array();
	}

	public function teams()
	{
		return $this->db->select('t.*,d.department_name')->from('teams t')->join('departments d', 'd.id=t.department_id')->order_by('d.department_name')->order_by('t.team_name')->get()->result_array();
	}
	public function team_belongs_to_department($teamId, $departmentId)
	{
		return $this->db->where('id', (int)$teamId)->where('department_id', (int)$departmentId)->where('status', 'ACTIVE')->count_all_results('teams') > 0;
	}
	public function team_name_exists($departmentId, $name)
	{
		return $this->db->where('department_id', (int)$departmentId)->where('team_name', $name)->count_all_results('teams') > 0;
	}
	public function team_is_in_use($id)
	{
		if ($this->db->where('team_id', (int)$id)->count_all_results('users') > 0) return true;
		if ($this->db->where('team_id', (int)$id)->count_all_results('department_project_categories') > 0) return true;
		return false;
	}
}
