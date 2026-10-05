<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Workflow_model extends CI_Model
{
	public function owner_department_for_category($categoryId)
	{
		$row = $this->db->select('department_id')->where('category_id', (int)$categoryId)->limit(1)->get('department_project_categories')->row_array();
		return $row ? (int)$row['department_id'] : null;
	}
	public function category_team($categoryId, $departmentId)
	{
		$row = $this->db->select('team_id')->where('category_id', (int)$categoryId)->where('department_id', (int)$departmentId)->get('department_project_categories')->row_array();
		return $row && !empty($row['team_id']) ? (int)$row['team_id'] : null;
	}
	public function steps_for_category($categoryId, $departmentId = null)
	{
		if (!$departmentId) return array();

		$assigned = $this->db
			->where('department_id', (int)$departmentId)
			->where('category_id', (int)$categoryId)
			->count_all_results('department_project_categories') > 0;

		if (!$assigned) return array();

		return $this->db
			->where('category_id', (int)$categoryId)
			->where('department_id', (int)$departmentId)
			->where('is_required', 1)
			->order_by('step_order')
			->get('approval_workflows')
			->result_array();
	}
	private function active_user_for_role($roleId, $departmentId = null, $teamId = null)
	{
		if (!$roleId) return null;
		$this->db->select('id')->from('users')->where('role_id', (int)$roleId)->where('status', 'ACTIVE');
		if ($departmentId !== null) $this->db->where('department_id', (int)$departmentId);
		if ($teamId !== null) $this->db->where('team_id', (int)$teamId);
		$u = $this->db->order_by('id')->limit(1)->get()->row_array();
		return $u ? (int)$u['id'] : null;
	}

	public function effective_steps_for_project($steps, $project)
	{
		$effectiveReversed = array();
		$seenDepartmentRole = array();

		/*
		 * Walk from the LAST workflow step to the FIRST one.
		 *
		 * When two department-scoped steps resolve to the same role and the
		 * same effective department, the later workflow step is authoritative.
		 * Therefore the earlier duplicate is removed, not the later one.
		 *
		 * Example:
		 *   Step 2: NM - REQUESTING_DEPARTMENT
		 *   Step 4: NM - CATEGORY_OWNER_DEPARTMENT
		 *
		 * If both scopes resolve to IT, Step 2 is skipped and Step 4 is kept.
		 */
		for ($i = count($steps) - 1; $i >= 0; $i--) {
			$step = $steps[$i];
			$roleId = (int)($step['role_id'] ?? 0);
			$scope = strtoupper((string)($step['approver_scope'] ?? 'CATEGORY_OWNER_DEPARTMENT'));
			$departmentId = null;

			if ($scope === 'REQUESTING_DEPARTMENT') {
				$departmentId = (int)($project['department_id'] ?? 0);
			} elseif ($scope === 'CATEGORY_OWNER_DEPARTMENT') {
				$departmentId = !empty($step['department_id'])
					? (int)$step['department_id']
					: (int)$this->owner_department_for_category((int)$project['category_id']);
			}

			if ($roleId && $departmentId) {
				$key = $roleId . ':' . $departmentId;

				// Because we are scanning backwards, a seen key means this is
				// the EARLIER duplicate. Skip it and preserve the later step.
				if (isset($seenDepartmentRole[$key])) {
					continue;
				}

				$seenDepartmentRole[$key] = true;
			}

			$step['resolved_approver_id'] = $this->resolve_approver($step, $project);
			$effectiveReversed[] = $step;
		}

		// Restore the workflow's original step order.
		return array_reverse($effectiveReversed);
	}

	public function resolve_approver($step, $project)
	{
		$roleId = (int)($step['role_id'] ?? 0);
		if (!$roleId) return null;
		$scope = strtoupper((string)($step['approver_scope'] ?? 'CATEGORY_OWNER_DEPARTMENT'));
		$ownerDepartmentId = !empty($step['department_id']) ? (int)$step['department_id'] : $this->owner_department_for_category((int)$project['category_id']);
		if ($scope === 'REQUESTING_DEPARTMENT') return $this->active_user_for_role($roleId, (int)$project['department_id']);
		if ($scope === 'CATEGORY_TEAM') {
			$teamId = $this->category_team((int)$project['category_id'], $ownerDepartmentId);
			if (!$teamId) return null;
			return $this->active_user_for_role($roleId, $ownerDepartmentId, $teamId);
		}
		if ($scope === 'ORGANIZATION') return $this->active_user_for_role($roleId, null, null);
		return $this->active_user_for_role($roleId, $ownerDepartmentId, null);
	}
}
