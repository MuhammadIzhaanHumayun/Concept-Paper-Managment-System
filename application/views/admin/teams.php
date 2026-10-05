<?php if ($this->session->flashdata('admin_success')): ?>
	<div class="alert alert-success"><?= e($this->session->flashdata('admin_success')) ?>
	</div><?php endif; ?>
<?php if ($this->session->flashdata('admin_error')): ?>
	<div class="alert alert-danger"><?= e($this->session->flashdata('admin_error')) ?>
	</div><?php endif; ?>
<h2 class="mb-4">Teams</h2>
<form method="POST" action="<?= site_url('admin/teams') ?>" class="row g-2 mb-4">
	<?= csrf_field() ?>
	<div class="col-md-5">
		<label class="form-label">Department</label>
		<select name="department_id" class="form-select" required>
			<option value="">Select Department</option>
			<?php foreach ($departments as $d): ?>
				<option value="<?= (int)$d['id'] ?>">
					<?= e($d['department_name']) ?>
				</option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="col-md-5">
		<label class="form-label">Team Name</label>
		<input name="team_name" class="form-control" placeholder="e.g. Application" required>
	</div>
	<div class="col-md-2 d-flex align-items-end">
		<button class="btn btn-primary w-100">Add Team</button>
	</div>
</form>
<table class="table table-bordered bg-white">
	<thead>
		<tr>
			<th>S.NO</th>
			<th>Department</th>
			<th>Team</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody><?php $n = 1;
			foreach ($teams as $t): ?><tr>
				<td><?= $n++ ?></td>
				<td><?= e($t['department_name']) ?></td>
				<td><?= e($t['team_name']) ?></td>
				<td>
					<form method="POST" action="<?= site_url('admin/teams/delete/' . $t['id']) ?>" onsubmit="return confirm('Remove this team?');"><?= csrf_field() ?><button class="btn btn-sm btn-danger">Remove</button></form>
				</td>
			</tr><?php endforeach; ?></tbody>
</table>
