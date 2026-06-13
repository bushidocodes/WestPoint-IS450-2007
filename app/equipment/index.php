<?php
	require_once(dirname(__FILE__) . "/../includes/auth_check.php");
	require_once(dirname(__FILE__) . "/../../classes/Equipment.php");

	$pageTitle = "Equipment";
	require_once(dirname(__FILE__) . "/../includes/header.php");

	$equipment = new Equipment();
	$equipmentArray = $equipment->getAllEquipment();
	$addedSerialNumber = $_GET['added'] ?? null;
	$message = $_GET['message'] ?? null;
?>
	<h2>Equipment</h2>
<?php
	if($message)
		print("<div class='message'>" . htmlspecialchars($message) . "</div>");
	$equipment->printEquipmentTable($equipmentArray, $addedSerialNumber);
?>
	<p><a href="add.php">Add equipment</a> | <a href="search.php">Find equipment</a></p>
<?php
	require_once(dirname(__FILE__) . "/../includes/footer.php");
?>
