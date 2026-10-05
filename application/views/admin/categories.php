<?php if ($this->session->flashdata('admin_success')): ?>
	<div class="alert alert-success"><?= e($this->session->flashdata('admin_success')) ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('admin_error')): ?>
	<div class="alert alert-danger"><?= e($this->session->flashdata('admin_error')) ?></div>
<?php endif; ?>

<h2 class="mb-4">
	Project Categories
</h2>

<div class="card mb-4">

	<div class="card-header">
		<h5 class="mb-0">Add Project Category</h5>
	</div>

	<div class="card-body">

		<form method="POST" action="<?= site_url('admin/categories') ?>">
			<?= csrf_field() ?>

			<div class="row">

				<div class="col-md-3 mb-3">
					<label class="form-label">Category Code <span class="text-danger">*</span></label>
					<input type="text" name="category_code" class="form-control" placeholder="e.g. SEC" required>
				</div>

				<div class="col-md-3 mb-3">
					<label class="form-label">Category Name <span class="text-danger">*</span></label>
					<input type="text" name="category_name" class="form-control" placeholder="e.g. Security" required>
				</div>

				<div class="col-md-6 mb-3">
					<label class="form-label">Criteria <span class="text-danger">*</span></label>
					<input type="text" name="criteria" class="form-control" placeholder="Describe when this category should be used" required>
				</div>

			</div>

			<div class="d-flex justify-content-end">
				<button type="submit" class="btn btn-primary">Add Category</button>
			</div>

		</form>

	</div>

</div>

<div class="card">

	<div class="card-body">

		<table class="table table-bordered">

			<thead>
				<tr>
					<th>Code</th>
					<th>Category</th>
					<th>Criteria</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
			</thead>

			<tbody>

				<?php foreach ($categories as $category): ?>
					<tr>
						<td><?= e($category["category_code"]) ?></td>
						<td><?= e($category["category_name"]) ?></td>
						<td><?= e($category["criteria"]) ?></td>
						<td><?= e($category["status"]) ?></td>
						<td>
							<form method="POST" action="<?= site_url('admin/categories/delete/' . $category["id"]) ?>" class="d-inline" onsubmit="return confirm('Remove this project category? This action cannot be undone.');">
								<?= csrf_field() ?>
								<button type="submit" class="btn btn-sm btn-danger">Remove</button>
							</form>
						</td>
					</tr>
				<?php endforeach; ?>

			</tbody>

		</table>

	</div>

</div>