<?php
	require_once(dirname(__FILE__) . "/../includes/auth_check.php");
	require_once(dirname(__FILE__) . "/../../classes/Reservation.php");

	$pageTitle = "Reservations";
	require_once(dirname(__FILE__) . "/../includes/header.php");

	$reservation = new Reservation();
	$reservations = $reservation->getAllReservations();
	$message = $_GET['message'] ?? null;
?>
	<h2>Reservations</h2>
<?php
	if($message)
		print("<div class='message'>" . htmlspecialchars($message) . "</div>");

	if(count($reservations) === 0)
	{
		print("<p>No equipment is currently checked out.</p>");
	}
	else
	{
		$reservation->printReservationTable($reservations);
	}
?>
	<p><a href="checkout.php">Check out equipment</a></p>
<?php
	require_once(dirname(__FILE__) . "/../includes/footer.php");
?>
