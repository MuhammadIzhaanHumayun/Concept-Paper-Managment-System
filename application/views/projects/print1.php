<?php
/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function print_status_class($status)
{
	switch (strtoupper(trim($status))) {

		case "APPROVED":
			return "status-approved";

		case "REJECTED":
			return "status-rejected";

		case "RETURNED_FOR_REVISION":
			return "status-returned";

		case "SUBMITTED":
			return "status-submitted";

		case "DRAFT":
			return "status-draft";

		case "PENDING":
			return "status-pending";

		default:
			return "status-default";
	}
}


function print_status_label($status)
{
	return ucwords(
		strtolower(
			str_replace("_", " ", trim($status))
		)
	);
}


/*
|--------------------------------------------------------------------------
| Document information
|--------------------------------------------------------------------------
*/

$generatedDate = date("d M Y, h:i A");

$projectStatus = strtoupper(
	trim($project["status"] ?? "")
);

$statusClass = print_status_class($projectStatus);

$statusLabel = print_status_label($projectStatus);

$priority = strtoupper(
	trim($project["priority"] ?? "")
);

$priorityClass = "priority-default";

if ($priority === "HIGH") {
	$priorityClass = "priority-high";
} elseif ($priority === "MEDIUM") {
	$priorityClass = "priority-medium";
} elseif ($priority === "LOW") {
	$priorityClass = "priority-low";
}

$logo = base_url('assets/images/AtlasHonda.png');
$icon = base_url('assets/images/atlas.jpg');

?>

<!DOCTYPE html>

<html lang="en">

<head>

	<meta charset="UTF-8">

	<meta
		name="viewport"
		content="width=device-width, initial-scale=1.0">

	<link rel="icon" href="<?= $icon ?>">

	<title>
		Concept Paper - <?= e($project["project_id"]) ?>
	</title>


	<style>
		/*
        |--------------------------------------------------------------------------
        | PAGE
        |--------------------------------------------------------------------------
        */

		@page {

			size: A4;

			margin:
				18mm 16mm 18mm 16mm;

		}


		* {
			box-sizing: border-box;
		}


		html,
		body {

			margin: 0;
			padding: 0;

		}


		body {

			font-family:
				Arial,
				Helvetica,
				sans-serif;

			font-size: 10.5pt;

			line-height: 1.5;

			color: #222;

			background: #fff;

		}


		/*
        |--------------------------------------------------------------------------
        | PRINT BUTTON
        |--------------------------------------------------------------------------
        */

		.print-toolbar {

			width: 100%;

			padding: 15px;

			margin-bottom: 20px;

			text-align: right;

			background: #f4f5f7;

			border-bottom:
				1px solid #d9dce1;

		}


		.print-button {

			border: none;

			background: #1f2937;

			color: #fff;

			padding:
				10px 18px;

			border-radius: 5px;

			font-size: 13px;

			font-weight: 600;

			cursor: pointer;

		}


		.print-button:hover {

			background: #111827;

		}


		/*
        |--------------------------------------------------------------------------
        | DOCUMENT
        |--------------------------------------------------------------------------
        */

		.document {

			max-width: 900px;

			margin: 0 auto;

		}


		/*
        |--------------------------------------------------------------------------
        | CORPORATE HEADER
        |--------------------------------------------------------------------------
        */

		.company-header {

			display: table;

			width: 100%;

			padding-bottom: 14px;

			border-bottom:
				2px solid #1f2937;

		}


		.company-logo {

			display: table-cell;

			width: 24%;

			vertical-align: middle;

		}


		.company-logo img {

			max-width: 145px;

			max-height: 70px;

			object-fit: contain;

		}


		.company-title {

			display: table-cell;

			width: 51%;

			vertical-align: middle;

			text-align: center;

		}


		.company-title h1 {

			margin: 0;

			font-size: 20px;

			letter-spacing: 1.2px;

			color: #111827;

		}


		.company-title p {

			margin:
				4px 0 0;

			font-size: 10px;

			color: #6b7280;

			letter-spacing: 0.5px;

			text-transform: uppercase;

		}


		.document-meta {

			display: table-cell;

			width: 25%;

			vertical-align: middle;

			text-align: right;

			font-size: 9px;

			color: #4b5563;

		}


		.document-meta strong {

			display: block;

			color: #111827;

			font-size: 10px;

		}


		/*
        |--------------------------------------------------------------------------
        | DOCUMENT BANNER
        |--------------------------------------------------------------------------
        */

		.document-banner {

			margin-top: 18px;

			padding:
				12px 15px;

			background: #f3f4f6;

			border-left:
				5px solid #1f2937;

		}


		.document-banner-title {

			font-size: 16px;

			font-weight: 700;

			color: #111827;

			text-transform: uppercase;

			letter-spacing: 0.8px;

		}


		.document-banner-subtitle {

			margin-top: 3px;

			font-size: 9.5px;

			color: #6b7280;

		}


		/*
        |--------------------------------------------------------------------------
        | SECTION
        |--------------------------------------------------------------------------
        */

		.section {

			margin-top: 20px;

			page-break-inside: avoid;

		}


		.section-title {

			display: flex;

			align-items: center;

			margin-bottom: 10px;

			padding-bottom: 6px;

			border-bottom:
				1px solid #d1d5db;

			color: #111827;

			font-size: 12px;

			font-weight: 700;

			text-transform: uppercase;

			letter-spacing: 0.5px;

		}


		.section-number {

			display: inline-block;

			min-width: 25px;

			margin-right: 7px;

			color: #6b7280;

		}


		/*
        |--------------------------------------------------------------------------
        | INFORMATION TABLE
        |--------------------------------------------------------------------------
        */

		table {

			width: 100%;

			border-collapse: collapse;

		}


		.information-table {

			border:
				1px solid #d1d5db;

		}


		.information-table th {

			width: 23%;

			padding:
				8px 10px;

			text-align: left;

			vertical-align: top;

			background: #f8f9fa;

			border-bottom:
				1px solid #d1d5db;

			border-right:
				1px solid #d1d5db;

			color: #374151;

			font-size: 9.5px;

			font-weight: 700;

		}


		.information-table td {

			padding:
				8px 10px;

			border-bottom:
				1px solid #d1d5db;

			color: #111827;

			font-size: 10px;

		}


		.information-table tr:last-child th,
		.information-table tr:last-child td {

			border-bottom: none;

		}


		/*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

		.status-badge {

			display: inline-block;

			padding:
				4px 9px;

			border-radius: 4px;

			font-size: 8.5px;

			font-weight: 700;

			letter-spacing: 0.4px;

			text-transform: uppercase;

		}


		.status-approved {

			background: #dcfce7;

			color: #166534;

		}


		.status-rejected {

			background: #fee2e2;

			color: #991b1b;

		}


		.status-returned {

			background: #fef3c7;

			color: #92400e;

		}


		.status-submitted {

			background: #dbeafe;

			color: #1e40af;

		}


		.status-draft {

			background: #e5e7eb;

			color: #374151;

		}


		.status-pending {

			background: #fef3c7;

			color: #92400e;

		}


		.status-default {

			background: #e5e7eb;

			color: #374151;

		}


		/*
        |--------------------------------------------------------------------------
        | PRIORITY
        |--------------------------------------------------------------------------
        */

		.priority {

			font-weight: 700;

			font-size: 9px;

			letter-spacing: 0.5px;

		}


		.priority-high {
			color: #b91c1c;
		}


		.priority-medium {
			color: #b45309;
		}


		.priority-low {
			color: #166534;
		}


		.priority-default {
			color: #374151;
		}


		/*
        |--------------------------------------------------------------------------
        | CONTENT
        |--------------------------------------------------------------------------
        */

		.content-box {

			padding:
				11px 13px;

			border:
				1px solid #d1d5db;

			background: #fff;

			min-height: 45px;

			white-space: normal;

		}


		.content-box p {

			margin:
				0 0 8px;

		}


		.content-box p:last-child {

			margin-bottom: 0;

		}


		/*
        |--------------------------------------------------------------------------
        | TWO COLUMN LAYOUT
        |--------------------------------------------------------------------------
        */

		.two-column {

			display: table;

			width: 100%;

			border-spacing: 12px 0;

			margin-left: -12px;

			width: calc(100% + 24px);

		}


		.column {

			display: table-cell;

			width: 50%;

			vertical-align: top;

		}


		.mini-label {

			margin-bottom: 5px;

			color: #6b7280;

			font-size: 8.5px;

			font-weight: 700;

			text-transform: uppercase;

			letter-spacing: 0.5px;

		}


		/*
        |--------------------------------------------------------------------------
        | STAKEHOLDER TABLE
        |--------------------------------------------------------------------------
        */

		.data-table {

			border:
				1px solid #d1d5db;

		}


		.data-table th {

			padding:
				8px 7px;

			background: #374151;

			color: #fff;

			text-align: left;

			font-size: 8.5px;

			font-weight: 700;

			text-transform: uppercase;

			letter-spacing: 0.3px;

		}


		.data-table td {

			padding:
				7px;

			border-top:
				1px solid #d1d5db;

			vertical-align: top;

			font-size: 9px;

		}


		.data-table tbody tr:nth-child(even) td {

			background: #f9fafb;

		}


		.empty-row {

			padding: 12px !important;

			text-align: center;

			color: #6b7280;

			font-style: italic;

		}


		/*
        |--------------------------------------------------------------------------
        | APPROVAL WORKFLOW
        |--------------------------------------------------------------------------
        */

		.approval-table th:nth-child(1) {
			width: 7%;
		}


		.approval-table th:nth-child(2) {
			width: 30%;
		}


		.approval-table th:nth-child(3) {
			width: 23%;
		}


		.approval-table th:nth-child(4) {
			width: 14%;
		}


		.approval-table th:nth-child(5) {
			width: 26%;
		}


		.approval-date {

			white-space: nowrap;

			font-size: 8.5px;

			color: #4b5563;

		}


		.approval-comment {

			margin-top: 3px;

			color: #6b7280;

			font-size: 8px;

			font-style: italic;

		}


		/*
        |--------------------------------------------------------------------------
        | FOOTER
        |--------------------------------------------------------------------------
        */

		.document-footer {

			margin-top: 28px;

			padding-top: 10px;

			border-top:
				1px solid #9ca3af;

			display: table;

			width: 100%;

			font-size: 8px;

			color: #6b7280;

		}


		.footer-left {

			display: table-cell;

			text-align: left;

		}


		.footer-right {

			display: table-cell;

			text-align: right;

		}


		/*
        |--------------------------------------------------------------------------
        | PRINT
        |--------------------------------------------------------------------------
        */

		@media print {

			.print-toolbar {

				display: none;

			}


			body {

				font-size: 10pt;

			}


			.document {

				max-width: none;

				width: 100%;

				margin: 0;

			}


			.section {

				page-break-inside: avoid;

			}


			.data-table thead {

				display: table-header-group;

			}


			.data-table tr {

				page-break-inside: avoid;

			}


			.company-header {

				page-break-inside: avoid;

			}


			.document-banner {

				page-break-inside: avoid;

			}

			.approval-workflow-section {
				page-break-inside: auto;
				min-height: 205mm;
			}

			.document-footer {
				position: static;
				margin-top: 0;
				page-break-inside: avoid;
				break-inside: avoid;
			}


			a {

				color: inherit;

				text-decoration: none;

			}

		}
	</style>

</head>


<body>


	<!--
|--------------------------------------------------------------------------
| PRINT TOOLBAR
|--------------------------------------------------------------------------
-->

	<div class="print-toolbar">

		<button
			type="button"
			class="print-button"
			onclick="window.print()">
			Print / Save as PDF
		</button>

	</div>


	<div class="document">


		<!--
    |--------------------------------------------------------------------------
    | COMPANY HEADER
    |--------------------------------------------------------------------------
    -->

		<div class="company-header">


			<div class="company-logo">

				<img
					src=<?= $logo ?>
					alt="Company Logo">

			</div>


			<div class="company-title">

				<h1>
					CONCEPT PAPER
				</h1>

				<p>
					Project Proposal &amp; Approval Document
				</p>

			</div>


			<div class="document-meta">

				<strong>
					<?= e($project["project_id"]) ?>
				</strong>

				<span>
					Generated: <?= e($generatedDate) ?>
				</span>

			</div>


		</div>


		<!--
    |--------------------------------------------------------------------------
    | DOCUMENT BANNER
    |--------------------------------------------------------------------------
    -->

		<div class="document-banner">

			<div class="document-banner-title">

				<?= e($project["project_title"]) ?>

			</div>

			<div class="document-banner-subtitle">

				Official Concept Paper for Review and Approval

			</div>

		</div>


		<!--
    |--------------------------------------------------------------------------
    | 1. PROJECT INFORMATION
    |--------------------------------------------------------------------------
    -->

		<div class="section">

			<div class="section-title">

				<span class="section-number">
					01
				</span>

				Project Information

			</div>


			<table class="information-table">

				<tr>

					<th>
						Project ID
					</th>

					<td>
						<?= e($project["project_id"]) ?>
					</td>

				</tr>


				<tr>

					<th>
						Project Title
					</th>

					<td>
						<strong>
							<?= e($project["project_title"]) ?>
						</strong>
					</td>

				</tr>


				<tr>

					<th>
						Category
					</th>

					<td>
						<?= e($project["category_name"]) ?>
					</td>

				</tr>


				<tr>

					<th>
						Requestor
					</th>

					<td>
						<?= e($project["requestor_name"]) ?>
					</td>

				</tr>


				<tr>

					<th>
						Department
					</th>

					<td>
						<?= e($project["department_name"]) ?>
					</td>

				</tr>


				<tr>

					<th>
						Priority
					</th>

					<td>

						<span class="priority <?= e($priorityClass) ?>">

							<?= e($priority) ?>

						</span>

					</td>

				</tr>


				<tr>

					<th>
						Current Status
					</th>

					<td>

						<span class="status-badge <?= e($statusClass) ?>">

							<?= e($statusLabel) ?>

						</span>

					</td>

				</tr>

			</table>

		</div>


		<!--
    |--------------------------------------------------------------------------
    | 2. STAKEHOLDERS
    |--------------------------------------------------------------------------
    -->

		<div class="section">

			<div class="section-title">

				<span class="section-number">
					02
				</span>

				Stakeholders

			</div>


			<table class="data-table">

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

					<?php if (!empty($stakeholders)): ?>

						<?php foreach ($stakeholders as $s): ?>

							<tr>

								<td>
									<?= e(
										print_status_label(
											$s["stakeholder_type"]
										)
									) ?>
								</td>

								<td>
									<strong>
										<?= e(
											$s["stakeholder_name"]
										) ?>
									</strong>
								</td>

								<td>
									<?= e(
										$s["department_name"] ?? ""
									) ?>
								</td>

								<td>
									<?= e(
										$s["email"] ?? ""
									) ?>
								</td>

							</tr>

						<?php endforeach; ?>

					<?php else: ?>

						<tr>

							<td
								colspan="4"
								class="empty-row">
								No stakeholders have been recorded.
							</td>

						</tr>

					<?php endif; ?>

				</tbody>

			</table>

		</div>


		<!--
    |--------------------------------------------------------------------------
    | 3. CURRENT & PROPOSED STATE
    |--------------------------------------------------------------------------
    -->

		<div class="section">

			<div class="section-title">

				<span class="section-number">
					03
				</span>

				Current &amp; Proposed State

			</div>


			<div class="two-column">


				<div class="column">

					<div class="mini-label">
						Current State
					</div>

					<div class="content-box">

						<?= nl2br(
							e($project["current_state"] ?? "")
						) ?>

					</div>

				</div>


				<div class="column">

					<div class="mini-label">
						Proposed State
					</div>

					<div class="content-box">

						<?= nl2br(
							e($project["proposed_state"] ?? "")
						) ?>

					</div>

				</div>


			</div>

		</div>


		<!--
    |--------------------------------------------------------------------------
    | 4. SCOPE
    |--------------------------------------------------------------------------
    -->

		<div class="section">

			<div class="section-title">

				<span class="section-number">
					04
				</span>

				Project Scope

			</div>


			<div class="two-column">


				<div class="column">

					<div class="mini-label">
						Scope Included
					</div>

					<div class="content-box">

						<?= nl2br(
							e($project["scope_included"] ?? "")
						) ?>

					</div>

				</div>


				<div class="column">

					<div class="mini-label">
						Scope Excluded
					</div>

					<div class="content-box">

						<?= nl2br(
							e($project["scope_excluded"] ?? "")
						) ?>

					</div>

				</div>


			</div>

		</div>


		<?php if (trim((string)($project["scope_included"] ?? "")) !== "" && trim((string)($project["scope_excluded"] ?? "")) !== ""): ?>

			<div class="section">
				<div class="section-title">
					<span class="section-number">05</span>
					Expected End Date
				</div>
				<table class="information-table">
					<tr>
						<th>Expected End Date</th>
						<td><?= !empty($project["end_date"]) ? e(date("d M Y", strtotime($project["end_date"]))) : "Not specified" ?></td>
					</tr>
				</table>
			</div>

		<?php endif; ?>

		<div class="section">
			<div class="section-title">Expected Timeline by Technical Reviewer</div>
			<table class="information-table">
				<tr>
					<th>Expected Start Date</th>
					<td><?= !empty($technicalTimeline['expected_start_date']) ? e(date('d M Y', strtotime($technicalTimeline['expected_start_date']))) : 'Pending technical review' ?></td>
				</tr>
				<tr>
					<th>Expected End Date</th>
					<td><?= !empty($technicalTimeline['expected_end_date']) ? e(date('d M Y', strtotime($technicalTimeline['expected_end_date']))) : 'Pending technical review' ?></td>
				</tr>
				<?php if (!empty($technicalTimeline['reviewer_name'])): ?>
					<tr>
						<th>Technical Reviewer</th>
						<td><?= e($technicalTimeline['reviewer_name']) ?></td>
					</tr>
				<?php endif; ?>
			</table>
		</div>


		<!--
    |--------------------------------------------------------------------------
    | 6. APPROVAL WORKFLOW
    |--------------------------------------------------------------------------
    -->

		<div class="section approval-workflow-section">

			<div class="section-title">

				<span class="section-number">
					06
				</span>

				Approval Workflow

			</div>


			<table class="data-table approval-table">

				<thead>

					<tr>

						<th>
							Step
						</th>

						<th>
							Approval Stage
						</th>

						<th>
							Approver
						</th>

						<th>
							Status
						</th>

						<th>
							Action Date
						</th>

					</tr>

				</thead>


				<tbody>

					<?php if (!empty($approvals)): ?>

						<?php foreach ($approvals as $a): ?>

							<?php

							$approvalStatus =
								strtoupper(
									trim(
										$a["status"] ?? ""
									)
								);

							$approvalStatusClass =
								print_status_class(
									$approvalStatus
								);

							$approvalStatusLabel =
								print_status_label(
									$approvalStatus
								);

							?>

							<tr>


								<td>

									<strong>
										<?= e(
											$a["step_order"]
										) ?>
									</strong>

								</td>


								<td>

									<?= e(
										$a["step_name"]
									) ?>

								</td>


								<td>

									<?= e(
										$a["approver_name"]
											?? "Pending Assignment"
									) ?>

								</td>


								<td>

									<span
										class="
                                        status-badge
                                        <?= e(
											$approvalStatusClass
										) ?>
                                    ">

										<?= e(
											$approvalStatusLabel
										) ?>

									</span>

								</td>


								<td>

									<?php if (!empty($a["action_date"])): ?>

										<div class="approval-date">

											<?= e(
												date(
													"d M Y, h:i A",
													strtotime(
														$a["action_date"]
													)
												)
											) ?>

										</div>

									<?php else: ?>

										<div class="approval-date">
											—
										</div>

									<?php endif; ?>




								</td>


							</tr>

						<?php endforeach; ?>

					<?php else: ?>

						<tr>

							<td
								colspan="5"
								class="empty-row">

								No approval workflow records are available.

							</td>

						</tr>

					<?php endif; ?>

				</tbody>

			</table>

		</div>


		<!--
    |--------------------------------------------------------------------------
    | DOCUMENT FOOTER
    |--------------------------------------------------------------------------
    -->

		<div class="document-footer">

			<div class="footer-left">

				Concept Paper Management System

			</div>


			<div class="footer-right">

				Project:
				<?= e($project["project_id"]) ?>

				&nbsp; | &nbsp;

				Generated:
				<?= e($generatedDate) ?>

			</div>

		</div>


	</div>


</body>

</html>