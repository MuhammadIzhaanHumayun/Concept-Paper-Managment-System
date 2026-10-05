<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Workflows extends MY_Controller
{
	private $departmentId;
	private $scopes = array('REQUESTING_DEPARTMENT', 'CATEGORY_OWNER_DEPARTMENT', 'CATEGORY_TEAM', 'ORGANIZATION');
	public function __construct()
	{
		parent::__construct();
		$this->require_login();
		if (!$this->can_manage_workflows()) {
			show_error('Access denied. Your role is not authorized to define workflows for this department.', 403);
		}
		$this->departmentId = (int)$this->user['department_id'];
	}
	public function index()
	{
		$categories = $this->db->select('pc.*,dpc.team_id,t.team_name')->from('department_project_categories dpc')->join('project_categories pc', 'pc.id=dpc.category_id')->join('teams t', 't.id=dpc.team_id', 'left')->where('dpc.department_id', $this->departmentId)->where('pc.status', 'ACTIVE')->order_by('pc.category_name')->get()->result_array();
		$selectedCategoryId = (int)$this->input->get('category_id');
		$selectedCategory = null;
		$workflows = array();
		if ($selectedCategoryId) {
			foreach ($categories as $c) if ((int)$c['id'] === $selectedCategoryId) {
				$selectedCategory = $c;
				break;
			}
			if (!$selectedCategory) show_error('This project category is not assigned to your department.', 403);
			$workflows = $this->db->select('aw.*,r.role_name')->from('approval_workflows aw')->join('roles r', 'r.id=aw.role_id', 'left')->where('aw.department_id', $this->departmentId)->where('aw.category_id', $selectedCategoryId)->where('aw.is_required', 1)->order_by('aw.step_order')->get()->result_array();
		}
		$this->render('workflows/index', array('page_title' => 'Department Approval Workflows', 'department' => $this->db->where('id', $this->departmentId)->get('departments')->row_array(), 'workflows' => $workflows, 'categories' => $categories, 'selectedCategory' => $selectedCategory, 'selectedCategoryId' => $selectedCategoryId, 'roles' => $this->db->order_by('role_name')->get('roles')->result_array()));
	}
	public function add()
	{
		if ($this->input->method(TRUE) !== 'POST') show_error('Method not allowed.', 405);
		$categoryId = (int)$this->input->post('category_id');
		$stepName = trim((string)$this->input->post('step_name', TRUE));
		$roleId = (int)$this->input->post('role_id');
		$scope = strtoupper(trim((string)$this->input->post('approver_scope', TRUE)));
		$technical = $this->input->post('requires_technical_review') ? 1 : 0;
		if (!$categoryId || $stepName === '' || !$roleId || !in_array($scope, $this->scopes, true)) show_error('All workflow fields are required.', 400);
		$assignment = $this->db->where('department_id', $this->departmentId)->where('category_id', $categoryId)->get('department_project_categories')->row_array();
		if (!$assignment) show_error('This category is not assigned to your department.', 403);
		if (!$this->db->where('id', $roleId)->get('roles')->row_array()) show_error('Invalid role.', 400);
		if ($scope === 'CATEGORY_TEAM' && empty($assignment['team_id'])) {
			$this->session->set_flashdata('workflow_error', 'This category has no team assigned. Ask the Administrator to assign a team in Department Categories.');
			redirect('workflows?category_id=' . $categoryId);
		}
		$row = $this->db->select_max('step_order', 'max_order')->where('category_id', $categoryId)->where('department_id', $this->departmentId)->where('is_required', 1)->get('approval_workflows')->row_array();
		$stepOrder = ((int)($row['max_order'] ?? 0)) + 1;
		$this->db->insert('approval_workflows', array('category_id' => $categoryId, 'department_id' => $this->departmentId, 'step_order' => $stepOrder, 'step_code' => 'ROLE_APPROVAL', 'step_name' => $stepName, 'role_id' => $roleId, 'approver_scope' => $scope, 'requires_technical_review' => $technical, 'is_required' => 1));
		$this->session->set_flashdata('workflow_success', 'Workflow step added successfully.');
		redirect('workflows?category_id=' . $categoryId);
	}
	public function update($id)
	{
		if ($this->input->method(TRUE) !== 'POST') show_error('Method not allowed.', 405);
		$step = $this->owned_step($id);
		$stepName = trim((string)$this->input->post('step_name', TRUE));
		$roleId = (int)$this->input->post('role_id');
		$scope = strtoupper(trim((string)$this->input->post('approver_scope', TRUE)));
		$technical = $this->input->post('requires_technical_review') ? 1 : 0;
		if ($stepName === '' || !$this->db->where('id', $roleId)->get('roles')->row_array() || !in_array($scope, $this->scopes, true)) show_error('Invalid workflow data.', 400);
		if ($scope === 'CATEGORY_TEAM') {
			$a = $this->db->where('department_id', $this->departmentId)->where('category_id', (int)$step['category_id'])->get('department_project_categories')->row_array();
			if (!$a || empty($a['team_id'])) {
				$this->session->set_flashdata('workflow_error', 'This category has no team assigned.');
				redirect('workflows?category_id=' . (int)$step['category_id']);
			}
		}
		$this->db->where('id', (int)$id)->where('department_id', $this->departmentId)->update('approval_workflows', array('step_name' => $stepName, 'role_id' => $roleId, 'approver_scope' => $scope, 'requires_technical_review' => $technical, 'step_code' => 'ROLE_APPROVAL'));
		$this->session->set_flashdata('workflow_success', 'Workflow step updated successfully.');
		redirect('workflows?category_id=' . (int)$step['category_id']);
	}
	public function reorder($id, $direction)
	{
		if ($this->input->method(TRUE) !== 'POST') show_error('Method not allowed.', 405);
		$step = $this->owned_step($id);
		$categoryId = (int)$step['category_id'];
		$direction = strtolower((string)$direction);
		if (!in_array($direction, array('up', 'down'), true)) show_error('Invalid reorder direction.', 400);

		$activeRows = $this->db->select('id, step_order')->where('department_id', $this->departmentId)->where('category_id', $categoryId)->where('is_required', 1)->order_by('step_order')->get('approval_workflows')->result_array();
		$activeStepIds = array();
		foreach ($activeRows as $row) $activeStepIds[] = (int)$row['id'];

		// Approval sequencing reads approval_workflows.step_order dynamically, so an
		// in-flight workflow must not be reordered.
		if ($activeStepIds) {
			$this->db->where_in('workflow_id', $activeStepIds)->where('status', 'PENDING');
			if ($this->db->count_all_results('project_approvals') > 0) {
				$this->session->set_flashdata('workflow_error', 'Workflow order cannot be changed while a submitted project still has pending approvals in this workflow. Complete or reject the pending approval first.');
				redirect('workflows?category_id=' . $categoryId);
			}
		}

		$currentIndex = null;
		foreach ($activeRows as $i => $row) if ((int)$row['id'] === (int)$id) {
			$currentIndex = $i;
			break;
		}
		if ($currentIndex === null) show_error('Workflow step is not active.', 400);
		$targetIndex = $direction === 'up' ? $currentIndex - 1 : $currentIndex + 1;
		if ($targetIndex < 0 || $targetIndex >= count($activeRows)) redirect('workflows?category_id=' . $categoryId);

		$current = $activeRows[$currentIndex];
		$target = $activeRows[$targetIndex];
		$currentOrder = (int)$current['step_order'];
		$targetOrder = (int)$target['step_order'];

		$this->db->trans_begin();
		$this->db->where('id', (int)$current['id'])->update('approval_workflows', array('step_order' => 0));
		$this->db->where('id', (int)$target['id'])->update('approval_workflows', array('step_order' => $currentOrder));
		$this->db->where('id', (int)$current['id'])->update('approval_workflows', array('step_order' => $targetOrder));
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			show_error('Unable to reorder workflow steps.', 500);
		}
		$this->db->trans_commit();
		$this->session->set_flashdata('workflow_success', 'Workflow order updated successfully.');
		redirect('workflows?category_id=' . $categoryId);
	}

	public function delete($id)
	{
		if ($this->input->method(TRUE) !== 'POST') show_error('Method not allowed.', 405);
		$step = $this->owned_step($id);
		$categoryId = (int)$step['category_id'];

		// A submitted project may already be waiting on this exact workflow step.
		// Do not let a workflow manager change the future workflow in a way that removes an
		// approval which is still outstanding for an existing project.
		$pendingCount = $this->db
			->where('workflow_id', (int)$id)
			->where('status', 'PENDING')
			->count_all_results('project_approvals');

		if ($pendingCount > 0) {
			$this->session->set_flashdata(
				'workflow_error',
				'This workflow step cannot be removed because ' . $pendingCount .
					' submitted project' . ($pendingCount === 1 ? ' is' : 's are') .
					' still waiting for this approval. The assigned approver must approve or reject the pending project first.'
			);
			redirect('workflows?category_id=' . $categoryId);
		}

		$isUsed = $this->db->where('workflow_id', (int)$id)->count_all_results('project_approvals') > 0;

		$this->db->trans_begin();

		if ($isUsed) {
			// Retire only the workflow step. Never change any user's status here.
			$this->db->where('id', (int)$id)
				->where('department_id', $this->departmentId)
				->update('approval_workflows', array(
					'is_required' => 0,
					'step_order' => 1000000 + (int)$id
				));
		} else {
			$this->db->where('id', (int)$id)
				->where('department_id', $this->departmentId)
				->delete('approval_workflows');
		}

		$remaining = $this->db
			->where('category_id', $categoryId)
			->where('department_id', $this->departmentId)
			->where('is_required', 1)
			->order_by('step_order')
			->get('approval_workflows')->result_array();

		foreach ($remaining as $i => $r) {
			$this->db->where('id', (int)$r['id'])
				->update('approval_workflows', array('step_order' => $i + 1));
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			show_error('Unable to remove workflow step.', 500);
		}
		$this->db->trans_commit();

		$this->session->set_flashdata('workflow_success', $isUsed
			? 'Workflow step removed from future projects. Existing approval history has been preserved.'
			: 'Workflow step removed successfully.');
		redirect('workflows?category_id=' . $categoryId);
	}
	private function owned_step($id)
	{
		$step = $this->db->where('id', (int)$id)->where('department_id', $this->departmentId)->get('approval_workflows')->row_array();
		if (!$step) show_error('Access denied. This workflow does not belong to your department.', 403);
		return $step;
	}
}
