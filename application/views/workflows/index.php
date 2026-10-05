<?php if ($this->session->flashdata('workflow_success')): ?><div class="alert alert-success"><?= e($this->session->flashdata('workflow_success')) ?></div><?php endif; ?>
<?php if ($this->session->flashdata('workflow_error')): ?><div class="alert alert-danger"><?= e($this->session->flashdata('workflow_error')) ?></div><?php endif; ?>
<h2>Department Approval Workflows</h2>
<p>Department: <strong><?= e($department['department_name'] ?? '') ?></strong></p>
<?php if (!$categories): ?><div class="alert alert-warning">No project category is assigned to your department. An Administrator must first assign a project category from <strong>Department Categories</strong>.</div>
<?php else: ?>
	<?php if (!$selectedCategory): ?><button type="button" class="btn btn-primary mb-3" id="showWorkflowCategory">Add Workflow</button>
		<div class="card d-none" id="workflowCategoryCard">
			<div class="card-body">
				<h5>Select Project Category</h5>
				<form method="GET" action="<?= site_url('workflows') ?>" class="row g-2">
					<div class="col-md-10"><select name="category_id" class="form-select" required>
							<option value="">Select Project Category</option><?php foreach ($categories as $c): ?><option value="<?= (int)$c['id'] ?>"><?= e($c['category_name']) ?><?= !empty($c['team_name']) ? ' — Team: ' . e($c['team_name']) : '' ?></option><?php endforeach; ?>
						</select></div>
					<div class="col-md-2"><button class="btn btn-primary w-100">Continue</button></div>
				</form>
			</div>
		</div>
	<?php else: ?>
		<a class="btn btn-secondary btn-sm mb-3" href="<?= site_url('workflows') ?>">Change Category</a>
		<h4><?= e($selectedCategory['category_name']) ?> Workflow</h4>
		<p class="text-muted">Category Team: <strong><?= !empty($selectedCategory['team_name']) ? e($selectedCategory['team_name']) : 'Not assigned' ?></strong></p>
		<div class="card mb-4">
			<div class="card-body">
				<h5>Add Workflow Step</h5>
				<form method="POST" action="<?= site_url('workflows/add') ?>" class="row g-3"><?= csrf_field() ?><input type="hidden" name="category_id" value="<?= (int)$selectedCategoryId ?>">
					<div class="col-md-4"><label class="form-label">Step Name</label><input name="step_name" class="form-control" placeholder="e.g. Technical Review" required></div>
					<div class="col-md-3"><label class="form-label">Required Role</label><select name="role_id" class="form-select" required>
							<option value="">Select Role</option><?php foreach ($roles as $r): ?><option value="<?= (int)$r['id'] ?>"><?= e($r['role_name']) ?></option><?php endforeach; ?>
						</select></div>
					<div class="col-md-3"><label class="form-label">Approver Scope</label><select name="approver_scope" class="form-select" required>
							<option value="REQUESTING_DEPARTMENT">Requesting Department</option>
							<option value="CATEGORY_OWNER_DEPARTMENT" selected>Category Owner Department</option>
							<option value="CATEGORY_TEAM">Category Team</option>
							<option value="ORGANIZATION">Organization-wide</option>
						</select></div>
					<div class="col-md-2 d-flex align-items-end"><button class="btn btn-primary w-100">Add Step</button></div>
					<div class="col-12">
						<div class="form-check"><input class="form-check-input" type="checkbox" name="requires_technical_review" value="1" id="technicalReview"><label class="form-check-label" for="technicalReview">Collect technical review details on this approval step</label></div>
					</div>
				</form>

			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<h5>Defined Steps</h5>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Step</th>
							<th>Step Name</th>
							<th>Required Role</th>
							<th>Approver Scope</th>
							<th>Technical Details</th>
							<th>Order</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody><?php if (!$workflows): ?><tr>
								<td colspan="7" class="text-center text-muted">No workflow is defined for this category yet.</td>
							</tr><?php endif; ?><?php foreach ($workflows as $w): ?><tr>
								<td><?= (int)$w['step_order'] ?></td>
								<td><?= e($w['step_name']) ?></td>
								<td><?= e($w['role_name'] ?? '') ?></td>
								<td><?= e(ucwords(strtolower(str_replace('_', ' ', $w['approver_scope'] ?? 'CATEGORY_OWNER_DEPARTMENT')))) ?></td>
								<td><?= !empty($w['requires_technical_review']) ? 'Yes' : 'No' ?></td>
								<td class="text-nowrap">
									<?php $workflowIndex = array_search($w['id'], array_column($workflows, 'id')); ?>
									<?php if ($workflowIndex > 0): ?><form method="POST" action="<?= site_url('workflows/reorder/' . $w['id'] . '/up') ?>" class="d-inline"><?= csrf_field() ?><button class="btn btn-sm btn-outline-secondary" title="Move up">&uarr;</button></form><?php endif; ?>
									<?php if ($workflowIndex < count($workflows) - 1): ?><form method="POST" action="<?= site_url('workflows/reorder/' . $w['id'] . '/down') ?>" class="d-inline"><?= csrf_field() ?><button class="btn btn-sm btn-outline-secondary" title="Move down">&darr;</button></form><?php endif; ?>
								</td>
								<td><button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#edit<?= (int)$w['id'] ?>">Update</button>
									<form method="POST" action="<?= site_url('workflows/delete/' . $w['id']) ?>" class="d-inline" onsubmit="return confirm('Remove this workflow step?');"><?= csrf_field() ?><button class="btn btn-sm btn-danger">Remove</button></form>
									<div class="modal fade" id="edit<?= (int)$w['id'] ?>" tabindex="-1">
										<div class="modal-dialog">
											<div class="modal-content">
												<form method="POST" action="<?= site_url('workflows/update/' . $w['id']) ?>"><?= csrf_field() ?><div class="modal-header">
														<h5 class="modal-title">Update Workflow Step</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
													</div>
													<div class="modal-body">
														<div class="mb-3"><label class="form-label">Step Name</label><input name="step_name" class="form-control" value="<?= e($w['step_name']) ?>" required></div>
														<div class="mb-3"><label class="form-label">Required Role</label><select name="role_id" class="form-select" required><?php foreach ($roles as $r): ?><option value="<?= (int)$r['id'] ?>" <?= (int)$r['id'] === (int)$w['role_id'] ? 'selected' : '' ?>><?= e($r['role_name']) ?></option><?php endforeach; ?></select></div>
														<div class="mb-3"><label class="form-label">Approver Scope</label><select name="approver_scope" class="form-select"><?php foreach (array('REQUESTING_DEPARTMENT' => 'Requesting Department', 'CATEGORY_OWNER_DEPARTMENT' => 'Category Owner Department', 'CATEGORY_TEAM' => 'Category Team', 'ORGANIZATION' => 'Organization-wide') as $v => $label): ?><option value="<?= $v ?>" <?= ($w['approver_scope'] ?? 'CATEGORY_OWNER_DEPARTMENT') === $v ? 'selected' : '' ?>><?= $label ?></option><?php endforeach; ?></select></div>
														<div class="form-check"><input class="form-check-input" type="checkbox" name="requires_technical_review" value="1" id="tr<?= (int)$w['id'] ?>" <?= !empty($w['requires_technical_review']) ? 'checked' : '' ?>><label class="form-check-label" for="tr<?= (int)$w['id'] ?>">Collect technical review details</label></div>
													</div>
													<div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary">Update</button></div>
												</form>
											</div>
										</div>
									</div>
								</td>
							</tr><?php endforeach; ?></tbody>
				</table>
			</div>
		</div>
<?php endif;
endif; ?>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		var b = document.getElementById('showWorkflowCategory'),
			c = document.getElementById('workflowCategoryCard');
		if (b && c) b.addEventListener('click', function() {
			c.classList.remove('d-none');
			b.classList.add('d-none');
		});
	});
</script>