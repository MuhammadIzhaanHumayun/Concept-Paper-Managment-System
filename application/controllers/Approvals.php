<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Approvals extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->require_approver();
		$this->load->model(array('Approval_model', 'Project_model', 'History_model'));
	}
	public function index()
	{
		$this->render('approvals/index', array('page_title' => 'Approval Inbox', 'approvals' => $this->Approval_model->inbox($this->user['id'])));
	}

	public function rejected()
	{
		$rows = $this->db->select('pa.*,p.project_id,p.project_title,aw.step_name')->from('project_approvals pa')->join('projects p', 'p.id=pa.project_id')->join('approval_workflows aw', 'aw.id=pa.workflow_id')->where('pa.approver_id', (int)$this->user['id'])->where('pa.status', 'REJECTED')->order_by('pa.action_date', 'DESC')->get()->result_array();
		$this->render('approvals/rejected', array('page_title' => 'Rejected / Returned', 'approvals' => $rows));
	}
	public function history($projectId)
	{
		$project = $this->Project_model->get($projectId);
		if (!$project) show_404();
		$isOwner = (int)$project['requestor_id'] === (int)$this->user['id'];
		$isAssigned = $this->db->where('project_id', (int)$projectId)->where('approver_id', (int)$this->user['id'])->count_all_results('project_approvals') > 0;
		if (!$isOwner && !$isAssigned && !$this->is_admin()) show_error('Access denied.', 403);
		$this->render('approvals/history', array('page_title' => 'Project History', 'project' => $project, 'history' => $this->History_model->for_project($projectId)));
	}

	public function review($projectId, $approvalId)
	{
		$approval = $this->Approval_model->assigned_pending($approvalId, $projectId, $this->user['id']);
		if (!$approval) show_error('Access denied. This approval task is not assigned to you.', 403);
		if ($this->Approval_model->previous_incomplete($projectId, $approval['step_order']) > 0) show_error('Access denied. Previous approval steps have not been completed.', 403);
		$project = $this->Project_model->get($projectId);
		if (!$project) show_404();
		$stakeholders = $this->db->where('project_id', (int)$projectId)->order_by('id')->get('stakeholders')->result_array();
		$this->render('approvals/review', array(
			'page_title' => 'Review ' . $project['project_id'],
			'project' => $project,
			'approval' => $approval,
			'stakeholders' => $stakeholders,
			'remarks' => $this->Approval_model->remarks_for_project($projectId),
			'technicalTimeline' => $this->Approval_model->latest_expected_timeline($projectId)
		));
	}
	public function action()
	{
		if ($this->input->method(TRUE) !== 'POST') show_404();
		$id = (int)$this->input->post('id');
		$approvalId = (int)$this->input->post('approval_id');
		$action = strtoupper((string)$this->input->post('action', TRUE));
		if (!in_array($action, array('APPROVE', 'REJECT', 'RETURN'), true)) show_error('Invalid approval action.', 400);
		$approval = $this->Approval_model->assigned_pending($approvalId, $id, $this->user['id']);
		if (!$approval) show_error('Access denied. This approval task is not assigned to you.', 403);
		if ($this->Approval_model->previous_incomplete($id, $approval['step_order']) > 0) show_error('Previous approval steps have not been completed.', 403);
		$project = $this->Project_model->get($id);
		if (!$project) show_404();
		$comments = trim((string)$this->input->post('comments', TRUE));
		$this->db->trans_begin();
		if ($action === 'APPROVE') {
			$technicalReviewId = null;
			if (!empty($approval['requires_technical_review'])) {
				$expectedStartDate = trim((string)$this->input->post('expected_start_date', TRUE));
				$expectedEndDate = trim((string)$this->input->post('expected_end_date', TRUE));
				if ($expectedStartDate === '' || $expectedEndDate === '') {
					$this->db->trans_rollback();
					show_error('Expected Start Date and Expected End Date are required for the technical review.', 400);
				}
				if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $expectedStartDate) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $expectedEndDate)) {
					$this->db->trans_rollback();
					show_error('Please enter valid expected start and end dates.', 400);
				}
				if (strtotime($expectedEndDate) < strtotime($expectedStartDate)) {
					$this->db->trans_rollback();
					show_error('Expected End Date cannot be earlier than Expected Start Date.', 400);
				}
				$this->db->insert('technical_reviews', array(
					'project_id' => $id,
					'reviewer_id' => $this->user['id'],
					'technical_team' => 'DYNAMIC',
					'feasibility' => $this->input->post('feasibility', TRUE) ?: 'PENDING',
					'technical_comments' => $comments,
					'estimated_effort' => $this->input->post('estimated_effort', TRUE),
					'technical_risks' => $this->input->post('technical_risks', TRUE),
					'dependencies' => $this->input->post('dependencies', TRUE),
					'expected_start_date' => $expectedStartDate,
					'expected_end_date' => $expectedEndDate,
					'review_date' => date('Y-m-d H:i:s')
				));
				$technicalReviewId = (int)$this->db->insert_id();
			}

			$wasReturnedTask = !empty($approval['returned_for_revision']);
			$wasReturningStep = !empty($approval['return_comments']);
			$this->db->where('id', $approvalId)->where('project_id', $id)->where('approver_id', $this->user['id'])->where('status', 'PENDING')
				->update('project_approvals', array('status' => 'APPROVED', 'returned_for_revision' => 0, 'comments' => $comments, 'action_date' => date('Y-m-d H:i:s')));
			if ($this->db->affected_rows() !== 1) {
				$this->db->trans_rollback();
				show_error('Approval was already processed or is no longer assigned to you.', 409);
			}

			if ($wasReturnedTask) {
				// This approver received the paper because the NEXT step returned it.
				// After correction/approval, reopen that next step as a NORMAL pending
				// approval. "Returned for Revision" must not propagate forward.
				$nextReturned = $this->db->select('pa.id')
					->from('project_approvals pa')
					->join('approval_workflows aw', 'aw.id=pa.workflow_id')
					->where('pa.project_id', $id)
					->where('pa.status', 'RETURNED_FOR_REVISION')
					->where('aw.step_order >', (int)$approval['step_order'])
					->order_by('aw.step_order', 'ASC')->limit(1)->get()->row_array();

				if ($nextReturned) {
					$this->db->where('id', (int)$nextReturned['id'])->update('project_approvals', array(
						'status' => 'PENDING',
						'returned_for_revision' => 0,
						'action_date' => null
					));
				}
			} elseif ($wasReturningStep) {
				// The approver who originally returned the paper has now approved
				// the corrected version. Resume the untouched future workflow.
				$this->db->where('project_id', $id)
					->where('status', 'NOT_REQUIRED')
					->update('project_approvals', array(
						'status' => 'PENDING',
						'returned_for_revision' => 0
					));
			}

			$remaining = $this->db->where('project_id', $id)->where('status', 'PENDING')->count_all_results('project_approvals');
			$returned = $this->db->where('project_id', $id)->where('status', 'RETURNED_FOR_REVISION')->count_all_results('project_approvals');
			$new = ($remaining === 0 && $returned === 0) ? 'APPROVED' : 'SUBMITTED';
			$this->db->where('id', $id)->update('projects', array('status' => $new));
			$this->History_model->add(
				$id,
				$this->user['id'],
				'APPROVAL_APPROVED',
				$project['status'],
				$new,
				$comments,
				$approvalId,
				(int)$approval['workflow_id'],
				$technicalReviewId
			);
		} elseif ($action === 'REJECT') {
			$this->db->where('id', $approvalId)->where('project_id', $id)->where('approver_id', $this->user['id'])->where('status', 'PENDING')
				->update('project_approvals', array('status' => 'REJECTED', 'returned_for_revision' => 0, 'comments' => $comments, 'action_date' => date('Y-m-d H:i:s')));
			if ($this->db->affected_rows() !== 1) {
				$this->db->trans_rollback();
				show_error('Approval was already processed.', 409);
			}
			$this->db->where('project_id', $id)->where('status', 'PENDING')
				->update('project_approvals', array('status' => 'NOT_REQUIRED', 'returned_for_revision' => 0));
			$this->db->where('id', $id)->update('projects', array('status' => 'REJECTED'));
			$this->History_model->add(
				$id,
				$this->user['id'],
				'PROJECT_REJECTED',
				$project['status'],
				'REJECTED',
				$comments,
				$approvalId,
				(int)$approval['workflow_id'],
				null
			);
		} else {
			// Mark the returning approver's step, then send the project one step backwards.
			$this->db->where('id', $approvalId)->where('project_id', $id)->where('approver_id', $this->user['id'])->where('status', 'PENDING')
				->update('project_approvals', array(
					'status' => 'RETURNED_FOR_REVISION',
					'returned_for_revision' => 0,
					'comments' => $comments,
					'return_comments' => $comments,
					'action_date' => date('Y-m-d H:i:s')
				));
			if ($this->db->affected_rows() !== 1) {
				$this->db->trans_rollback();
				show_error('Approval was already processed.', 409);
			}

			// Steps after the returning approver are not needed until the return chain catches up.
			$futureIds = $this->db->select('pa.id')
				->from('project_approvals pa')
				->join('approval_workflows aw', 'aw.id=pa.workflow_id')
				->where('pa.project_id', $id)
				->where('pa.status', 'PENDING')
				->where('aw.step_order >', (int)$approval['step_order'])
				->get()->result_array();
			if ($futureIds) {
				$ids = array_map(function ($r) {
					return (int)$r['id'];
				}, $futureIds);
				$this->db->where_in('id', $ids)->update('project_approvals', array('status' => 'NOT_REQUIRED', 'returned_for_revision' => 0));
			}

			$previous = $this->db->select('pa.id')
				->from('project_approvals pa')
				->join('approval_workflows aw', 'aw.id=pa.workflow_id')
				->where('pa.project_id', $id)
				->where('aw.step_order <', (int)$approval['step_order'])
				->order_by('aw.step_order', 'DESC')->limit(1)->get()->row_array();

			if ($previous) {
				// Re-open only the immediately previous approver and identify it as a returned task.
				$this->db->where('id', (int)$previous['id'])->update('project_approvals', array(
					'status' => 'PENDING',
					'returned_for_revision' => 1,
					'action_date' => null
				));
				$this->db->where('id', $id)->update('projects', array('status' => 'SUBMITTED'));
				$this->History_model->add(
					$id,
					$this->user['id'],
					'PROJECT_RETURNED_TO_PREVIOUS_APPROVER',
					$project['status'],
					'SUBMITTED',
					$comments,
					$approvalId,
					(int)$approval['workflow_id'],
					null
				);
			} else {
				// First workflow approver: there is nobody before them, so return to requestor.
				$this->db->where('project_id', $id)->where('status', 'PENDING')
					->update('project_approvals', array('status' => 'NOT_REQUIRED', 'returned_for_revision' => 0));
				$this->db->where('id', $id)->update('projects', array('status' => 'RETURNED_FOR_REVISION'));
				$this->History_model->add(
					$id,
					$this->user['id'],
					'PROJECT_RETURNED_TO_REQUESTOR',
					$project['status'],
					'RETURNED_FOR_REVISION',
					$comments,
					$approvalId,
					(int)$approval['workflow_id'],
					null
				);
			}
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			show_error('Unable to process approval.', 500);
		}
		$this->db->trans_commit();
		redirect('approvals');
	}
}
