<?php $id = (int)($project["id"] ?? 0); ?>
<div class="d-flex justify-content-between align-items-center mb-4">

	<div>

		<h2 class="mb-1">
			Project History
		</h2>

		<p class="text-muted mb-0">

			<?= e($project["project_id"]) ?>

			-

			<?= e($project["project_title"]) ?>

		</p>

	</div>


	<a
		href="<?= site_url('projects/view/' . $project["id"]) ?>"
		class="btn btn-outline-secondary">

		Back to Concept Paper

	</a>

</div>


<div class="card">

	<div class="card-header">

		<h5 class="mb-0">
			Activity History
		</h5>

	</div>


	<div class="card-body">

		<?php if ($history): ?>

			<?php foreach ($history as $item): ?>

				<div class="border-bottom py-3">

					<div class="row">

						<div class="col-md-3 mb-2">

							<small class="text-muted">
								Date
							</small>

							<div>
								<?= e(
									$item["action_date"]
								) ?>
							</div>

						</div>


						<div class="col-md-3 mb-2">

							<small class="text-muted">
								User
							</small>

							<div>
								<?= e(
									$item["user_name"]
								) ?>
							</div>

						</div>


						<div class="col-md-3 mb-2">

							<small class="text-muted">
								Action
							</small>

							<div>

								<span class="badge bg-primary">

									<?= e(
										status_label(
											$item["action"]
										)
									) ?>

								</span>

							</div>

						</div>


						<div class="col-md-3 mb-2">

							<small class="text-muted">
								Status Change
							</small>

							<div>

								<?php if (
									!empty($item["old_status"])
									||
									!empty($item["new_status"])
								): ?>

									<?= e(
										status_label(
											$item["old_status"]
												?? ""
										)
									) ?>

									→

									<?= e(
										status_label(
											$item["new_status"]
												?? ""
										)
									) ?>

								<?php else: ?>

									<span class="text-muted">
										—
									</span>

								<?php endif; ?>

							</div>

						</div>

					</div>



				</div>

			<?php endforeach; ?>

		<?php else: ?>

			<p class="text-muted mb-0">
				No history is available for this project.
			</p>

		<?php endif; ?>

	</div>

</div>