<?php
	require_once(dirname(__FILE__) . "/../includes/auth_check.php");
	require_once(dirname(__FILE__) . "/../../classes/Equipment.php");

	if(session_status() === PHP_SESSION_NONE) session_start();

	if($_SERVER['REQUEST_METHOD'] !== 'POST')
	{
		header("Location: index.php");
		exit;
	}

	$serialNumber = trim($_POST['serialNumber'] ?? '');
	$equipment = new Equipment();

	if($serialNumber !== '' && $equipment->deleteEquipment($serialNumber))
	{
		header("Location: index.php?message=" . urlencode("Deleted " . $serialNumber));
	}
	else
	{
		header("Location: index.php?message=" .
			urlencode("Could not delete: " . ($_SESSION['ERROR'] ?? 'unknown error')));
	}
	exit;
?>
