<?php
	require_once(dirname(__FILE__) . "/../includes/auth_check.php");
	require_once(dirname(__FILE__) . "/../../classes/Person.php");

	$pageTitle = "People";
	require_once(dirname(__FILE__) . "/../includes/header.php");

	$person = new Person();
	$personArray = $person->getAllPeople();
	$addedPersonID = $_GET['added'] ?? null;
	$message = $_GET['message'] ?? null;
?>
	<h2>People</h2>
<?php
	if($message)
		print("<div class='message'>" . htmlspecialchars($message) . "</div>");
	$person->printPersonTable($personArray, $addedPersonID);
?>
	<p><a href="add.php">Add a person</a> | <a href="search.php">Find a person</a></p>
<?php
	require_once(dirname(__FILE__) . "/../includes/footer.php");
?>
