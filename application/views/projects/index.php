<div class="d-flex justify-content-between mb-3">

	<h2>
		My Projects
	</h2>

	<a
		href="<?= site_url('projects/create') ?>"
		class="btn btn-primary">

		<i class="bi bi-plus fs-4 align-middle"></i>New Concept Paper

	</a>

</div>


<div class="card">

	<div class="card-body">

		<div class="table-responsive">


			<table class="table table-bordered">

				<thead>

					<tr>

						<th>Project ID</th>

						<th>Title</th>

						<th>Category</th>

						<th>Priority</th>

						<th>Status</th>

						<th>Action</th>

					</tr>

				</thead>


				<tbody>


					<?php foreach ($projects as $project): ?>

						<tr>

							<td>
								<?= e($project["project_id"]) ?>
							</td>


							<td>
								<?= e($project["project_title"]) ?>
							</td>


							<td>
								<?= e($project["category_name"]) ?>
							</td>


							<td>
								<?= e($project["priority"]) ?>
							</td>


							<td>
								<?= e(
									status_label(
										$project["status"]
									)
								) ?>
							</td>


							<td>

								<a
									href="<?= site_url('projects/view/' . $project["id"]) ?>"
									class="btn btn-sm btn-primary">

									View

								</a>


								<?php if (
									in_array(
										$project["status"],
										["DRAFT", "RETURNED_FOR_REVISION"]
									)
								): ?>

									<a
										href="<?= site_url('projects/edit/' . $project["id"]) ?>"
										class="btn btn-sm btn-warning">

										Edit

									</a>

								<?php endif; ?>


							</td>

						</tr>

					<?php endforeach; ?>


					<?php if (!$projects): ?>

						<tr>

							<td
								colspan="6"
								class="text-center">

								No projects found.

							</td>

						</tr>

					<?php endif; ?>


				</tbody>

			</table>

		</div>

	</div>

</div>