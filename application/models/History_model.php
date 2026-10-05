<?php
defined('BASEPATH') or exit('No direct script access allowed');

class History_model extends CI_Model
{

	public function add($projectId, $userId, $action, $oldStatus = null, $newStatus = null, $comments = null, $approvalId = null, $workflowId = null, $technicalReviewId = null)
	{
		return $this->db->insert('project_history', array(
			'project_id' => (int)$projectId,
			'user_id' => (int)$userId,
			'action' => $action,
			'old_status' => $oldStatus,
			'new_status' => $newStatus,
			'comments' => $comments,
			'approval_id' => $approvalId ? (int)$approvalId : null,
			'workflow_id' => $workflowId ? (int)$workflowId : null,
			'technical_review_id' => $technicalReviewId ? (int)$technicalReviewId : null,
			'action_date' => date('Y-m-d H:i:s')
		));
	}

	public function for_project($projectId)
	{
		return $this->db->select('ph.*,u.name user_name')
			->from('project_history ph')
			->join('users u', 'u.id=ph.user_id')
			->where('ph.project_id', (int)$projectId)
			->order_by('ph.id', 'DESC')
			->get()->result_array();
	}
}
