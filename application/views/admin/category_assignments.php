<?php if ($this->session->flashdata('admin_success')): ?>
	<div class="alert alert-success"><?= e($this->session->flashdata('admin_success')) ?>
	</div>
<?php endif; ?>
<?php if ($this->session->flashdata('admin_error')): ?>
	<div class="alert alert-danger"><?= e($this->session->flashdata('admin_error')) ?>
	</div>
<?php endif; ?>
<h2 class="mb-4">Department Project Categories</h2>
<form method="POST" action="<?= site_url('admin/category-assignments') ?>" class="row g-2 mb-4">
	<?= csrf_field() ?>
	<div class="col-md-4">
		<label class="form-label">Department</label>
		<select name="department_id" id="department_id" class="form-select" required>
			<option value="">Select Department</option>
			<?php foreach ($departments as $d): ?>
				<option value="<?= (int)$d['id'] ?>">
					<?= e($d['department_name']) ?>
				</option><?php endforeach; ?>
		</select>
	</div>
	<div class="col-md-4">
		<label class="form-label">Project Category</label>
		<select name="category_id" class="form-select" required>
			<option value="">Select Category</option>
			<?php foreach ($categories as $c): ?>
				<option value="<?= (int)$c['id'] ?>">
					<?= e($c['category_name']) ?></option><?php endforeach; ?>
		</select>
	</div>
	<div class="col-md-3">
		<label class="form-label">Default Team</label>
		<select name="team_id" id="team_id" class="form-select">
			<option value="">No team</option>
			<?php foreach ($teams as $t): ?>
				<option value="<?= (int)$t['id'] ?>" data-department="<?= (int)$t['department_id'] ?>">
					<?= e($t['team_name']) ?>
				</option><?php endforeach; ?>
		</select>
	</div>
	<div class="col-md-1 d-flex align-items-end">
		<button class="btn btn-primary w-100">Assign</button>
	</div>
</form>
<table class="table table-bordered bg-white">
	<thead>
		<tr>
			<th>S.NO</th>
			<th>Department</th>
			<th>Project Category</th>
			<th>Default Team</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody><?php $n = 1;
			foreach ($assignments as $a): ?><tr>
				<td><?= $n++ ?></td>
				<td><?= e($a['department_name']) ?></td>
				<td><?= e($a['category_name']) ?></td>
				<td><?= e($a['team_name'] ?? '') ?></td>
				<td>
					<form method="POST" action="<?= site_url('admin/category-assignments/delete/' . $a['department_id'] . '/' . $a['category_id']) ?>" onsubmit="return confirm('Remove this assignment?');"><?= csrf_field() ?><button class="btn btn-sm btn-danger">Remove</button></form>
				</td>
			</tr><?php endforeach; ?></tbody>
</table>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		var d = document.getElementById('department_id'),
			t = document.getElementById('team_id');

		function f() {
			var v = d.value;
			Array.from(t.options).forEach(function(o, i) {
				if (i === 0) return;
				o.hidden = !!v && o.dataset.department !== v;
			});
			if (t.selectedOptions[0] && t.selectedOptions[0].hidden) t.value = '';
		}
		d.addEventListener('change', f);
		f();
	});
</script>