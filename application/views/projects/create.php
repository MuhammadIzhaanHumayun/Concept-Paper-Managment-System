<div class="d-flex justify-content-between align-items-center mb-4">

	<div>
		<h2 class="mb-1">
			Create Concept Paper
		</h2>

		<p class="text-muted mb-0">
			Submit a new project concept paper for review and approval.
		</p>
	</div>

</div>


<form method="POST" action="<?= site_url('projects/store') ?>">
	<?= csrf_field() ?>


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
						Project Title <span class="text-danger">*</span>
					</label>

					<input
						type="text"
						name="project_title"
						class="form-control"
						placeholder="Enter project title"
						required>

				</div>


				<div class="col-md-4 mb-3">

					<label class="form-label">
						Project Category <span class="text-danger">*</span>
					</label>

					<select
						name="category_id"
						id="category_id"
						class="form-select"
						required>

						<option value="">
							Select Category
						</option>

						<?php foreach ($categories as $category): ?>

							<option
								value="<?= $category["id"] ?>"
								data-criteria="<?= e($category["criteria"]) ?>">

								<?= e($category["category_name"]) ?>

							</option>

						<?php endforeach; ?>

					</select>

					<div
						id="categoryCriteria"
						class="form-text"></div>

				</div>


				<div class="col-md-4">

					<label class="form-label">
						Priority
					</label>

					<select
						name="priority"
						class="form-select">

						<option value="HIGH">
							High
						</option>

						<option value="MEDIUM" selected>
							Medium
						</option>

						<option value="LOW">
							Low
						</option>

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

			<h5 class="mb-0">
				2. Stakeholders
			</h5>

		</div>


		<div class="card-body">

			<div id="stakeholders">

				<!-- =================================================
                     FIRST STAKEHOLDER ROW
                     No Remove button here.
                ================================================== -->

				<div class="stakeholder-row row g-2 mb-3 align-items-end">

					<div class="col-md-3">

						<label class="form-label">
							Type <span class="text-danger">*</span>
						</label>

						<select
							name="stakeholder_type[]"
							class="form-select">

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


					<div class="col-md-3">

						<label class="form-label">
							Name <span class="text-danger">*</span>
						</label>

						<input
							name="stakeholder_name[]"
							class="form-control"
							placeholder="Stakeholder name"
							required>

					</div>


					<div class="col-md-2">

						<label class="form-label">
							Department <span class="text-danger">*</span>
						</label>

						<input
							name="stakeholder_department[]"
							class="form-control"
							placeholder="Department">

					</div>


					<div class="col-md-3">

						<label class="form-label">
							Email <span class="text-danger">*</span>
						</label>

						<input
							type="email"
							name="stakeholder_email[]"
							class="form-control"
							placeholder="Email address">

					</div>

				</div>

			</div>


			<button
				type="button"
				class="btn btn-outline-secondary btn-sm"
				onclick="addStakeholder()">

				+ Add Stakeholder

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
						Requestor Name
					</label>

					<input
						type="text"
						class="form-control"
						value="<?= e($user["name"]) ?>"
						readonly>

				</div>


				<div class="col-md-6 mb-3">

					<label class="form-label">
						Department
					</label>

					<input
						type="text"
						class="form-control"
						value="<?= e($user["department_name"]) ?>"
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
					Current State <span class="text-danger">*</span>
				</label>

				<textarea
					name="current_state"
					class="form-control"
					rows="5"
					placeholder="Describe the current situation, process, system or problem..."
					required></textarea>

			</div>


			<div>

				<label class="form-label">
					Proposed State <span class="text-danger">*</span>
				</label>

				<textarea
					name="proposed_state"
					class="form-control"
					rows="5"
					placeholder="Describe the proposed solution or future state..."
					required></textarea>

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
						<span class="text-danger">*</span>
					</label>

					<textarea
						name="scope_included"
						id="scope_included"
						class="form-control"
						rows="6"
						placeholder="Describe what is included in this project..."
						required></textarea>

				</div>


				<div class="col-md-6 mb-3">

					<label class="form-label">
						Excluded from Scope
						<span class="text-danger">*</span>
					</label>

					<textarea
						name="scope_excluded"
						id="scope_excluded"
						class="form-control"
						rows="6"
						placeholder="Describe what is not included..."
						required></textarea>

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
			<label class="form-label">Expected End Date <span class="text-danger">*</span></label>
			<input type="date" name="end_date" id="end_date" class="form-control">
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

					Save Draft

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
	/*
	 * =========================================================
	 * ADD STAKEHOLDER
	 * =========================================================
	 */

	function addStakeholder() {

		const container =
			document.getElementById("stakeholders");

		const original =
			document.querySelector(".stakeholder-row");


		if (!container || !original) {
			return;
		}


		/*
		 * Clone the first stakeholder row.
		 * The first row itself does not contain
		 * a Remove button.
		 */
		const clone =
			original.cloneNode(true);


		/*
		 * Clear all input values
		 */
		clone.querySelectorAll("input").forEach(
			function(input) {

				input.value = "";

			}
		);


		/*
		 * Reset select fields
		 */
		clone.querySelectorAll("select").forEach(
			function(select) {

				select.selectedIndex = 0;

			}
		);


		/*
		 * Create Remove button column
		 * ONLY for newly added rows.
		 */
		const removeColumn =
			document.createElement("div");

		removeColumn.className =
			"col-md-1";


		removeColumn.innerHTML = `
            <button
                type="button"
                class="btn btn-outline-danger w-100 remove-stakeholder"
                title="Remove stakeholder">

                ×

            </button>
        `;


		/*
		 * Add Remove button to the cloned row.
		 */
		clone.appendChild(removeColumn);


		/*
		 * Add cloned row to the form.
		 */
		container.appendChild(clone);

	}


	/*
	 * =========================================================
	 * REMOVE STAKEHOLDER
	 * =========================================================
	 *
	 * This works for dynamically created rows.
	 */

	document.addEventListener(
		"click",
		function(event) {

			const removeButton =
				event.target.closest(
					".remove-stakeholder"
				);


			if (!removeButton) {
				return;
			}


			const row =
				removeButton.closest(
					".stakeholder-row"
				);


			if (!row) {
				return;
			}


			/*
			 * Remove only the selected stakeholder row.
			 */
			row.remove();

		}
	);


	/*
	 * =========================================================
	 * CATEGORY CRITERIA
	 * =========================================================
	 */

	const categorySelect =
		document.getElementById("category_id");

	const categoryCriteria =
		document.getElementById("categoryCriteria");


	if (categorySelect) {

		categorySelect.addEventListener(
			"change",
			function() {

				const option =
					this.options[
						this.selectedIndex
					];


				categoryCriteria.textContent =
					option.dataset.criteria || "";

			}
		);

	}
</script>


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

	(function() {
		const el = document.getElementById('end_date');
		if (el) {
			el.min = new Date().toLocaleDateString('sv-SE');
		}
	})();
</script>