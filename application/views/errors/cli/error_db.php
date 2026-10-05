<?php defined('BASEPATH') or exit('No direct script access allowed');
echo isset($heading) ? $heading . PHP_EOL : '';
echo isset($message) ? (is_array($message) ? implode(PHP_EOL, $message) : $message) . PHP_EOL : '';
if (isset($exception)) {
	echo $exception->getMessage() . PHP_EOL . $exception->getFile() . ':' . $exception->getLine() . PHP_EOL;
}
if (isset($filepath)) {
	echo $filepath . ':' . ($line ?? '') . PHP_EOL;
}
