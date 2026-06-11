<?php
require_once("ReservationManager.php");

# A Reservation represents one piece of equipment checked out to one person.
class Reservation
{
	protected $serialNumber;
	protected $userID;
	protected $dateOut;
	protected $dateIn;
	//Display-only fields filled by the JOIN in mgrGetAllReservations
	protected $lastName;
	protected $firstName;
	protected $equipmentType;
	protected $mgr;

	function __construct()
	{
		$this->serialNumber = null;
		$this->userID = null;
		$this->dateOut = null;
		$this->dateIn = null;
		$this->lastName = null;
		$this->firstName = null;
		$this->equipmentType = null;
		$this->mgr = new ReservationManager();
	}

	function getSerialNumber() {return $this->serialNumber;}
	function setSerialNumber($sn) {$this->serialNumber = $sn;}

	function getUserID() {return $this->userID;}
	function setUserID($id) {$this->userID = $id;}

	function getDateOut() {return $this->dateOut;}
	function setDateOut($date) {$this->dateOut = $date;}

	function getDateIn() {return $this->dateIn;}
	function setDateIn($date) {$this->dateIn = $date;}

	function getLastName() {return $this->lastName;}
	function setLastName($name) {$this->lastName = $name;}

	function getFirstName() {return $this->firstName;}
	function setFirstName($name) {$this->firstName = $name;}

	function getEquipmentType() {return $this->equipmentType;}
	function setEquipmentType($type) {$this->equipmentType = $type;}

	public function buildReservation($arrayOfValues)
	{
		$this->setSerialNumber($arrayOfValues['serialNumber']);
		$this->setUserID($arrayOfValues['userID']);
		$this->setDateOut($arrayOfValues['dateOut']);
		$this->setDateIn($arrayOfValues['dateIn']);
		$this->setLastName($arrayOfValues['lastName'] ?? null);
		$this->setFirstName($arrayOfValues['firstName'] ?? null);
		$this->setEquipmentType($arrayOfValues['role'] ?? null);
		return $this;
	}

	function getAllReservations()
	{
		return $this->mgr->mgrGetAllReservations();
	}

	function checkOut($serialNumber,$userID,$dateOut,$dateIn)
	{
		return $this->mgr->mgrAddReservation($serialNumber,$userID,$dateOut,$dateIn);
	}

	function checkIn($serialNumber)
	{
		return $this->mgr->mgrDeleteReservation($serialNumber);
	}

	function getAvailableEquipment()
	{
		return $this->mgr->mgrGetAvailableEquipment();
	}

	function printReservationTable($arrayOfReservations)
	{
		print("<table border = '1' id='smallTable'>
			<thead>
				<tr> <th> Serial Number </th> <th> Type </th> <th> Checked Out To </th>
					<th> Date Out </th> <th> Date Due </th> <th> Actions </th></tr>
			</thead>
			<tbody>");
		foreach ($arrayOfReservations as $reservation)
		{
			$reservation->display();
		}
		print("</tbody> </table>");
	}

	public function display()
	{
		$who = htmlspecialchars($this->getLastName() . ", " . $this->getFirstName() .
			" (" . $this->getUserID() . ")");
		print("<tr> <td>" . htmlspecialchars($this->getSerialNumber()) . "</td><td>" .
			htmlspecialchars($this->getEquipmentType()) . "</td><td>" . $who . "</td><td>" .
			$this->getDateOut() . "</td><td>" . $this->getDateIn() . "</td>");
		print("<td><form method='post' action='/app/reservations/checkin.php' style='display:inline'>
			<input type='hidden' name='serialNumber' value='" . htmlspecialchars($this->getSerialNumber(), ENT_QUOTES) . "'/>
			<input type='submit' value='Check In'/>
			</form></td></tr>");
	}

}
?>
