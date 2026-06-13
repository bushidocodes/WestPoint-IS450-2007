<?php
	/**
	 * Session-based authentication gate.
	 *
	 * require_once this file as the very first statement of any page that
	 * mutates data (add / edit / delete / checkout / checkin). If no user is
	 * logged in, the request is bounced to the login page and the originally
	 * requested URL is remembered so login can send the user back.
	 */
	if(session_status() === PHP_SESSION_NONE) session_start();

	if(empty($_SESSION['userID']))
	{
		// Only remember same-app paths to avoid an open-redirect via login.
		$requested = $_SERVER['REQUEST_URI'] ?? '';
		if(is_string($requested) && strncmp($requested, '/app/', 5) === 0)
		{
			$_SESSION['LOGIN_REDIRECT'] = $requested;
		}
		header("Location: /app/login.php");
		exit;
	}
?>
