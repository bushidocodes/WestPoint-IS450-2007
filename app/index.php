<?php
	require_once(dirname(__FILE__) . "/../classes/Person.php");
	require_once(dirname(__FILE__) . "/../classes/Equipment.php");
	require_once(dirname(__FILE__) . "/../classes/Reservation.php");

	$pageTitle = "Dashboard";
	require_once(dirname(__FILE__) . "/includes/header.php");

	$person = new Person();
	$equipment = new Equipment();
	$reservation = new Reservation();

	$people = $person->getAllPeople();
	$allEquipment = $equipment->getAllEquipment();
	$reservations = $reservation->getAllReservations();
	$available = $reservation->getAvailableEquipment();
?>
	<h2>Dashboard</h2>
	<table border="1">
		<thead><tr><th>People</th><th>Equipment</th><th>Available Now</th><th>Checked Out</th></tr></thead>
		<tbody><tr>
			<td><a href="/app/people/"><?php print(count($people)); ?> registered</a></td>
			<td><a href="/app/equipment/"><?php print(count($allEquipment)); ?> items</a></td>
			<td><a href="/app/reservations/checkout.php"><?php print(count($available)); ?> items</a></td>
			<td><a href="/app/reservations/"><?php print(count($reservations)); ?> reservations</a></td>
		</tr></tbody>
	</table>

	<h2 style="margin-top:24px">Current Reservations</h2>
<?php
	if(count($reservations) === 0)
	{
		print("<p>No equipment is currently checked out.</p>");
	}
	else
	{
		$reservation->printReservationTable($reservations);
	}

	require_once(dirname(__FILE__) . "/includes/footer.php");
?>
