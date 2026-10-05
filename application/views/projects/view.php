<?php $id = (int)($project["id"] ?? 0); ?>
<!-- =========================================================
     PAGE HEADER
========================================================= -->

<div class="d-flex justify-content-between align-items-center mb-4">

	<div>

		<h2 class="mb-1">
			<?= e($project["project_title"]) ?>
		</h2>

		<p class="text-muted mb-0">
			Concept Paper:
			<?= e($project["project_id"]) ?>
		</p>

	</div>


	<div class="no-print d-flex gap-2">

		<?php if (
			$project["status"] === "APPROVED"
			&&
			(int)$project["requestor_id"] === (int)$user["id"]
		): ?>

			<a
				href="<?= site_url('projects/print/' . $id) ?>"
				class="btn btn-outline-secondary">

				Print / PDF

			</a>

		<?php endif; ?>


		<?php if (
			$project["requestor_id"] == $user["id"]
			&&
			in_array(
				$project["status"],
				["DRAFT", "RETURNED_FOR_REVISION"]
			)
		): ?>

			<a
				href="<?= site_url('projects/edit/' . $id) ?>"
				class="btn btn-warning">

				Edit

			</a>

		<?php endif; ?>

	</div>

</div>


<!-- =========================================================
     1. PROJECT INFORMATION
========================================================= -->

<div class="card mb-4">

	<div class="card-header">

		<h5 class="mb-0">
			1. Project Information
		</h5>

	</div>


	<div class="card-body">

		<div class="row">

			<div class="col-md-4 mb-3">

				<strong>
					Project ID
				</strong>

				<div>
					<?= e($project["project_id"]) ?>
				</div>

			</div>


			<div class="col-md-4 mb-3">

				<strong>
					Category
				</strong>

				<div>
					<?= e($project["category_name"]) ?>
				</div>

			</div>


			<div class="col-md-4 mb-3">

				<strong>
					Priority
				</strong>

				<div>
					<?= e(
						status_label(
							$project["priority"]
						)
					) ?>
				</div>

			</div>


			<div class="col-md-4 mb-3">

				<strong>
					Requestor
				</strong>

				<div>
					<?= e($project["requestor_name"]) ?>
				</div>

			</div>


			<div class="col-md-4 mb-3">

				<strong>
					Department
				</strong>

				<div>
					<?= e($project["department_name"]) ?>
				</div>

			</div>


			<div class="col-md-4 mb-3">

				<strong>
					Status
				</strong>

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

		</div>

	</div>

</div>


<!-- =========================================================
     2. STAKEHOLDERS
========================================================= -->

<div class="card mb-4">

	<div class="card-header">

		<h5 class="mb-0">
			2. Stakeholders
		</h5>

	</div>


	<div class="card-body">

		<?php if ($stakeholders): ?>

			<div class="table-responsive">

				<table class="table table-bordered table-hover align-middle">

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

						<?php foreach ($stakeholders as $s): ?>

							<tr>

								<td>
									<?= e(
										status_label(
											$s["stakeholder_type"]
										)
									) ?>
								</td>

								<td>
									<?= e(
										$s["stakeholder_name"]
									) ?>
								</td>

								<td>
									<?= e(
										$s["department_name"]
									) ?>
								</td>

								<td>
									<?= e(
										$s["email"]
									) ?>
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
     3. CURRENT & PROPOSED STATE
========================================================= -->

<div class="card mb-4">

	<div class="card-header">

		<h5 class="mb-0">
			3. Current & Proposed State
		</h5>

	</div>


	<div class="card-body">

		<div class="row">

			<div class="col-md-6 mb-3">

				<h6>
					Current State
				</h6>

				<p class="mb-0">

					<?= nl2br(
						e(
							$project["current_state"]
						)
					) ?>

				</p>

			</div>


			<div class="col-md-6 mb-3">

				<h6>
					Proposed State
				</h6>

				<p class="mb-0">

					<?= nl2br(
						e(
							$project["proposed_state"]
						)
					) ?>

				</p>

			</div>

		</div>

	</div>

</div>


<!-- =========================================================
     4. SCOPE
========================================================= -->

<div class="card mb-4">

	<div class="card-header">

		<h5 class="mb-0">
			4. Project Scope
		</h5>

	</div>


	<div class="card-body">

		<div class="row">

			<div class="col-md-6">

				<h6>
					Included in Scope
				</h6>

				<p>

					<?= nl2br(
						e(
							$project["scope_included"]
						)
					) ?>

				</p>

			</div>


			<div class="col-md-6">

				<h6>
					Excluded from Scope
				</h6>

				<p>

					<?= nl2br(
						e(
							$project["scope_excluded"]
						)
					) ?>

				</p>

			</div>

		</div>

	</div>

</div>


<?php if (trim((string)($project['scope_included'] ?? '')) !== '' && trim((string)($project['scope_excluded'] ?? '')) !== ''): ?>

	<!-- =========================================================
     5. EXPECTED END DATE
========================================================= -->

	<div class="card mb-4">
		<div class="card-header">
			<h5 class="mb-0">5. Expected End Date</h5>
		</div>
		<div class="card-body">
			<?php if (!empty($project["end_date"])): ?>
				<?= e(date("d M Y", strtotime($project["end_date"]))) ?>
			<?php else: ?>
				<span class="text-muted">Not specified</span>
			<?php endif; ?>
		</div>
	</div>

<?php endif; ?>

<!-- =========================================================
     EXPECTED TIMELINE BY TECHNICAL REVIEWER
========================================================= -->
<div class="card mb-4">
	<div class="card-header">
		<h5 class="mb-0">Expected Timeline by Technical Reviewer</h5>
	</div>
	<div class="card-body">
		<?php if (!empty($technicalTimeline['expected_start_date']) && !empty($technicalTimeline['expected_end_date'])): ?>
			<div class="row g-3">
				<div class="col-md-6"><strong>Start Date:</strong> <?= e(date('d M Y', strtotime($technicalTimeline['expected_start_date']))) ?></div>
				<div class="col-md-6"><strong>End Date:</strong> <?= e(date('d M Y', strtotime($technicalTimeline['expected_end_date']))) ?></div>
			</div>
			<?php if (!empty($technicalTimeline['reviewer_name'])): ?>
				<small class="text-muted">Assigned by <?= e($technicalTimeline['reviewer_name']) ?></small>
			<?php endif; ?>
		<?php else: ?>
			<span class="text-muted">Pending technical review</span>
		<?php endif; ?>
	</div>
</div>

<!-- =========================================================
     6. APPROVAL WORKFLOW
========================================================= -->

<div class="card mb-4">

	<div class="card-header">

		<h5 class="mb-0">
			6. Approval Workflow
		</h5>

	</div>


	<div class="card-body">

		<?php if ($approvals): ?>

			<div class="table-responsive">

				<table class="table table-bordered table-hover align-middle">

					<thead>

						<tr>

							<th>
								Step
							</th>

							<th>
								Approval
							</th>

							<th>
								Approver
							</th>

							<th>
								Status
							</th>

						</tr>

					</thead>


					<tbody>

						<?php foreach ($approvals as $approval): ?>

							<tr>

								<td>
									<?= e(
										$approval["step_order"]
									) ?>
								</td>

								<td>
									<?= e(
										$approval["step_name"]
									) ?>
								</td>

								<td>
									<?= e(
										$approval["approver_name"]
											?? "Pending"
									) ?>
								</td>

								<td>

									<?php

									$badgeClass = "bg-secondary";

									if (
										$approval["status"] === "APPROVED"
									) {
										$badgeClass = "bg-success";
									} elseif (
										$approval["status"] === "REJECTED"
									) {
										$badgeClass = "bg-danger";
									} elseif (
										$approval["status"] === "PENDING"
									) {
										$badgeClass = "bg-warning text-dark";
									} elseif (
										$approval["status"] === "WAITING"
									) {
										$badgeClass = "bg-secondary";
									}

									?>

									<span
										class="badge <?= $badgeClass ?>">

										<?= e(
											status_label(
												$approval["status"]
											)
										) ?>

									</span>

								</td>

							</tr>

						<?php endforeach; ?>

					</tbody>

				</table>

			</div>

		<?php else: ?>

			<p class="text-muted mb-0">
				No approval workflow has been created yet.
			</p>

		<?php endif; ?>

	</div>

</div>


<!-- APPROVER REMARKS -->
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
					<div class="border-bottom pb-3 mb-3">
						<strong><?= e($remark['step_name']) ?></strong>
						<div class="small text-muted mb-2">
							<?= e($remark['approver_name'] ?? 'Approver') ?>
							<?= !empty($remark['role_name']) ? ' - ' . e($remark['role_name']) : '' ?>
							<?= !empty($remark['action_date']) ? ' | ' . e($remark['action_date']) : '' ?>
						</div>

						<?php if (!empty($remark['requires_technical_review'])): ?>
							<div class="mb-2"><strong>Technical Remarks</strong></div>
							<div class="row g-2 mb-2">
								<div class="col-md-6"><strong>Feasibility:</strong> <?= e(str_replace('_', ' ', $remark['feasibility'] ?? '—')) ?></div>
								<div class="col-md-6"><strong>Estimated Effort:</strong> <?= e($remark['estimated_effort'] ?: '—') ?></div>
								<div class="col-md-6"><strong>Expected Start Date:</strong> <?= !empty($remark['expected_start_date']) ? e(date('d M Y', strtotime($remark['expected_start_date']))) : '—' ?></div>
								<div class="col-md-6"><strong>Expected End Date:</strong> <?= !empty($remark['expected_end_date']) ? e(date('d M Y', strtotime($remark['expected_end_date']))) : '—' ?></div>
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

<!-- =========================================================
     7. PROJECT HISTORY
========================================================= -->

<div class="card mb-4">

	<div class="card-header">

		<h5 class="mb-0">
			7. Project History
		</h5>

	</div>


	<div class="card-body">

		<?php if ($history): ?>

			<?php foreach ($history as $item): ?>

				<div class="border-bottom py-3">

					<div class="d-flex justify-content-between">

						<strong>
							<?= e(
								status_label(
									$item["action"]
								)
							) ?>
						</strong>

						<small class="text-muted">

							<?= e(
								$item["action_date"]
							) ?>

						</small>

					</div>


					<div class="mt-1">

						<?= e(
							$item["user_name"]
						) ?>

					</div>


				</div>

			<?php endforeach; ?>

		<?php else: ?>

			<p class="text-muted mb-0">
				No project history available.
			</p>

		<?php endif; ?>

	</div>

</div>