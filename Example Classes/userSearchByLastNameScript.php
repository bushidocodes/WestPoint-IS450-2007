<?php

	session_start();
	require_once("./includes/header.php");
	require_once("./includes/startBody.php");
	require_once("./includes/PersonClass.php");

	$user= new Person();
	
	$userArray = array();
	//$userArray = $user->searchPersonByLastName('stanton');	
	$userArray = $user->searchPersonByLastName($_POST['lastName']);
	

	$user->printPersonTable($userArray);

	
	require_once("./includes/footer.php");
?>