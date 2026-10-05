<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Project_model extends CI_Model
{
	public function get($id)
	{
		return $this->db->select('p.*, c.category_name, c.category_code, c.criteria, u.name requestor_name, u.email requestor_email, d.department_name')
			->from('projects p')->join('project_categories c', 'c.id=p.category_id')->join('users u', 'u.id=p.requestor_id')
			->join('departments d', 'd.id=p.department_id')->where('p.id', (int)$id)->get()->row_array();
	}
	public function list_for_user($user, $filter = 'all')
	{
		$this->db->select('p.*, c.category_name')
			->from('projects p')
			->join('project_categories c', 'c.id=p.category_id')
			->where('p.requestor_id', (int)$user['id']);

		switch ($filter) {
			case 'pending':
				$this->db->where('p.status', 'SUBMITTED');
				break;

			case 'returned':
				$this->db->where('p.status', 'RETURNED_FOR_REVISION');
				break;

			case 'drafts':
				$this->db->where('p.status', 'DRAFT');
				break;

			case 'approved':
				$this->db->where('p.status', 'APPROVED');
				break;

			case 'rejected':
				$this->db->where('p.status', 'REJECTED');
				break;
		}

		return $this->db
			->order_by("CASE UPPER(p.priority) WHEN 'HIGH' THEN 1 WHEN 'MEDIUM' THEN 2 WHEN 'LOW' THEN 3 ELSE 4 END", '', FALSE)
			->order_by('p.id', 'DESC')
			->get()->result_array();
	}
	public function generate_project_id($categoryCode)
	{
		$year = date('Y');
		$prefix = 'CP-' . strtoupper($categoryCode) . '-' . $year . '-';
		$last = $this->db->select('project_id')->like('project_id', $prefix, 'after')->order_by('id', 'DESC')->limit(1)->get('projects')->row_array();
		$number = 1;
		if ($last && preg_match('/-(\d+)$/', $last['project_id'], $m)) $number = ((int)$m[1]) + 1;
		return $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);
	}
	public function status_count($requestorId, $status)
	{
		return $this->db->where('requestor_id', (int)$requestorId)->where('status', $status)->count_all_results('projects');
	}
}
