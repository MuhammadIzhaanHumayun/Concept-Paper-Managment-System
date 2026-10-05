<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Concept Paper Management System error page">
	<meta name="application-name" content="Concept Paper Management System">
	<meta name="robots" content="noindex, nofollow, noarchive">
	<meta name="referrer" content="same-origin">
	<meta charset="utf-8">
	<title>Exception</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			margin: 40px;
			color: #333;
		}

		h1 {
			font-size: 2rem;
			border-bottom: 1px solid #ddd;
			padding-bottom: 10px
		}

		pre {
			white-space: pre-wrap;
			background: #f7f7f7;
			padding: 12px;
			border: 1px solid #ddd
		}
	</style>
</head>

<body>
	<h1><?php echo $heading; ?></h1>
	<p><?php echo $message; ?></p>
	<p>Filename: <?php echo $exception->getFile(); ?></p>
	<p>Line Number: <?php echo $exception->getLine(); ?></p><?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>
		<pre><?php echo $exception->getTraceAsString(); ?></pre><?php endif; ?>
</body>

</html>
