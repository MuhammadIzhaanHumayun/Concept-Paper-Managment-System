<?php
$oldInput = $this->session->flashdata('user_form_old');
$oldInput = is_array($oldInput) ? $oldInput : array();
$formUser = (($mode ?? 'add') === 'edit')
	? array_merge((array)$userData, $oldInput)
	: $oldInput;
?>
<?php if ($this->session->flashdata('admin_error')): ?>
	<div class="alert alert-danger"><?= e($this->session->flashdata('admin_error')) ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('admin_success')): ?>
	<div class="alert alert-success"><?= e($this->session->flashdata('admin_success')) ?></div>
<?php endif; ?>

<?php if (($mode ?? 'add') === 'edit'): ?>
	<h2>
		Edit User
	</h2>


	<form method="POST">
		<?= csrf_field() ?>


		<div class="mb-3">

			<label>
				Employee ID
			</label>

			<input
				class="form-control"
				value="<?= e($userData["employee_id"]) ?>"
				readonly>

		</div>


		<div class="mb-3">

			<label>
				Name
			</label>

			<input
				name="name"
				class="form-control"
				value="<?= e($formUser["name"] ?? "") ?>"
				required>

		</div>


		<div class="mb-3">

			<label>
				Email
			</label>

			<input
				type="email"
				name="email"
				class="form-control"
				value="<?= e($formUser["email"] ?? "") ?>"
				required>

		</div>


		<div class="mb-3">

			<label>
				New Password
			</label>

			<input
				type="password"
				name="password"
				class="form-control">

		</div>


		<div class="mb-3">

			<label>
				Department
			</label>

			<select
				name="department_id"
				id="department_id"
				class="form-select">

				<option value="" <?= empty($formUser["department_id"]) ? "selected" : "" ?>>None / Organization-wide</option>

				<?php foreach ($departments as $d): ?>

					<option
						value="<?= $d["id"] ?>"
						<?= $d["id"] == ($formUser["department_id"] ?? null)
							? "selected"
							: "" ?>>

						<?= e($d["department_name"]) ?>

					</option>

				<?php endforeach; ?>

			</select>

		</div>


		<div class="mb-3">
			<label>Team</label>
			<select name="team_id" id="team_id" class="form-select">
				<option value="">No Team</option>
				<?php foreach ($teams as $t): ?>
					<option value="<?= (int)$t["id"] ?>" data-department="<?= (int)$t["department_id"] ?>" <?= (int)($formUser["team_id"] ?? 0) === (int)$t["id"] ? "selected" : "" ?>><?= e($t["department_name"] . " - " . $t["team_name"]) ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="mb-3">

			<label>
				Role
			</label>

			<select name="role_id" class="form-select">
				<?php foreach ($roles as $r): ?>
					<option value="<?= $r["id"] ?>" <?= $r["id"] == ($formUser["role_id"] ?? null) ? "selected" : "" ?>><?= e($r["role_name"]) ?></option>
				<?php endforeach; ?>
			</select>

		</div>


		<div class="mb-3">

			<label>
				Status
			</label>

			<select
				name="status"
				class="form-select">

				<option
					value="ACTIVE"
					<?= ($formUser["status"] ?? "ACTIVE") === "ACTIVE"
						? "selected"
						: "" ?>>

					Active

				</option>


				<option
					value="INACTIVE"
					<?= ($formUser["status"] ?? "ACTIVE") === "INACTIVE"
						? "selected"
						: "" ?>>

					Inactive

				</option>

			</select>

		</div>


		<button
			class="btn btn-primary">

			Update User

		</button>


	</form>
<?php else: ?>
	<h2>
		Add User
	</h2>


	<form method="POST">
		<?= csrf_field() ?>


		<div class="mb-3">

			<label>
				Employee ID
			</label>

			<input
				name="employee_id"
				class="form-control"
				value="<?= e($formUser["employee_id"] ?? "") ?>"
				required>

		</div>


		<div class="mb-3">

			<label>
				Name
			</label>

			<input
				name="name"
				class="form-control"
				value="<?= e($formUser["name"] ?? "") ?>"
				required>

		</div>


		<div class="mb-3">

			<label>
				Email
			</label>

			<input
				type="email"
				name="email"
				class="form-control"
				value="<?= e($formUser["email"] ?? "") ?>"
				required>

		</div>


		<div class="mb-3">

			<label>
				Password
			</label>

			<input
				type="password"
				name="password"
				class="form-control"
				required>

		</div>


		<div class="mb-3">

			<label>
				Department
			</label>

			<select
				name="department_id"
				id="department_id"
				class="form-select">

				<option value="" <?= empty($formUser["department_id"]) ? "selected" : "" ?>>None / Organization-wide</option>

				<?php foreach ($departments as $d): ?>

					<option value="<?= $d["id"] ?>" <?= (string)$d["id"] === (string)($formUser["department_id"] ?? '') ? "selected" : "" ?>>

						<?= e($d["department_name"]) ?>

					</option>

				<?php endforeach; ?>

			</select>

		</div>


		<div class="mb-3">
			<label>Team</label>
			<select name="team_id" id="team_id" class="form-select">
				<option value="" <?= empty($formUser["team_id"]) ? "selected" : "" ?>>No Team</option>
				<?php foreach ($teams as $t): ?>
					<option value="<?= (int)$t["id"] ?>" data-department="<?= (int)$t["department_id"] ?>" <?= (int)($formUser["team_id"] ?? 0) === (int)$t["id"] ? "selected" : "" ?>><?= e($t["department_name"] . " - " . $t["team_name"]) ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="mb-3">

			<label>
				Role
			</label>

			<select
				name="role_id"
				class="form-select"
				required>

				<?php foreach ($roles as $r): ?>

					<option value="<?= $r["id"] ?>" <?= (string)$r["id"] === (string)($formUser["role_id"] ?? '') ? "selected" : "" ?>>

						<?= e($r["role_name"]) ?>

					</option>

				<?php endforeach; ?>

			</select>

		</div>


		<button
			class="btn btn-primary">

			Create User

		</button>


	</form>
<?php endif; ?>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		var d = document.getElementById('department_id'),
			t = document.getElementById('team_id');
		if (!d || !t) return;

		function f() {
			var v = d.value;
			t.disabled = !v;
			Array.from(t.options).forEach(function(o, i) {
				if (i === 0) return;
				o.hidden = !v || o.dataset.department !== v;
			});
			if (!v || (t.selectedOptions[0] && t.selectedOptions[0].hidden)) t.value = '';
		}
		d.addEventListener('change', f);
		f();
	});
</script>
