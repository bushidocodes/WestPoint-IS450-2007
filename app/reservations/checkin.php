<?php
	require_once(dirname(__FILE__) . "/../../classes/Reservation.php");

	if(session_status() === PHP_SESSION_NONE) session_start();

	if($_SERVER['REQUEST_METHOD'] !== 'POST')
	{
		header("Location: index.php");
		exit;
	}

	$serialNumber = trim($_POST['serialNumber'] ?? '');
	$reservation = new Reservation();

	if($serialNumber !== '' && $reservation->checkIn($serialNumber))
	{
		header("Location: index.php?message=" . urlencode("Checked in " . $serialNumber));
	}
	else
	{
		header("Location: index.php?message=" .
			urlencode("Could not check in: " . ($_SESSION['ERROR'] ?? 'unknown error')));
	}
	exit;
?>
