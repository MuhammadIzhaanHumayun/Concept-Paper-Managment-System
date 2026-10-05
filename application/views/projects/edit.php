<?php $id = (int)($project["id"] ?? 0); ?>
<div class="d-flex justify-content-between align-items-center mb-4">

	<div>

		<h2 class="mb-1">
			Edit Concept Paper
		</h2>

		<p class="text-muted mb-0">
			<?= e($project["project_id"]) ?>
			-
			<?= e($project["project_title"]) ?>
		</p>

	</div>

</div>


<form method="POST" action="<?= site_url('projects/update/' . $project["id"]) ?>">
	<?= csrf_field() ?>

	<input
		type="hidden"
		name="id"
		value="<?= $id ?>">


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

				<div class="col-md-8 mb-3">

					<label class="form-label">
						Project Title
					</label>

					<input
						name="project_title"
						class="form-control"
						value="<?= e($project["project_title"]) ?>"
						required>

				</div>


				<div class="col-md-4 mb-3">

					<label class="form-label">
						Category
					</label>

					<input
						type="text"
						class="form-control"
						value="<?= e($project['category_name']) ?>"
						readonly>

					<input
						type="hidden"
						name="category_id"
						value="<?= (int)$project['category_id'] ?>">

				</div>


				<div class="col-md-4">

					<label class="form-label">
						Priority
					</label>

					<select
						name="priority"
						class="form-select">

						<?php foreach (
							["HIGH", "MEDIUM", "LOW"]
							as $priority
						): ?>

							<option
								value="<?= $priority ?>"
								<?= $priority == $project["priority"]
									? "selected"
									: "" ?>>

								<?= e(
									status_label($priority)
								) ?>

							</option>

						<?php endforeach; ?>

					</select>

				</div>

			</div>

		</div>

	</div>


	<!-- =========================================================
     2. STAKEHOLDERS
========================================================= -->

	<div class="card mb-4">

		<div class="card-header">

			<div class="d-flex justify-content-between align-items-center">

				<h5 class="mb-0">
					2. Stakeholders
				</h5>

				<button
					type="button"
					class="btn btn-sm btn-primary"
					id="addStakeholder">

					+ Add Stakeholder

				</button>

			</div>

		</div>


		<div class="card-body">

			<p class="text-muted small mb-4">
				Add or update the people involved in this concept paper.
			</p>


			<div id="stakeholderContainer">

				<?php if (!empty($stakeholders)): ?>

					<?php foreach ($stakeholders as $stakeholder): ?>

						<div class="stakeholder-row border rounded p-3 mb-3">

							<div class="row align-items-end">


								<!-- Stakeholder Type -->

								<div class="col-lg-3 col-md-6 mb-3">

									<label class="form-label">
										Stakeholder Type
									</label>

									<?php
									$stakeholderType =
										isset($stakeholder["type"]) ? $stakeholder["type"] : "";
									?>

									<select
										name="stakeholder_type[]"
										class="form-select"
										required>

										<option value="">
											Select Type
										</option>

										<option
											value="SPONSOR"
											<?php
											if ($stakeholderType == "SPONSOR") {
												echo "selected";
											}
											?>>
											Sponsor
										</option>

										<option
											value="USER"
											<?php
											if ($stakeholderType == "USER") {
												echo "selected";
											}
											?>>
											User
										</option>

										<option
											value="TECHNICAL_TEAM"
											<?php
											if ($stakeholderType == "TECHNICAL_TEAM") {
												echo "selected";
											}
											?>>
											Technical Team
										</option>

										<option
											value="APPROVER"
											<?php
											if ($stakeholderType == "APPROVER") {
												echo "selected";
											}
											?>>
											Approver
										</option>

									</select>

								</div>


								<!-- Name -->

								<div class="col-lg-3 col-md-6 mb-3">

									<label class="form-label">
										Name
									</label>

									<input
										type="text"
										name="stakeholder_name[]"
										class="form-control"
										value="<?= e(isset($stakeholder["name"]) ? $stakeholder["name"] : "") ?>"
										required>

								</div>


								<!-- Department -->

								<div class="col-lg-2 col-md-6 mb-3">

									<label class="form-label">
										Department
									</label>

									<input
										type="text"
										name="stakeholder_department[]"
										class="form-control"
										value="<?= e(isset($stakeholder["department"]) ? $stakeholder["department"] : "") ?>">

								</div>


								<!-- Email -->

								<div class="col-lg-3 col-md-6 mb-3">

									<label class="form-label">
										Email
									</label>

									<input
										type="email"
										name="stakeholder_email[]"
										class="form-control"
										value="<?= e(isset($stakeholder["email"]) ? $stakeholder["email"] : "") ?>">

								</div>


								<!-- Remove -->

								<div class="col-lg-1 col-md-12 mb-3">

									<button
										type="button"
										class="btn btn-outline-danger w-100 remove-stakeholder"
										title="Remove">

										×

									</button>

								</div>

							</div>

						</div>

					<?php endforeach; ?>


				<?php else: ?>


					<!-- Default stakeholder row -->

					<div class="stakeholder-row border rounded p-3 mb-3">

						<div class="row align-items-end">


							<div class="col-lg-3 col-md-6 mb-3">

								<label class="form-label">
									Stakeholder Type
								</label>

								<select
									name="stakeholder_type[]"
									class="form-select"
									required>

									<option value="">
										Select Type
									</option>

									<option value="SPONSOR">
										Sponsor
									</option>

									<option value="USER">
										Users
									</option>

									<option value="TECHNICAL_TEAM">
										Technical Team
									</option>

									<option value="APPROVER">
										Approver
									</option>

								</select>

							</div>


							<div class="col-lg-3 col-md-6 mb-3">

								<label class="form-label">
									Name
								</label>

								<input
									type="text"
									name="stakeholder_name[]"
									class="form-control"
									required>

							</div>


							<div class="col-lg-2 col-md-6 mb-3">

								<label class="form-label">
									Department
								</label>

								<input
									type="text"
									name="stakeholder_department[]"
									class="form-control">

							</div>


							<div class="col-lg-3 col-md-6 mb-3">

								<label class="form-label">
									Email
								</label>

								<input
									type="email"
									name="stakeholder_email[]"
									class="form-control">

							</div>


							<div class="col-lg-1 col-md-12 mb-3">

								<button
									type="button"
									class="btn btn-outline-danger w-100 remove-stakeholder">

									×

								</button>

							</div>


						</div>

					</div>


				<?php endif; ?>

			</div>


			<button
				type="button"
				class="btn btn-outline-primary"
				id="addStakeholderBottom">

				+ Add Another Stakeholder

			</button>

		</div>

	</div>


	<!-- =========================================================
     3. REQUESTOR
========================================================= -->

	<div class="card mb-4">

		<div class="card-header">

			<h5 class="mb-0">
				3. Requestor
			</h5>

		</div>


		<div class="card-body">

			<div class="row">

				<div class="col-md-6 mb-3">

					<label class="form-label">
						Requestor
					</label>

					<input
						class="form-control"
						value="<?= e($project["requestor_name"]) ?>"
						readonly>

				</div>


				<div class="col-md-6 mb-3">

					<label class="form-label">
						Department
					</label>

					<input
						class="form-control"
						value="<?= e($project["department_name"]) ?>"
						readonly>

				</div>

			</div>

		</div>

	</div>


	<!-- =========================================================
     4. CURRENT & PROPOSED STATE
========================================================= -->

	<div class="card mb-4">

		<div class="card-header">

			<h5 class="mb-0">
				4. Current & Proposed State
			</h5>

		</div>


		<div class="card-body">

			<div class="mb-3">

				<label class="form-label">
					Current State
				</label>

				<textarea
					name="current_state"
					class="form-control"
					rows="6"
					required><?= e($project["current_state"]) ?></textarea>

			</div>


			<div>

				<label class="form-label">
					Proposed State
				</label>

				<textarea
					name="proposed_state"
					class="form-control"
					rows="6"
					required><?= e($project["proposed_state"]) ?></textarea>

			</div>

		</div>

	</div>


	<!-- =========================================================
     5. SCOPE
========================================================= -->

	<div class="card mb-4">

		<div class="card-header">

			<h5 class="mb-0">
				5. Project Scope
			</h5>

		</div>


		<div class="card-body">

			<div class="row">

				<div class="col-md-6 mb-3">

					<label class="form-label">
						Included in Scope
					</label>

					<textarea
						name="scope_included"
						id="scope_included"
						class="form-control"
						rows="6"
						required><?= e($project["scope_included"]) ?></textarea>

				</div>


				<div class="col-md-6 mb-3">

					<label class="form-label">
						Excluded from Scope
					</label>

					<textarea
						name="scope_excluded"
						id="scope_excluded"
						class="form-control"
						rows="6"
						required><?= e($project["scope_excluded"]) ?></textarea>

				</div>

			</div>

		</div>

	</div>


	<!-- =========================================================
     6. EXPECTED END DATE
========================================================= -->

	<div class="card mb-4" id="timelineSection" hidden>
		<div class="card-header">
			<h5 class="mb-0">6. Expected End Date</h5>
		</div>
		<div class="card-body">
			<label class="form-label">Expected End Date</label>
			<input type="date" name="end_date" class="form-control" value="<?= e($project["end_date"]) ?>">
		</div>
	</div>


	<!-- =========================================================
     ACTIONS
========================================================= -->

	<div class="card mb-4">

		<div class="card-body">

			<div class="d-flex justify-content-end gap-2">

				<button
					type="submit"
					name="action"
					value="DRAFT"
					class="btn btn-secondary">

					Save Changes

				</button>


				<button
					type="submit"
					name="action"
					value="SUBMIT"
					class="btn btn-primary">

					Submit Concept Paper

				</button>

			</div>

		</div>

	</div>


</form>


<script>
	(function() {
		const scopeIncluded = document.getElementById("scope_included");
		const scopeExcluded = document.getElementById("scope_excluded");
		const timelineSection = document.getElementById("timelineSection");

		if (!scopeIncluded || !scopeExcluded || !timelineSection) {
			return;
		}

		function updateTimelineVisibility() {
			const scopeIsFilled =
				scopeIncluded.value.trim() !== "" &&
				scopeExcluded.value.trim() !== "";

			timelineSection.hidden = !scopeIsFilled;
		}

		scopeIncluded.addEventListener("input", updateTimelineVisibility);
		scopeExcluded.addEventListener("input", updateTimelineVisibility);
		updateTimelineVisibility();
	})();
</script>