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
	<title>Database Error</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			margin: 40px;
			color: #333;
			justify-items: center;
			align-content: center;
			width: 90dvw;
			height: 80dvh;
		}

		h1 {
			font-size: 3rem;
			border-bottom: 1px solid #ddd;
			padding-bottom: 10px
		}
	</style>
</head>

<body>
	<h1><?php echo $heading; ?></h1><?php echo $message; ?>
</body>

</html>
