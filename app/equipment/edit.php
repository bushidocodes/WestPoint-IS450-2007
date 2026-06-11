<?php
	require_once(dirname(__FILE__) . "/../../classes/Equipment.php");

	$pageTitle = "Edit Equipment";

	//Handle the form submission before any output so we can redirect
	if($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		if(session_status() === PHP_SESSION_NONE) session_start();
		$equipment = new Equipment();
		$oldSN = trim($_POST['oldSerialNumber'] ?? '');
		$newSN = trim($_POST['serialNumber'] ?? '');
		$role = $_POST['role'] ?? '';
		$detail = trim($_POST[$role === 'projector' ? 'connector' : 'image'] ?? '');
		$result = $equipment->modifyEquipment(
			$oldSN,
			$newSN,
			$_POST['availability'] ?? '1',
			trim($_POST['dateAdded'] ?? ''),
			trim($_POST['workingStatus'] ?? ''),
			$role,
			$detail);
		if($result)
		{
			header("Location: index.php?added=" . urlencode($newSN) .
				"&message=" . urlencode("Updated " . $newSN));
			exit;
		}
		$errorMessage = $_SESSION['ERROR'] ?? 'Could not update the equipment';
	}

	require_once(dirname(__FILE__) . "/../includes/header.php");

	$sn = $_GET['sn'] ?? ($_POST['oldSerialNumber'] ?? '');
	$equipmentForForm = new Equipment();
	$existing = $sn !== '' ? $equipmentForForm->searchForEquipmentBySerialNumber($sn) : false;
?>
	<h2>Edit Equipment</h2>
<?php
	if(isset($errorMessage))
		print("<div class='error'>" . htmlspecialchars($errorMessage) . "</div>");

	if(!$existing)
	{
		print("<div class='error'>" . htmlspecialchars($_SESSION['ERROR'] ?? 'The equipment does not exist') . "</div>");
		require_once(dirname(__FILE__) . "/../includes/footer.php");
		exit;
	}

	$existingRole = $existing->getRole();
	$image = is_a($existing, 'Laptop') ? $existing->getImage() : '';
	$connector = is_a($existing, 'Projector') ? $existing->getConnector() : '';
?>
	<form class="stacked" method="post" action="edit.php">
		<input type="hidden" name="oldSerialNumber" value="<?php print(htmlspecialchars($existing->getSerialNumber(), ENT_QUOTES)); ?>"/>
		<label>Serial Number</label>
		<input type="text" name="serialNumber" maxlength="10" value="<?php print(htmlspecialchars($existing->getSerialNumber())); ?>"/>
		<label>Availability</label>
		<select name="availability">
			<option value="1" <?php if($existing->getAvailability()) print('selected'); ?>>Available</option>
			<option value="0" <?php if(!$existing->getAvailability()) print('selected'); ?>>Checked Out</option>
		</select>
		<label>Date Added</label>
		<input type="date" name="dateAdded" value="<?php print(htmlspecialchars($existing->getDateAdded())); ?>"/>
		<label>Working Status</label>
		<input type="text" name="workingStatus" value="<?php print(htmlspecialchars($existing->getWorkingStatus() ?? '')); ?>"/>
		<label>Type</label>
		<select name="role" onchange="showDetails(this.value)">
			<option value="laptop" <?php if($existingRole == 'laptop') print('selected'); ?>>laptop</option>
			<option value="projector" <?php if($existingRole == 'projector') print('selected'); ?>>projector</option>
			<option value="other" <?php if($existingRole != 'laptop' && $existingRole != 'projector') print('selected'); ?>>other</option>
		</select>

		<fieldset id="laptopFields" <?php if($existingRole != 'laptop') print("style='display:none'"); ?>>
			<legend>Laptop details</legend>
			<label>Software Image</label>
			<input type="text" name="image" value="<?php print(htmlspecialchars($image ?? '')); ?>"/>
		</fieldset>

		<fieldset id="projectorFields" <?php if($existingRole != 'projector') print("style='display:none'"); ?>>
			<legend>Projector details</legend>
			<label>Connector</label>
			<select name="connector">
				<option value="wired" <?php if($connector == 'wired') print('selected'); ?>>wired</option>
				<option value="wireless" <?php if($connector == 'wireless') print('selected'); ?>>wireless</option>
			</select>
		</fieldset>

		<input type="submit" value="Save Changes"/>
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
