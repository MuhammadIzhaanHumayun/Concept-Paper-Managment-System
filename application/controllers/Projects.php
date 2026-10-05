<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Projects extends MY_Controller {
    public function __construct(){ parent::__construct(); $this->require_login(); $this->load->model(array('Project_model','Workflow_model','Approval_model','History_model')); }
    public function index(){
        $this->require_requestor();

        $filter = strtolower(trim((string)$this->input->get('status', TRUE)));
        $allowedFilters = array('all','pending','returned','drafts','approved','rejected');
        if ($filter === '' || !in_array($filter, $allowedFilters, true)) {
            $filter = 'all';
        }

        $this->render('projects/index',array(
            'page_title'=>'Projects',
            'projects'=>$this->Project_model->list_for_user($this->user, $filter),
            'filter'=>$filter
        ));
    }
    public function create(){ $this->require_requestor(); $cats=$this->db->order_by('id')->get('project_categories')->result_array(); $this->render('projects/create',array('page_title'=>'New Concept Paper','categories'=>$cats)); }
    public function store(){
        $this->require_requestor(); if($this->input->method(TRUE)!=='POST') show_404();
        $action=strtoupper((string)$this->input->post('action',TRUE)); if(!in_array($action,array('DRAFT','SUBMIT'),true)) $action='DRAFT';
        $categoryId=(int)$this->input->post('category_id'); $category=$this->db->where('id',$categoryId)->get('project_categories')->row_array(); if(!$category) show_error('Invalid category.',400);
        $status=$action==='SUBMIT'?'SUBMITTED':'DRAFT';
        $project=array('project_id'=>$this->Project_model->generate_project_id($category['category_code']),'project_title'=>trim($this->input->post('project_title',TRUE)),'category_id'=>$categoryId,'requestor_id'=>$this->user['id'],'department_id'=>$this->user['department_id'],'priority'=>$this->input->post('priority',TRUE),'current_state'=>$this->input->post('current_state',TRUE),'proposed_state'=>$this->input->post('proposed_state',TRUE),'scope_included'=>$this->input->post('scope_included',TRUE),'scope_excluded'=>$this->input->post('scope_excluded',TRUE),'start_date'=>null,'end_date'=>$this->input->post('end_date')?:null,'status'=>$status);
        $this->db->trans_begin(); $this->db->insert('projects',$project); $id=$this->db->insert_id();
        $types=$this->input->post('stakeholder_type')?:array(); $names=$this->input->post('stakeholder_name')?:array(); $deps=$this->input->post('stakeholder_department')?:array(); $emails=$this->input->post('stakeholder_email')?:array();
        foreach($names as $i=>$name){ $name=trim($name); if($name==='') continue; $this->db->insert('stakeholders',array('project_id'=>$id,'stakeholder_type'=>$types[$i]??'USER','stakeholder_name'=>$name,'department_name'=>trim($deps[$i]??''),'email'=>trim($emails[$i]??''))); }
        $this->History_model->add($id,$this->user['id'],'PROJECT_CREATED',null,$status);
        if($action==='SUBMIT'){
            $full=$this->Project_model->get($id); $steps=$this->Workflow_model->steps_for_category($categoryId,$this->Workflow_model->owner_department_for_category($categoryId)); if(!$steps){$this->db->trans_rollback(); show_error('No department approval workflow has been configured for this project category by the owning department.',500);}
            $steps=$this->Workflow_model->effective_steps_for_project($steps,$full);
            foreach($steps as $step){ $approver=(int)($step['resolved_approver_id']??0); if(!$approver){$this->db->trans_rollback(); show_error('Approver could not be resolved for '.$step['step_name'].'.',500);} $this->db->insert('project_approvals',array('project_id'=>$id,'workflow_id'=>$step['id'],'approver_id'=>$approver,'status'=>'PENDING')); }
        }
        if($this->db->trans_status()===FALSE){$this->db->trans_rollback(); show_error('Unable to save project.',500);} $this->db->trans_commit(); redirect('projects/view/'.$id);
    }

    public function edit($id){
        $this->require_requestor();
        $p=$this->Project_model->get($id); if(!$p) show_404();
        if((int)$p['requestor_id']!==(int)$this->user['id'] || !in_array($p['status'],array('DRAFT','RETURNED_FOR_REVISION'),true)) show_error('This project cannot be edited.',403);
        $stakeholders=$this->db->select('id,project_id,stakeholder_type AS type,stakeholder_name AS name,department_name AS department,email')->where('project_id',$id)->order_by('id')->get('stakeholders')->result_array();
        $this->render('projects/edit',array('page_title'=>'Edit Project','project'=>$p,'stakeholders'=>$stakeholders));
    }
    public function update($id){
        $this->require_requestor(); if($this->input->method(TRUE)!=='POST') show_404();
        $p=$this->Project_model->get($id); if(!$p) show_404();
        if((int)$p['requestor_id']!==(int)$this->user['id']) show_error('Access denied.',403);
        if(!in_array($p['status'],array('DRAFT','RETURNED_FOR_REVISION'),true)) show_error('This project cannot be edited.',403);
        $action=strtoupper((string)$this->input->post('action',TRUE)); if(!in_array($action,array('DRAFT','SUBMIT'),true)) show_error('Invalid action.',400);
        $priority=$this->input->post('priority',TRUE); if(!in_array($priority,array('HIGH','MEDIUM','LOW'),true)) show_error('Invalid priority.',400);
        $data=array('project_title'=>trim($this->input->post('project_title',TRUE)),'priority'=>$priority,'current_state'=>trim($this->input->post('current_state',TRUE)),'proposed_state'=>trim($this->input->post('proposed_state',TRUE)),'scope_included'=>trim($this->input->post('scope_included',TRUE)),'scope_excluded'=>trim($this->input->post('scope_excluded',TRUE)),'start_date'=>null,'end_date'=>$this->input->post('end_date')?:null,'status'=>$action==='SUBMIT'?'SUBMITTED':'DRAFT','updated_at'=>date('Y-m-d H:i:s'));
        foreach(array('project_title','current_state','proposed_state','scope_included','scope_excluded') as $f) if($data[$f]==='') show_error('Please complete all required fields.',400);
        $old=$p['status']; $this->db->trans_begin(); $this->db->where('id',$id)->update('projects',$data);
        $this->db->where('project_id',$id)->delete('stakeholders');
        $types=$this->input->post('stakeholder_type')?:array(); $names=$this->input->post('stakeholder_name')?:array(); $deps=$this->input->post('stakeholder_department')?:array(); $emails=$this->input->post('stakeholder_email')?:array();
        $count=max(count($types),count($names),count($deps),count($emails));
        for($i=0;$i<$count;$i++){ $type=trim($types[$i]??'');$name=trim($names[$i]??'');$dep=trim($deps[$i]??'');$email=trim($emails[$i]??''); if($type===''&&$name===''&&$dep===''&&$email==='') continue; if(!in_array($type,array('SPONSOR','USER','TECHNICAL_TEAM','APPROVER'),true)||$name===''){ $this->db->trans_rollback(); show_error('Invalid stakeholder data.',400);} $this->db->insert('stakeholders',array('project_id'=>$id,'stakeholder_type'=>$type,'stakeholder_name'=>$name,'department_name'=>$dep?:null,'email'=>$email?:null)); }
        if($action==='DRAFT'){ $this->History_model->add($id,$this->user['id'],'PROJECT_UPDATED',$old,'DRAFT'); }
        else {
            $existing=$this->db->where('project_id',$id)->count_all_results('project_approvals');
            if($existing===0){
                $full=$this->Project_model->get($id); $steps=$this->Workflow_model->steps_for_category((int)$p['category_id'],$this->Workflow_model->owner_department_for_category((int)$p['category_id'])); if(!$steps){$this->db->trans_rollback(); show_error('No department approval workflow has been configured for this project category by the owning department.',500);} $steps=$this->Workflow_model->effective_steps_for_project($steps,$full); foreach($steps as $step){$approver=(int)($step['resolved_approver_id']??0);if(!$approver){$this->db->trans_rollback();show_error('Approver could not be resolved for '.$step['step_name'].'.',500);} $this->db->insert('project_approvals',array('project_id'=>$id,'workflow_id'=>$step['id'],'approver_id'=>$approver,'status'=>'PENDING'));}
                $this->History_model->add($id,$this->user['id'],'PROJECT_SUBMITTED',$old,'SUBMITTED');
            } else {
                // A resubmission starts with the department's CURRENT workflow.
                // This allows a Head's workflow changes to apply to revised concept papers.
                $full=$this->Project_model->get($id);
                $steps=$this->Workflow_model->steps_for_category((int)$p['category_id'],$this->Workflow_model->owner_department_for_category((int)$p['category_id']));
                if(!$steps){$this->db->trans_rollback();show_error('No approval workflow is configured for this department and project category.',500);}

                $steps=$this->Workflow_model->effective_steps_for_project($steps,$full);
                $this->db->where('project_id',$id)->delete('project_approvals');
                foreach($steps as $step){
                    $approver=(int)($step['resolved_approver_id']??0);
                    if(!$approver){$this->db->trans_rollback();show_error('Approver could not be resolved for '.$step['step_name'].'.',500);}
                    $this->db->insert('project_approvals',array(
                        'project_id'=>$id,'workflow_id'=>$step['id'],'approver_id'=>$approver,'status'=>'PENDING'
                    ));
                }
                $this->History_model->add($id,$this->user['id'],'PROJECT_RESUBMITTED',$old,'SUBMITTED');
            }
        }
        if($this->db->trans_status()===FALSE){$this->db->trans_rollback();show_error('Unable to update project.',500);} $this->db->trans_commit(); redirect('projects/view/'.$id);
    }
    public function delete($id){
        $this->require_requestor(); if($this->input->method(TRUE)!=='POST') show_404(); $p=$this->Project_model->get($id); if(!$p) show_404();
        if((int)$p['requestor_id']!==(int)$this->user['id']) show_error('Access denied.',403); if($p['status']!=='DRAFT') show_error('Only draft projects can be deleted.',403);
        $this->db->where('id',$id)->delete('projects'); redirect('projects');
    }

    public function view($id){
        $p=$this->Project_model->get($id); if(!$p) show_404();
        $isOwner=(int)$p['requestor_id']===(int)$this->user['id']; $isAssigned=$this->db->where('project_id',(int)$id)->where('approver_id',(int)$this->user['id'])->count_all_results('project_approvals')>0;
        if(!$isOwner && !$isAssigned && !$this->is_admin()) show_error('Access denied.',403);
        $data=array('page_title'=>$p['project_id'],'project'=>$p,'stakeholders'=>$this->db->where('project_id',$id)->order_by('id')->get('stakeholders')->result_array(),'approvals'=>$this->Approval_model->project_approvals($id),'history'=>$this->History_model->for_project($id),
            'remarks'=>$this->Approval_model->remarks_for_project($id),
            'technicalTimeline'=>$this->Approval_model->latest_expected_timeline($id));
        $this->render('projects/view',$data);
    }
    public function print_project($id){
        $p=$this->Project_model->get($id); if(!$p) show_404(); if($p['status']!=='APPROVED') show_error('Printing is available only after the project has been approved.',403); if((int)$p['requestor_id']!==(int)$this->user['id']) show_error('Access denied. Only the project requestor can print this concept paper.',403);
        $data=array('project'=>$p,'stakeholders'=>$this->db->where('project_id',$id)->order_by('id')->get('stakeholders')->result_array(),'approvals'=>$this->Approval_model->project_approvals($id),
            'technicalTimeline'=>$this->Approval_model->latest_expected_timeline($id)); $this->load->view('projects/print',$data);
    }
}
