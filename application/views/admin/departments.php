<?php if ($this->session->flashdata('admin_success')): ?>
	<div class="alert alert-success"><?= e($this->session->flashdata('admin_success')) ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('admin_error')): ?>
	<div class="alert alert-danger"><?= e($this->session->flashdata('admin_error')) ?></div>
<?php endif; ?>

<h2 class="mb-4">
	Departments
</h2>


<form
	method="POST"
	class="row g-2 mb-4">
	<?= csrf_field() ?>


	<div class="col-md-3">

		<input
			name="department_code"
			class="form-control"
			placeholder="Department Code"
			required>

	</div>


	<div class="col-md-6">

		<input
			name="department_name"
			class="form-control"
			placeholder="Department Name"
			required>

	</div>


	<div class="col-md-3">

		<button
			class="btn btn-primary">

			Add Department

		</button>

	</div>


</form>


<table
	class="table table-bordered bg-white">


	<thead>

		<tr>
			<th>Code</th>

			<th>Name</th>

			<th>Status</th>

			<th>Action</th>

		</tr>

	</thead>


	<tbody>


		<?php foreach ($departments as $d): ?>

			<tr>
				<td>
					<?= e($d["department_code"]) ?>
				</td>

				<td>
					<?= e($d["department_name"]) ?>
				</td>

				<td>
					<?= e($d["status"]) ?>
				</td>

				<td>
					<form method="POST" action="<?= site_url('admin/departments/delete/' . $d["id"]) ?>" class="d-inline" onsubmit="return confirm('Remove this department? This action cannot be undone.');">
						<?= csrf_field() ?>
						<button type="submit" class="btn btn-sm btn-danger">Remove</button>
					</form>
				</td>

			</tr>

		<?php endforeach; ?>


	</tbody>

</table>
