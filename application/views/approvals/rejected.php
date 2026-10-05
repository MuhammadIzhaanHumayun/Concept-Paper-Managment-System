<h2>Rejected / Returned Approvals</h2>
<div class="table-responsive">
	<table class="table table-bordered bg-white">
		<thead>
			<tr>
				<th>Project</th>
				<th>Title</th>
				<th>Stage</th>
				<th>Comments</th>
				<th>Action Date</th>
			</tr>
		</thead>
		<tbody><?php if ($approvals): foreach ($approvals as $a): ?><tr>
						<td><a href="<?= site_url('projects/view/' . $a['project_id']) ?>"><?= html_escape($a['project_id']) ?></a></td>
						<td><?= html_escape($a['project_title']) ?></td>
						<td><?= html_escape($a['step_name']) ?></td>
						<td><?= html_escape($a['comments'] ?? '') ?></td>
						<td><?= html_escape($a['action_date'] ?? '') ?></td>
					</tr><?php endforeach;
					else: ?><tr>
					<td colspan="5" class="text-muted text-center">No rejected or returned approvals.</td>
				</tr><?php endif; ?></tbody>
	</table>
</div>