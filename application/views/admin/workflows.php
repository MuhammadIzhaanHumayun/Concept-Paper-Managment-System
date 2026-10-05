<?php if ($this->session->flashdata('admin_success')): ?><div class="alert alert-success"><?= e($this->session->flashdata('admin_success')) ?></div><?php endif; ?>
<?php if ($this->session->flashdata('admin_error')): ?><div class="alert alert-danger"><?= e($this->session->flashdata('admin_error')) ?></div><?php endif; ?>
<h2 class="mb-4">Approval Workflows</h2>
<div class="card">
	<div class="card-body">
		<table class="table table-bordered">
			<thead>
				<tr class="align-middle ">
					<th>Department</th>
					<th>Category</th>
					<th>Step</th>
					<th>Step Name</th>
					<th>Required Role</th>
					<th>Approver Scope</th>
					<th>Technical Details</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!$workflows): ?><tr>
						<td colspan="7" class="text-center text-muted">No approval workflows configured.</td>
					</tr><?php endif; ?>
				<?php foreach ($workflows as $w): ?><tr>
						<td><?= e($w['department_name'] ?? '') ?></td>
						<td><?= e($w['category_name']) ?></td>
						<td><?= (int)$w['step_order'] ?></td>
						<td><?= e($w['step_name']) ?></td>
						<td><?= e($w['role_name'] ?? '') ?></td>
						<td><?= e(ucwords(strtolower(str_replace('_', ' ', $w['approver_scope'] ?? 'CATEGORY_OWNER_DEPARTMENT')))) ?></td>
						<td><?= !empty($w['requires_technical_review']) ? 'Yes' : 'No' ?></td>
					</tr><?php endforeach; ?></tbody>
		</table>
	</div>
</div>
