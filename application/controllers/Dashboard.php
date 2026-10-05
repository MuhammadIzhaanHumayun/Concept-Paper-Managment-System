<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->require_login();
		$this->load->model(array('Project_model', 'Approval_model'));
	}
	public function index()
	{
		$data = array('page_title' => 'Dashboard', 'isApprover' => $this->is_approver(), 'counts' => array());
		if (($this->user['role_name'] ?? '') === 'Requestor') foreach (array('DRAFT', 'SUBMITTED', 'RETURNED_FOR_REVISION', 'REJECTED', 'APPROVED') as $s) $data['counts'][$s] = $this->Project_model->status_count($this->user['id'], $s);
		$data['pendingApprovals'] = $data['isApprover'] ? $this->Approval_model->pending_count($this->user['id']) : 0;
		$data['returnedApprovals'] = $data['isApprover'] ? $this->Approval_model->returned_count($this->user['id']) : 0;
		$data['rejectedApprovals'] = $data['isApprover'] ? $this->db->where('approver_id', $this->user['id'])->where('status', 'REJECTED')->count_all_results('project_approvals') : 0;
		$this->render('dashboard/index', $data);
	}
}
