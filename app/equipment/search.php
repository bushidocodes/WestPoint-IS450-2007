<?php
	require_once(dirname(__FILE__) . "/../../classes/Equipment.php");

	$pageTitle = "Find Equipment";
	require_once(dirname(__FILE__) . "/../includes/header.php");

	$searchTerm = trim($_POST['searchTerm'] ?? '');
?>
	<h2>Find Equipment</h2>
	<form class="stacked" method="post" action="search.php">
		<label>Serial Number</label>
		<input type="text" name="searchTerm" value="<?php print(htmlspecialchars($searchTerm)); ?>"/>
		<br/><input type="submit" value="Search"/>
	</form>
	<br/>
<?php
	if($_SERVER['REQUEST_METHOD'] === 'POST' && $searchTerm !== '')
	{
		$equipment = new Equipment();
		$result = $equipment->searchForEquipmentBySerialNumber($searchTerm);
		if($result)
			$result->display();
		else
			print("<div class='error'>" . htmlspecialchars($_SESSION['ERROR'] ?? 'Not found') . "</div>");
	}
	elseif($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		print("<div class='error'>Please enter a serial number.</div>");
	}

	require_once(dirname(__FILE__) . "/../includes/footer.php");
?>
