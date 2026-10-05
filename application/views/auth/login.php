<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Secure login to the Concept Paper Management System.">
	<meta name="application-name" content="Concept Paper Management System">
	<meta name="author" content="Atlas Honda">
	<meta name="robots" content="noindex, nofollow, noarchive">
	<meta name="referrer" content="same-origin">
	<meta name="theme-color" content="#212529">
	<title>Concept Paper Portal | Login</title>
	<link rel="icon" href="<?= base_url('assets/images/atlas.jpg') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap-icons.css') ?>">
</head>

<body class=" company-bg">

	<img class="logo mx-4" src="<?= base_url('assets/images/AtlasHonda.png') ?>" alt="Atlas Honda">

	<div class="container">
		<div class="row justify-content-center">

			<div class="col-md-5 mt-5">

				<div class="card shadow" style="width: 26rem;">

					<div class="card-body p-4">


						<h3 class="fs-1 text-center mb-4">

							Login

						</h3>


						<?php if ($error): ?>

							<div class="alert alert-danger">

								<?= e($error ?? "") ?>

							</div>

						<?php endif; ?>


						<form method="POST" action="<?= site_url('login') ?>">
							<?= csrf_field() ?>


							<div class="mb-3 input-group mb-4">
								<span class="input-group-text bg-secondary-subtle text-secondary" id="email-addon">
									<i class="bi bi-person-fill" style="font-size: 1.2rem;"></i>
								</span>

								<input
									type="email"
									name="email"
									class="form-control"
									placeholder="Enter your email"
									required>
							</div>

							<div class="mb-3 input-group mb-4">
								<span class="input-group-text bg-secondary-subtle text-secondary" id="password-addon">
									<i class="bi bi-lock-fill" style="font-size: 1.2rem;"></i>
								</span>

								<input
									type="password"
									name="password"
									class="form-control"
									placeholder="Enter your Password"
									required>
							</div>


							<button
								type="submit"
								class="btn btn-primary w-100">
								Login

							</button>


						</form>


					</div>

				</div>

			</div>

		</div>

	</div>


</body>

</html>
