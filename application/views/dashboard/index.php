<div class="container-fluid">

	<div class="mb-4">

		<h2 class="fw-bold">
			Dashboard
		</h2>

		<p class="text-muted">
			Welcome, <?= e($user["name"]) ?>
		</p>

	</div>


	<?php if (trim($user["role_name"] ?? "") === "Requestor"): ?>


		<!-- ========================================================= -->
		<!-- REQUESTOR DASHBOARD                                      -->
		<!-- ========================================================= -->

		<div class="mb-4">

			<a
				href="<?= site_url('projects/create') ?>"
				class="btn btn-primary" style="--bs-btn-padding-y: 0.09rem">

				<i class="bi bi-plus fs-4 align-middle"></i> Create Concept Paper

			</a>

			<a
				href="<?= site_url('projects') ?>"
				class="btn btn-outline-secondary ms-2">

				My Projects

			</a>

		</div>


		<div class="row g-4">

			<!-- Draft -->

			<div class="col-md-6 col-lg">

				<div class="card shadow-sm h-100">

					<div class="card-body">

						<h6 class="text-muted card-heading">
							Drafts
						</h6>

						<h2 class="fw-bold card-count">
							<?= $counts["DRAFT"] ?? 0 ?>
						</h2>

						<a
							href="<?= site_url('projects') ?>?status=drafts"
							class="text-decoration-none card-link">

							View Drafts

						</a>

					</div>

				</div>

			</div>


			<!-- Pending -->

			<div class="col-md-6 col-lg">

				<div class="card shadow-sm h-100">

					<div class="card-body">

						<h6 class="text-muted card-heading">
							Pending Submissions
						</h6>

						<h2 class="fw-bold card-count">
							<?= $counts["SUBMITTED"] ?? 0 ?>
						</h2>

						<a
							href="<?= site_url('projects') ?>?status=pending"
							class="text-decoration-none card-link">

							View Pending

						</a>

					</div>

				</div>

			</div>


			<!-- Returned for Revision -->

			<div class="col-md-6 col-lg">

				<div class="card shadow-sm h-100">

					<div class="card-body">

						<h6 class="text-muted card-heading">
							Returned for Revision
						</h6>

						<h2 class="fw-bold card-count">
							<?= $counts["RETURNED_FOR_REVISION"] ?? 0 ?>
						</h2>

						<a
							href="<?= site_url('projects') ?>?status=returned"
							class="text-decoration-none card-link">

							View Returned

						</a>

					</div>

				</div>

			</div>


			<!-- Rejected -->

			<div class="col-md-6 col-lg">

				<div class="card shadow-sm h-100">

					<div class="card-body">

						<h6 class="text-muted card-heading">
							Rejected
						</h6>

						<h2 class="fw-bold card-count">
							<?= $counts["REJECTED"] ?? 0 ?>
						</h2>

						<a
							href="<?= site_url('projects') ?>?status=rejected"
							class="text-decoration-none card-link">

							View Rejected

						</a>

					</div>

				</div>

			</div>


			<!-- Approved -->

			<div class="col-md-6 col-lg">

				<div class="card shadow-sm h-100">

					<div class="card-body">

						<h6 class="text-muted card-heading">
							Approved
						</h6>

						<h2 class="fw-bold card-count">
							<?= $counts["APPROVED"] ?? 0 ?>
						</h2>

						<a
							href="<?= site_url('projects') ?>?status=approved"
							class="text-decoration-none card-link">

							View Approved

						</a>

					</div>

				</div>

			</div>

		</div>


	<?php elseif ($isApprover): ?>


		<!-- ========================================================= -->
		<!-- APPROVER DASHBOARD                                       -->
		<!-- ========================================================= -->

		<div class="mb-4">

			<a
				href="<?= site_url('approvals') ?>"
				class="btn btn-primary">

				Approval Inbox

			</a>

		</div>


		<div class="row g-4">


			<!-- Pending Approvals -->

			<div class="col-md-5">

				<div class="card shadow-sm h-100">

					<div class="card-body">

						<h6 class="text-muted card-heading">
							Pending Approvals
						</h6>

						<h2 class="fw-bold card-count">
							<?= $pendingApprovals ?>
						</h2>

						<a
							href="<?= site_url('approvals') ?>"
							class="text-decoration-none card-link">

							Review Pending Approvals

						</a>

					</div>

				</div>

			</div>


			<!-- Returned for Revision -->

			<div class="col-md-5">
				<div class="card shadow-sm h-100">
					<div class="card-body">
						<h6 class="text-muted card-heading">Returned for Revision</h6>
						<h2 class="fw-bold card-count"><?= $returnedApprovals ?? 0 ?></h2>
						<a href="<?= site_url('approvals') ?>" class="text-decoration-none card-link">
							Review Returned Projects
						</a>
					</div>
				</div>
			</div>


			<!-- Rejected -->

			<!-- <div class="col-md-6">

				<div class="card shadow-sm h-100">

					<div class="card-body">

						<h6 class="text-muted card-heading">
							Rejected
						</h6>

						<h2 class="fw-bold card-count">
							<?= $rejectedApprovals ?>
						</h2>

						<a
							href="#"
							onclick="return false;"
							aria-disabled="true"
							class="text-decoration-none card-link">

							View Rejected

						</a>

					</div>

				</div>

			</div> -->

		</div>


	<?php else: ?>

		<!-- ========================================================= -->
		<!-- DYNAMIC USER DASHBOARD                                   -->
		<!-- Any active database role can log in without PHP changes. -->
		<!-- ========================================================= -->

		<div class="row g-4">
			<div class="col-md-6">
				<div class="card shadow-sm h-100">
					<div class="card-body">
						<h6 class="text-muted card-heading">Current Role</h6>
						<h4 class="fw-bold mb-2"><?= e(trim($user["role_name"] ?? "") ?: "Unassigned") ?></h4>
						<?php if (!empty($user["department_name"])): ?>
							<div class="text-muted">Department: <?= e($user["department_name"]) ?></div>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="card shadow-sm h-100">
					<div class="card-body">
						<h6 class="text-muted card-heading">Approval Tasks</h6>
						<h2 class="fw-bold card-count"><?= (int)$pendingApprovals ?></h2>
						<div class="text-muted">
							No approval task is currently assigned to you.
						</div>
					</div>
				</div>
			</div>
		</div>


	<?php endif; ?>

</div>