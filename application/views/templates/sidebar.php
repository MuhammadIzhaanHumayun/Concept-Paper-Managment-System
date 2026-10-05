<?php
$currentRoute = $currentRoute ?? uri_string();
$nav = $nav ?? array();
?>
<aside class="sidebar col-md-2 bg-dark-subtle p-3">
	<?php if (empty($nav['is_admin'])): ?>
		<h6 class="text-muted">MENU</h6>
		<a href="<?= site_url('dashboard') ?>" class="sidebar-link <?= $currentRoute === 'dashboard' || $currentRoute === '' ? 'active' : '' ?>">Dashboard</a>

		<?php if (!empty($nav['is_requestor'])): ?>
			<a href="<?= site_url('projects/create') ?>" class="sidebar-link <?= strpos($currentRoute, 'projects/create') === 0 ? 'active' : '' ?>">New Concept Paper</a>
			<a href="<?= site_url('projects') ?>" class="sidebar-link <?= $currentRoute === 'projects' ? 'active' : '' ?>">My Projects</a>
		<?php endif; ?>

		<?php if (!empty($nav['can_manage_workflows'])): ?>
			<a href="<?= site_url('workflows') ?>" class="sidebar-link <?= strpos($currentRoute, 'workflows') === 0 ? 'active' : '' ?>">Define Workflows</a>
		<?php endif; ?>

		<?php if (!empty($nav['is_approver'])): ?>
			<a href="<?= site_url('approvals') ?>" class="sidebar-link <?= strpos($currentRoute, 'approvals') === 0 ? 'active' : '' ?>">Approval Inbox</a>
		<?php endif; ?>
	<?php else: ?>
		<h6 class="text-muted mt-3">ADMINISTRATION</h6>
		<a href="<?= site_url('admin/users') ?>" class="sidebar-link <?= strpos($currentRoute, 'admin/users') === 0 || strpos($currentRoute, 'admin/user') === 0 ? 'active' : '' ?>">Users</a>
		<a href="<?= site_url('admin/roles') ?>" class="sidebar-link <?= strpos($currentRoute, 'admin/roles') === 0 ? 'active' : '' ?>">Roles</a>
		<a href="<?= site_url('admin/departments') ?>" class="sidebar-link <?= strpos($currentRoute, 'admin/departments') === 0 ? 'active' : '' ?>">Departments</a>
		<a href="<?= site_url('admin/teams') ?>" class="sidebar-link <?= strpos($currentRoute, 'admin/teams') === 0 ? 'active' : '' ?>">Teams</a>
		<a href="<?= site_url('admin/categories') ?>" class="sidebar-link <?= strpos($currentRoute, 'admin/categories') === 0 ? 'active' : '' ?>">Project Categories</a>
		<a href="<?= site_url('admin/category-assignments') ?>" class="sidebar-link <?= strpos($currentRoute, 'admin/category-assignments') === 0 ? 'active' : '' ?>">Department Categories</a>
		<a href="<?= site_url('admin/workflows') ?>" class="sidebar-link <?= strpos($currentRoute, 'admin/workflows') === 0 ? 'active' : '' ?>">Approval Workflows</a>
	<?php endif; ?>
</aside>