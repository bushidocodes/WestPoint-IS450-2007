<?php
	require_once(dirname(__FILE__) . "/../../classes/Equipment.php");

	$pageTitle = "Add Equipment";

	//Handle the form submission before any output so we can redirect
	if($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		if(session_status() === PHP_SESSION_NONE) session_start();
		$equipment = new Equipment();
		$serialNumber = trim($_POST['serialNumber'] ?? '');
		$role = $_POST['role'] ?? '';
		$detail = trim($_POST[$role === 'projector' ? 'connector' : 'image'] ?? '');
		$result = false;
		if($serialNumber !== '')
		{
			$result = $equipment->addEquipment(
				$serialNumber,
				$_POST['availability'] ?? '1',
				trim($_POST['dateAdded'] ?? ''),
				trim($_POST['workingStatus'] ?? ''),
				$role,
				$detail);
		}
		else
		{
			$_SESSION['ERROR'] = "Serial number is required";
		}
		if($result)
		{
			header("Location: index.php?added=" . urlencode($serialNumber) .
				"&message=" . urlencode("Added " . $serialNumber));
			exit;
		}
		$errorMessage = $_SESSION['ERROR'] ?? 'Could not add the equipment';
	}

	require_once(dirname(__FILE__) . "/../includes/header.php");
?>
	<h2>Add Equipment</h2>
<?php
	if(isset($errorMessage))
		print("<div class='error'>" . htmlspecialchars($errorMessage) . "</div>");
?>
	<form class="stacked" method="post" action="add.php">
		<label>Serial Number</label>
		<input type="text" name="serialNumber" maxlength="10" value="<?php print(htmlspecialchars($_POST['serialNumber'] ?? '')); ?>"/>
		<label>Availability</label>
		<select name="availability">
			<option value="1">Available</option>
			<option value="0">Checked Out</option>
		</select>
		<label>Date Added</label>
		<input type="date" name="dateAdded" value="<?php print(htmlspecialchars($_POST['dateAdded'] ?? date('Y-m-d'))); ?>"/>
		<label>Working Status</label>
		<input type="text" name="workingStatus" value="<?php print(htmlspecialchars($_POST['workingStatus'] ?? 'works fine')); ?>"/>
		<label>Type</label>
		<select name="role" onchange="showDetails(this.value)">
			<option value="laptop">laptop</option>
			<option value="projector">projector</option>
			<option value="other">other</option>
		</select>

		<fieldset id="laptopFields">
			<legend>Laptop details</legend>
			<label>Software Image</label>
			<input type="text" name="image" value="Base"/>
		</fieldset>

		<fieldset id="projectorFields" style="display:none">
			<legend>Projector details</legend>
			<label>Connector</label>
			<select name="connector">
				<option value="wired">wired</option>
				<option value="wireless">wireless</option>
			</select>
		</fieldset>

		<input type="submit" value="Add Equipment"/>
	</form>
	<script>
		function showDetails(role) {
			document.getElementById('laptopFields').style.display = (role === 'laptop') ? '' : 'none';
			document.getElementById('projectorFields').style.display = (role === 'projector') ? '' : 'none';
		}
	</script>
<?php
	require_once(dirname(__FILE__) . "/../includes/footer.php");
?>
