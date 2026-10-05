<?php
defined('BASEPATH') or exit('No direct script access allowed');
function e($value)
{
	return html_escape($value ?? '');
}
function status_label($status)
{
	return ucwords(strtolower(str_replace('_', ' ', (string)$status)));
}

function csrf_field()
{
	$CI = &get_instance();
	return '<input type="hidden" name="' . html_escape($CI->security->get_csrf_token_name()) . '" value="' . html_escape($CI->security->get_csrf_hash()) . '">';
}
