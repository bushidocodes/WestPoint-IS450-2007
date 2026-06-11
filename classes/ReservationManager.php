<?php
require_once("AbstractManager.php");

class ReservationManager extends AbstractManager
{
	public function __construct()
	{
		parent::__construct();
	}

	# Returns all reservations joined with the person and equipment so the
	# list page can show who has what.
	public function mgrGetAllReservations()
	{
		$resultArray = array();
		$query = "SELECT r.serialNumber, r.userID, r.dateOut, r.dateIn,
				p.lastName, p.firstName, e.role
			FROM submitReservationTable r
			JOIN personTable p ON r.userID = p.userID
			JOIN equipmentTable e ON r.serialNumber = e.serialNumber
			ORDER BY r.dateOut ASC, r.serialNumber ASC";
		$resultSet = $this->mDb->Execute($query);
		if($resultSet)
		{
			while(!$resultSet->EOF)
			{
				$tempReservation = new Reservation();
				$tempReservation->buildReservation($resultSet->fields);
				$resultArray[] = $tempReservation;
				$resultSet->MoveNext();
			}
		}
		return $resultArray;
	}

	# Checks equipment out to a person and marks it unavailable.
	public function mgrAddReservation($serialNumber,$userID,$dateOut,$dateIn)
	{
		$query = "INSERT INTO submitReservationTable (serialNumber,userID,dateOut,dateIn)
			VALUES (" . $this->mDb->qStr($serialNumber) . "," . $this->mDb->qStr($userID) . "," .
			$this->mDb->qStr($dateOut) . "," . $this->mDb->qStr($dateIn) . ")";
		$resultSet = $this->mDb->Execute($query);
		if(!$resultSet)
		{
			if(session_status() === PHP_SESSION_NONE) session_start();
			$_SESSION['ERROR'] = $this->mDb->errorMsg();
			return false;
		}
		$query = "UPDATE equipmentTable SET availability = 0
			WHERE serialNumber = " . $this->mDb->qStr($serialNumber);
		$this->mDb->Execute($query);
		return true;
	}

	# Checks equipment back in: removes the reservation and marks it available.
	public function mgrDeleteReservation($serialNumber)
	{
		$query = "DELETE FROM submitReservationTable
			WHERE serialNumber = " . $this->mDb->qStr($serialNumber);
		$resultSet = $this->mDb->Execute($query);
		if(!$resultSet)
		{
			if(session_status() === PHP_SESSION_NONE) session_start();
			$_SESSION['ERROR'] = $this->mDb->errorMsg();
			return false;
		}
		$query = "UPDATE equipmentTable SET availability = 1
			WHERE serialNumber = " . $this->mDb->qStr($serialNumber);
		$this->mDb->Execute($query);
		return true;
	}

	# Equipment that can be checked out right now.
	public function mgrGetAvailableEquipment()
	{
		$resultArray = array();
		$query = "SELECT serialNumber, role FROM equipmentTable
			WHERE availability = 1 ORDER BY serialNumber ASC";
		$resultSet = $this->mDb->Execute($query);
		if($resultSet)
		{
			while(!$resultSet->EOF)
			{
				$resultArray[] = $resultSet->fields;
				$resultSet->MoveNext();
			}
		}
		return $resultArray;
	}

}
?>
