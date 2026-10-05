<?php if ($this->session->flashdata('admin_success')): ?>
	<div class="alert alert-success"><?= e($this->session->flashdata('admin_success')) ?></div>
<?php endif; ?>

<?php if ($this->session->flashdata('admin_error')): ?>
	<div class="alert alert-danger"><?= e($this->session->flashdata('admin_error')) ?></div>
<?php endif; ?>

<h2 class="mb-4">Roles</h2>

<form method="POST" action="<?= site_url('admin/roles') ?>" class="row g-2 mb-4">
	<?= csrf_field() ?>

	<div class="col-md-6">
		<input type="text" name="role_name" class="form-control" placeholder="Role Name" required>
	</div>

	<div class="col-md-3 d-flex align-items-center">
		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="can_define_workflow" value="1" id="newCanDefineWorkflow">
			<label class="form-check-label" for="newCanDefineWorkflow">Can Define Workflow</label>
		</div>
	</div>

	<div class="col-md-3">
		<button type="submit" class="btn btn-primary w-100">Add Role</button>
	</div>
</form>

<table class="table table-bordered bg-white">
	<thead>
		<tr>
			<th>Role</th>
			<th>Can Define Workflow</th>
			<th>Action</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($roles as $r): ?>
			<tr>
				<td><?= e($r['role_name']) ?></td>
				<td><?= !empty($r['can_define_workflow']) ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' ?></td>
				<td>
					<button
						type="button"
						class="btn btn-sm btn-primary"
						data-bs-toggle="modal"
						data-bs-target="#editRoleModal<?= (int)$r['id'] ?>">
						Update
					</button>

					<form
						method="POST"
						action="<?= site_url('admin/roles/delete/' . $r['id']) ?>"
						class="d-inline"
						onsubmit="return confirm('Remove this role? Users assigned to it will remain in the system with an empty role.');">
						<?= csrf_field() ?>
						<button type="submit" class="btn btn-sm btn-danger">Remove</button>
					</form>

					<div class="modal fade" id="editRoleModal<?= (int)$r['id'] ?>" tabindex="-1" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">

								<form method="POST" action="<?= site_url('admin/roles/update/' . $r['id']) ?>">
									<?= csrf_field() ?>

									<div class="modal-header">
										<h5 class="modal-title">Update Role</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>

									<div class="modal-body">
										<label class="form-label">Role Name</label>
										<input
											type="text"
											name="role_name"
											class="form-control"
											value="<?= e($r['role_name']) ?>"
											required>
									</div>

									<div class="form-check mt-3 ms-3">
										<input class="form-check-input" type="checkbox" name="can_define_workflow" value="1"
											id="canDefineWorkflow<?= (int)$r['id'] ?>" <?= !empty($r['can_define_workflow']) ? 'checked' : '' ?>>
										<label class="form-check-label" for="canDefineWorkflow<?= (int)$r['id'] ?>">
											Can Define Workflow
										</label>
									</div>

									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
										<button type="submit" class="btn btn-primary">Update</button>
									</div>

								</form>

							</div>
						</div>
					</div>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>