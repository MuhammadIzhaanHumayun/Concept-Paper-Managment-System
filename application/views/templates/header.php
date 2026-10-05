<?php
$user = $user ?? null;
$page_title = $page_title ?? 'Concept Paper Management System';
$meta_description = $meta_description ?? $page_title.' - Concept Paper Management System';
$currentRoute = uri_string();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= e($meta_description) ?>">
    <meta name="application-name" content="Concept Paper Management System">
    <meta name="author" content="Atlas Honda">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <meta name="referrer" content="same-origin">
    <meta name="theme-color" content="#212529">
    <title><?= e($page_title) ?></title>
    <link rel="icon" href="<?= base_url('assets/images/atlas.jpg') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap-icons.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
<nav class="navbar h-5 navbar-bg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold fs-4" href="<?= site_url('dashboard') ?>">Concept Paper Management System</a>
        <?php if ($user): ?>
            <div class="text-white">
                <?= e($user['name']) ?> | <?= e($user['role_name']) ?> |
                <a href="<?= site_url('logout') ?>" aria-label="Logout" title="Logout"><i class="btn bi bi-power fs-4 align-middle text-danger"></i></a>
            </div>
        <?php endif; ?>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <?php if ($user): ?>
            <?php $this->load->view('templates/sidebar', array('user'=>$user, 'nav'=>$nav ?? array(), 'currentRoute'=>$currentRoute)); ?>
            <main class="col-md-10 p-4">
        <?php else: ?>
            <main class="col-12 p-4">
        <?php endif; ?>
