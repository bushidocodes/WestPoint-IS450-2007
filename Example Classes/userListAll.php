<?php

	session_start();
	require_once("./includes/header.php");
	require_once("./includes/startBody.php");
	require_once("./includes/PersonClass.php");
	
	$user= new Person();
	$personArray = $user->getAllPeople();
	$addedUserID = $_GET['addedPersonID'];
	$user->printPersonTable($personArray, $addedUserID);
	
	require_once("./includes/footer.php");
?>