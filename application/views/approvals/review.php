<?php $id = (int)($project["id"] ?? 0); ?>
<div class="d-flex justify-content-between align-items-center mb-4">

	<div>

		<h2>
			<?= e($approval["step_name"]) ?>
		</h2>

		<p class="text-muted mb-0">
			Review complete Concept Paper before taking action.
		</p>

	</div>

</div>


<!-- =========================================================
     PROJECT INFORMATION
========================================================= -->

<div class="card mb-4">

	<div class="card-header">

		<h5 class="mb-0">
			Project Information
		</h5>

	</div>


	<div class="card-body">

		<div class="row">

			<div class="col-md-6 mb-3">

				<strong>Project ID</strong>

				<div>
					<?= e($project["project_id"]) ?>
				</div>

			</div>


			<div class="col-md-6 mb-3">

				<strong>Project Title</strong>

				<div>
					<?= e($project["project_title"]) ?>
				</div>

			</div>


			<div class="col-md-6 mb-3">

				<strong>Category</strong>

				<div>
					<?= e($project["category_name"]) ?>
				</div>

			</div>


			<div class="col-md-6 mb-3">

				<strong>Priority</strong>

				<div>
					<?= e($project["priority"]) ?>
				</div>

			</div>


			<div class="col-md-6 mb-3">

				<strong>Requestor</strong>

				<div>
					<?= e($project["requestor_name"]) ?>
				</div>

				<?php if (!empty($project["requestor_email"])): ?>

					<small class="text-muted">
						<?= e($project["requestor_email"]) ?>
					</small>

				<?php endif; ?>

			</div>


			<div class="col-md-6 mb-3">

				<strong>Requesting Department</strong>

				<div>
					<?= e($project["department_name"]) ?>
				</div>

			</div>


			<div class="col-md-6 mb-3">

				<strong>Project Status</strong>

				<div>

					<span class="badge bg-primary">

						<?= e(
							status_label(
								$project["status"]
							)
						) ?>

					</span>

				</div>

			</div>


			<div class="col-md-6 mb-3">

				<strong>Current Approval Step</strong>

				<div>
					<?= e($approval["step_name"]) ?>
				</div>

			</div>

		</div>

	</div>

</div>


<!-- =========================================================
     CURRENT STATE
========================================================= -->

<div class="card mb-4">

	<div class="card-header">

		<h5 class="mb-0">
			Current State
		</h5>

	</div>


	<div class="card-body">

		<?php if (!empty($project["current_state"])): ?>

			<p class="mb-0">

				<?= nl2br(
					e(
						$project["current_state"]
					)
				) ?>

			</p>

		<?php else: ?>

			<span class="text-muted">
				No information provided.
			</span>

		<?php endif; ?>

	</div>

</div>


<!-- =========================================================
     PROPOSED STATE
========================================================= -->

<div class="card mb-4">

	<div class="card-header">

		<h5 class="mb-0">
			Proposed State
		</h5>

	</div>


	<div class="card-body">

		<?php if (!empty($project["proposed_state"])): ?>

			<p class="mb-0">

				<?= nl2br(
					e(
						$project["proposed_state"]
					)
				) ?>

			</p>

		<?php else: ?>

			<span class="text-muted">
				No information provided.
			</span>

		<?php endif; ?>

	</div>

</div>


<!-- =========================================================
     SCOPE
========================================================= -->

<div class="card mb-4">

	<div class="card-header">

		<h5 class="mb-0">
			Project Scope
		</h5>

	</div>


	<div class="card-body">

		<div class="row">

			<div class="col-md-6">

				<h6>
					Included Scope
				</h6>

				<?php if (!empty($project["scope_included"])): ?>

					<p>

						<?= nl2br(
							e(
								$project["scope_included"]
							)
						) ?>

					</p>

				<?php else: ?>

					<p class="text-muted">
						No information provided.
					</p>

				<?php endif; ?>

			</div>


			<div class="col-md-6">

				<h6>
					Excluded Scope
				</h6>

				<?php if (!empty($project["scope_excluded"])): ?>

					<p>

						<?= nl2br(
							e(
								$project["scope_excluded"]
							)
						) ?>

					</p>

				<?php else: ?>

					<p class="text-muted">
						No information provided.
					</p>

				<?php endif; ?>

			</div>

		</div>

	</div>

</div>


<!-- =========================================================
     EXPECTED END DATE
========================================================= -->

<div class="card mb-4">
	<div class="card-header">
		<h5 class="mb-0">Expected End Date</h5>
	</div>
	<div class="card-body">
		<?php if (!empty($project["end_date"])): ?>
			<?= e(date("d M Y", strtotime($project["end_date"]))) ?>
		<?php else: ?>
			<span class="text-muted">Not specified</span>
		<?php endif; ?>
	</div>
</div>

<?php if (!empty($technicalTimeline['expected_start_date']) && !empty($technicalTimeline['expected_end_date'])): ?>
	<div class="card mb-4">
		<div class="card-header">
			<h5 class="mb-0">Expected Timeline by Technical Reviewer</h5>
		</div>
		<div class="card-body">
			<div class="row g-3">
				<div class="col-md-6"><strong>Start Date:</strong> <?= e(date('d M Y', strtotime($technicalTimeline['expected_start_date']))) ?></div>
				<div class="col-md-6"><strong>End Date:</strong> <?= e(date('d M Y', strtotime($technicalTimeline['expected_end_date']))) ?></div>
			</div>
			<?php if (!empty($technicalTimeline['reviewer_name'])): ?>
				<small class="text-muted">Assigned by <?= e($technicalTimeline['reviewer_name']) ?></small>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>


<!-- =========================================================
     STAKEHOLDERS
========================================================= -->

<div class="card mb-4">

	<div class="card-header">

		<h5 class="mb-0">
			Stakeholders
		</h5>

	</div>


	<div class="card-body">

		<?php if ($stakeholders): ?>

			<div class="table-responsive">

				<table class="table table-bordered table-hover">

					<thead>

						<tr>

							<th>
								Type
							</th>

							<th>
								Name
							</th>

							<th>
								Department
							</th>

							<th>
								Email
							</th>

						</tr>

					</thead>


					<tbody>

						<?php foreach (
							$stakeholders as $stakeholder
						): ?>

							<tr>

								<td>
									<?= e(
										status_label(
											$stakeholder["stakeholder_type"]
										)
									) ?>
								</td>


								<td>
									<?= e(
										$stakeholder["stakeholder_name"]
									) ?>
								</td>


								<td>

									<?= e(
										$stakeholder["department_name"]
									) ?>

								</td>


								<td>

									<?php if (
										!empty($stakeholder["email"])
									): ?>

										<?= e(
											$stakeholder["email"]
										) ?>

									<?php else: ?>

										<span class="text-muted">
											—
										</span>

									<?php endif; ?>

								</td>

							</tr>

						<?php endforeach; ?>

					</tbody>

				</table>

			</div>

		<?php else: ?>

			<p class="text-muted mb-0">
				No stakeholders have been added.
			</p>

		<?php endif; ?>

	</div>

</div>


<!-- =========================================================
     TECHNICAL REVIEW
========================================================= -->

<?php if (!empty($approval["requires_technical_review"])): ?>


	<div class="card mb-4">

		<div class="card-header">

			<h5 class="mb-0">
				Technical Review
			</h5>

		</div>


		<div class="card-body">


			<div class="mb-3">

				<label class="form-label">
					Technical Feasibility
				</label>

				<select
					name="feasibility"
					form="approvalForm"
					class="form-select"
					required>

					<option value="FEASIBLE">
						Feasible
					</option>

					<option value="FEASIBLE_WITH_CONDITIONS">
						Feasible With Conditions
					</option>

					<option value="NOT_FEASIBLE">
						Not Feasible
					</option>

				</select>

			</div>


			<div class="mb-3">

				<label class="form-label">
					Estimated Effort
				</label>

				<input
					type="text"
					name="estimated_effort"
					form="approvalForm"
					class="form-control">

			</div>


			<div class="mb-3">
				<label class="form-label">Expected Timeline</label>
				<div class="row g-3">
					<div class="col-md-6">
						<label class="form-label">Expected Start Date <span class="text-danger">*</span></label>
						<input type="date" id="startDate" name="expected_start_date" form="approvalForm" class="form-control" required>
					</div>
					<div class="col-md-6">
						<label class="form-label">Expected End Date <span class="text-danger">*</span></label>
						<input type="date" id="endDate" name="expected_end_date" form="approvalForm" class="form-control" required>
					</div>
				</div>
			</div>


			<div class="mb-3">

				<label class="form-label">
					Technical Risks
				</label>

				<textarea
					name="technical_risks"
					form="approvalForm"
					class="form-control"
					rows="4"></textarea>

			</div>


			<div class="mb-0">

				<label class="form-label">
					Dependencies
				</label>

				<textarea
					name="dependencies"
					form="approvalForm"
					class="form-control"
					rows="4"></textarea>

			</div>


		</div>

	</div>


<?php endif; ?>


<!-- =========================================================
     APPROVAL ACTION
========================================================= -->

<div class="card mb-4">

	<div class="card-header">

		<h5 class="mb-0">
			Approval Decision
		</h5>

	</div>


	<div class="card-body">


		<form
			method="POST"
			action="<?= site_url('approvals/action') ?>"

			id="approvalForm">
			<?= csrf_field() ?>


			<input
				type="hidden"
				name="id"
				value="<?= $id ?>">


			<input
				type="hidden"
				name="approval_id"
				value="<?= (int)$approval['id'] ?>">


			<div class="mb-3">

				<label class="form-label">
					Comments
				</label>

				<textarea
					name="comments"
					class="form-control"
					rows="5"
					placeholder="Enter your comments or decision remarks..."></textarea>

			</div>


			<div class="d-flex gap-2 flex-wrap">


				<button
					type="submit"
					name="action"
					value="APPROVE"
					class="btn btn-success">

					Approve

				</button>


				<button
					type="submit"
					name="action"
					value="REJECT"
					class="btn btn-danger">

					Reject

				</button>


				<button
					type="submit"
					name="action"
					value="RETURN"
					class="btn btn-warning">

					Return for Revision

				</button>


			</div>


		</form>


	</div>

</div><!-- APPROVER REMARKS -->
<div class="card mb-4">
	<div class="card-header d-flex justify-content-between align-items-center">
		<h5 class="mb-0">Approver Remarks</h5>
		<button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#approverRemarks">Show Remarks</button>
	</div>
	<div class="collapse" id="approverRemarks">
		<div class="card-body">
			<?php if (empty($remarks)): ?>
				<span class="text-muted">No approver remarks are available yet.</span>
			<?php else: ?>
				<?php foreach ($remarks as $remark): ?>
					<div class="border-bottom border-3 pb-3 mb-3">
						<strong class="fs-5"><?= e($remark['step_name']) ?></strong>
						<div class="small text-muted mb-3">
							<?= e($remark['approver_name'] ?? 'Approver') ?>
							<?= !empty($remark['role_name']) ? ' - ' . e($remark['role_name']) : '' ?>
							<?= !empty($remark['action_date']) ? ' | ' . e($remark['action_date']) : '' ?>
						</div>

						<?php if (!empty($remark['requires_technical_review'])): ?>
							<div class="mb-2 text-decoration-underline"><strong>Technical Remarks</strong></div>
							<div class="row g-2 mb-2">
								<div class="col-md-6"><strong>Feasibility:</strong> <?= e(str_replace('_', ' ', $remark['feasibility'] ?? '—')) ?></div>
								<div class="col-md-6"><strong>Estimated Effort:</strong> <?= e($remark['estimated_effort'] ?: '—') ?></div>
								<div class="col-12"><strong>Technical Risks:</strong><br><?= !empty($remark['technical_risks']) ? nl2br(e($remark['technical_risks'])) : '—' ?></div>
								<div class="col-12"><strong>Dependencies:</strong><br><?= !empty($remark['dependencies']) ? nl2br(e($remark['dependencies'])) : '—' ?></div>
							</div>
						<?php endif; ?>

						<?php if (!empty($remark['return_comments'])): ?>
							<div class="alert alert-warning py-2 mb-2">
								<strong>Return Reason:</strong><br>
								<?= nl2br(e($remark['return_comments'])) ?>
							</div>
						<?php endif; ?>

						<?php if (!empty($remark['comments']) && $remark['comments'] !== ($remark['return_comments'] ?? null)): ?>
							<div><strong>Comment:</strong><br><?= nl2br(e($remark['comments'])) ?></div>
						<?php elseif (!empty($remark['comments']) && empty($remark['return_comments'])): ?>
							<div><strong>Comment:</strong><br><?= nl2br(e($remark['comments'])) ?></div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</div>
<script>
	(function() {
		const sd = document.getElementById('startDate');
		if (sd) {
			sd.min = new Date().toLocaleDateString('sv-SE');
		}
	})();
	(function() {
		const ed = document.getElementById('endDate');
		if (ed) {
			ed.min = new Date().toLocaleDateString('sv-SE');
		}
	})();
</script>