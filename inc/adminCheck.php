<?
	$is_admin = 0;

	if ($admin_ip) $is_admin = 1;
	if ($loggedIn && $level > 2) $is_admin = 1;
?>
