<?php

	session_start();
	require_once("./includes/header.php");
	require_once("./includes/startBody.php");
	require_once("./includes/PersonClass.php");
	
	$user= new Person();
	$user = $user->searchPersonByID($_POST['userID'] ?? '');
	//$user = $user->searchPersonByID('dp0270');
	if($user) {
		$user->display();
	} else {
		echo "<p>Error: " . htmlspecialchars($_SESSION['ERROR']) . "</p>";
	}
	
	require_once("./includes/footer.php");
?>