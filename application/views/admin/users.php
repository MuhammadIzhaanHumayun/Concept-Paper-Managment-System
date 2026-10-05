<?php if ($this->session->flashdata('admin_success')): ?>
	<div class="alert alert-success"><?= e($this->session->flashdata('admin_success')) ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('admin_error')): ?>
	<div class="alert alert-danger"><?= e($this->session->flashdata('admin_error')) ?></div>
<?php endif; ?>

<div class="d-flex justify-content-between">

	<h2>
		Users
	</h2>

	<a
		href="<?= site_url('admin/users/add') ?>"
		class="btn btn-primary">

		Add User

	</a>

</div>


<table class="table table-bordered bg-white mt-3">

	<thead>

		<tr>

			<th>Employee ID</th>

			<th>Name</th>

			<th>Email</th>

			<th>Department</th>

			<th>Role</th>

			<th>Status</th>

			<th>Action</th>

		</tr>

	</thead>


	<tbody>


		<?php foreach ($users as $u): ?>

			<tr>

				<td>
					<?= e($u["employee_id"]) ?>
				</td>

				<td>
					<?= e($u["name"]) ?>
				</td>

				<td>
					<?= e($u["email"]) ?>
				</td>

				<td>
					<?= e($u["department_name"]) ?>
				</td>

				<td>
					<?= e($u["role_name"]) ?>
				</td>

				<td>
					<?= e($u["status"]) ?>
				</td>

				<td>

					<a
						href="<?= site_url('admin/users/edit/' . $u["id"]) ?>"
						class="btn btn-sm btn-warning">

						Edit

					</a>

					<form method="POST" action="<?= site_url('admin/users/delete/' . $u["id"]) ?>" class="d-inline" onsubmit="return confirm('Remove this user? This action cannot be undone.');">
						<?= csrf_field() ?>
						<button type="submit" class="btn btn-sm btn-danger">Remove</button>
					</form>

				</td>

			</tr>

		<?php endforeach; ?>


	</tbody>

</table>