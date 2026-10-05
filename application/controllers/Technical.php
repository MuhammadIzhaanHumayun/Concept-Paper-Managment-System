<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Technical extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->require_login();
	}
	public function review($projectId, $approvalId)
	{
		redirect('approvals/review/' . $projectId . '/' . $approvalId);
	}
	public function save_review()
	{
		show_error('Technical review submission is now handled securely through the approval action endpoint.', 410);
	}
}
