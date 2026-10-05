<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Approval_model extends CI_Model
{
	public function pending_count($userId)
	{
		$sql = "SELECT COUNT(*) total FROM project_approvals pa JOIN approval_workflows aw ON aw.id=pa.workflow_id WHERE pa.approver_id=? AND pa.status='PENDING' AND COALESCE(pa.returned_for_revision,0)=0 AND NOT EXISTS (SELECT 1 FROM project_approvals previous JOIN approval_workflows pw ON pw.id=previous.workflow_id WHERE previous.project_id=pa.project_id AND pw.step_order<aw.step_order AND previous.status<>'APPROVED')";
		return (int)$this->db->query($sql, array((int)$userId))->row()->total;
	}
	public function returned_count($userId)
	{
		return (int)$this->db->where('approver_id', (int)$userId)->where('status', 'PENDING')->where('returned_for_revision', 1)->count_all_results('project_approvals');
	}
	public function inbox($userId)
	{
		$sql = "SELECT pa.id approval_id, pa.project_id db_project_id, pa.status approval_status, pa.returned_for_revision, aw.step_order, aw.step_code, aw.step_name, p.project_id, p.project_title, p.priority, p.status project_status, c.category_name FROM project_approvals pa JOIN approval_workflows aw ON aw.id=pa.workflow_id JOIN projects p ON p.id=pa.project_id JOIN project_categories c ON c.id=p.category_id WHERE pa.approver_id=? AND pa.status='PENDING' AND NOT EXISTS (SELECT 1 FROM project_approvals previous JOIN approval_workflows pw ON pw.id=previous.workflow_id WHERE previous.project_id=pa.project_id AND pw.step_order<aw.step_order AND previous.status<>'APPROVED') ORDER BY p.id DESC";
		return $this->db->query($sql, array((int)$userId))->result_array();
	}
	public function assigned_pending($approvalId, $projectId, $userId)
	{
		return $this->db->select('pa.*, aw.step_code, aw.step_name, aw.step_order, aw.requires_technical_review, aw.approver_scope')->from('project_approvals pa')->join('approval_workflows aw', 'aw.id=pa.workflow_id')->where('pa.id', (int)$approvalId)->where('pa.project_id', (int)$projectId)->where('pa.approver_id', (int)$userId)->where('pa.status', 'PENDING')->get()->row_array();
	}
	public function previous_incomplete($projectId, $stepOrder)
	{
		$sql = "SELECT COUNT(*) total FROM project_approvals previous JOIN approval_workflows pw ON pw.id=previous.workflow_id WHERE previous.project_id=? AND pw.step_order<? AND previous.status<>'APPROVED'";
		return (int)$this->db->query($sql, array((int)$projectId, (int)$stepOrder))->row()->total;
	}
	public function project_approvals($projectId)
	{
		return $this->db->select('pa.*,aw.step_order,aw.step_code,aw.step_name,u.name approver_name')->from('project_approvals pa')->join('approval_workflows aw', 'aw.id=pa.workflow_id')->join('users u', 'u.id=pa.approver_id', 'left')->where('pa.project_id', (int)$projectId)->order_by('aw.step_order')->get()->result_array();
	}
	public function latest_expected_timeline($projectId)
	{
		$row = $this->db->select('tr.expected_start_date,tr.expected_end_date,u.name reviewer_name,tr.review_date')
			->from('technical_reviews tr')
			->join('users u', 'u.id=tr.reviewer_id', 'left')
			->where('tr.project_id', (int)$projectId)
			->where('tr.expected_start_date IS NOT NULL', null, false)
			->where('tr.expected_end_date IS NOT NULL', null, false)
			->order_by('tr.id', 'DESC')->limit(1)->get()->row_array();
		return $row ?: null;
	}
	public function remarks_for_project($projectId)
	{
		/*
         * One row per approval action, newest first.
         *
         * IMPORTANT:
         * Do not read historical remarks from project_approvals.comments because
         * that row is reused when a returned step is reopened. project_history
         * is append-only and therefore preserves every individual action.
         *
         * Technical details are joined by the exact technical_review_id saved
         * with that history action. This prevents a newer technical review from
         * being copied onto older remarks by the same reviewer.
         */
		$sql = "
            SELECT
                ph.id history_id,
                ph.action,
                ph.new_status status,
                ph.comments,
                CASE
                    WHEN ph.action IN (
                        'PROJECT_RETURNED_TO_PREVIOUS_APPROVER',
                        'PROJECT_RETURNED_TO_REQUESTOR'
                    ) THEN ph.comments
                    ELSE NULL
                END AS return_comments,
                ph.action_date,
                u.name approver_name,
                r.role_name,
                COALESCE(aw.step_name, r.role_name, 'Approval') AS step_name,
                COALESCE(aw.requires_technical_review,0) AS requires_technical_review,
                tr.feasibility,
                tr.estimated_effort,
                tr.technical_risks,
                tr.dependencies,
                tr.technical_comments,
                tr.expected_start_date,
                tr.expected_end_date
            FROM project_history ph
            JOIN users u ON u.id=ph.user_id
            LEFT JOIN roles r ON r.id=u.role_id
            LEFT JOIN approval_workflows aw ON aw.id=ph.workflow_id
            LEFT JOIN technical_reviews tr ON tr.id=ph.technical_review_id
            WHERE ph.project_id=?
              AND ph.action IN (
                    'APPROVAL_APPROVED',
                    'PROJECT_REJECTED',
                    'PROJECT_RETURNED_TO_PREVIOUS_APPROVER',
                    'PROJECT_RETURNED_TO_REQUESTOR'
              )
              AND ph.comments IS NOT NULL
              AND TRIM(ph.comments)<>''
            ORDER BY ph.id DESC
        ";
		return $this->db->query($sql, array((int)$projectId))->result_array();
	}
}
