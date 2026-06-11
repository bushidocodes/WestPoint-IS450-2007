<?php
	require_once(dirname(__FILE__) . "/../../classes/Reservation.php");
	require_once(dirname(__FILE__) . "/../../classes/Person.php");

	$pageTitle = "Check Out Equipment";

	//Handle the form submission before any output so we can redirect
	if($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		if(session_status() === PHP_SESSION_NONE) session_start();
		$reservation = new Reservation();
		$serialNumber = trim($_POST['serialNumber'] ?? '');
		$userID = trim($_POST['userID'] ?? '');
		$dateOut = trim($_POST['dateOut'] ?? '');
		$dateIn = trim($_POST['dateIn'] ?? '');
		$result = false;
		if($serialNumber !== '' && $userID !== '' && $dateOut !== '' && $dateIn !== '')
		{
			$result = $reservation->checkOut($serialNumber,$userID,$dateOut,$dateIn);
		}
		else
		{
			$_SESSION['ERROR'] = "All fields are required";
		}
		if($result)
		{
			header("Location: index.php?message=" .
				urlencode("Checked out " . $serialNumber . " to " . $userID));
			exit;
		}
		$errorMessage = $_SESSION['ERROR'] ?? 'Could not check out the equipment';
	}

	require_once(dirname(__FILE__) . "/../includes/header.php");

	$reservationForForm = new Reservation();
	$available = $reservationForForm->getAvailableEquipment();
	$personForForm = new Person();
	$people = $personForForm->getAllPeople();
?>
	<h2>Check Out Equipment</h2>
<?php
	if(isset($errorMessage))
		print("<div class='error'>" . htmlspecialchars($errorMessage) . "</div>");

	if(count($available) === 0)
	{
		print("<p>No equipment is available for checkout.</p>");
		require_once(dirname(__FILE__) . "/../includes/footer.php");
		exit;
	}
?>
	<form class="stacked" method="post" action="checkout.php">
		<label>Equipment</label>
		<select name="serialNumber">
<?php
	foreach($available as $item)
	{
		print("<option value='" . htmlspecialchars($item['serialNumber'], ENT_QUOTES) . "'>" .
			htmlspecialchars($item['serialNumber'] . " (" . $item['role'] . ")") . "</option>");
	}
?>
		</select>
		<label>Check out to</label>
		<select name="userID">
<?php
	foreach($people as $person)
	{
		print("<option value='" . htmlspecialchars($person->getUserID(), ENT_QUOTES) . "'>" .
			htmlspecialchars($person->getLastName() . ", " . $person->getFirstName() .
				" (" . $person->getUserID() . ")") . "</option>");
	}
?>
		</select>
		<label>Date Out</label>
		<input type="date" name="dateOut" value="<?php print(date('Y-m-d')); ?>"/>
		<label>Date Due</label>
		<input type="date" name="dateIn" value="<?php print(date('Y-m-d', strtotime('+7 days'))); ?>"/>
		<br/><input type="submit" value="Check Out"/>
	</form>
<?php
	require_once(dirname(__FILE__) . "/../includes/footer.php");
?>
