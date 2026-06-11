<?php
require_once("AbstractManager.php");

class EquipmentManager extends AbstractManager
{
	public function __construct()
	{
		parent::__construct();
	}
	# Defines a manager Select function for an instance of equipment
	public function mgrSearchForEquipmentBySerialNumber($serialNumber)
	{
		$tempEquipment = new Equipment();
		$query = "SELECT * FROM equipmentTable WHERE serialNumber = " . $this->mDb->qStr($serialNumber) . ";";
		$resultSet = $this->mDb->Execute($query);
		if(!$resultSet || !$resultSet->fields)
		{
			if(session_status() === PHP_SESSION_NONE) session_start();
			$_SESSION['ERROR'] = "The equipment does not exist";
			return false;
		}
		else
		{
			$tempEquipment->buildEquipment($resultSet->fields);
			return $tempEquipment;
		}
	}
	# Defines a manager Select function for an instance of laptop
		function mgrSearchForLaptopBySerialNumber($serialNumber)
		{
			$tempLaptop = new Laptop();
			$query = "SELECT equipmentTable.serialNumber,availability,dateAdded,workingStatus,role,image
			FROM equipmentTable
			JOIN laptops
			ON equipmentTable.serialNumber=laptops.serialNumber
			WHERE laptops.serialNumber = " . $this->mDb->qStr($serialNumber) . ";";
			$resultSet = $this->mDb->Execute($query);
			if(!$resultSet || !$resultSet->fields)
			{
				return false;
			}
			else
			{
				$tempLaptop->buildLaptop($resultSet->fields);
				return $tempLaptop;
			}
		}
	# Defines a manager Select function for an instance of projector
		function mgrSearchForProjectorBySerialNumber($serialNumber)
		{
			$tempProjector = new Projector();
			$query = "SELECT equipmentTable.serialNumber,availability,dateAdded,workingStatus,role,connector
			FROM equipmentTable
			JOIN projectors
			ON equipmentTable.serialNumber=projectors.serialNumber
			WHERE projectors.serialNumber = " . $this->mDb->qStr($serialNumber) . ";";
			$resultSet = $this->mDb->Execute($query);
			if(!$resultSet || !$resultSet->fields)
			{
				return false;
			}
			else
			{
				$tempProjector->buildProjector($resultSet->fields);
				return $tempProjector;
			}
		}
	# Defines a manager Add function for an instance of equipment
	function mgrAddEquipment($serialNumber,$availability,$dateAdded,$workingStatus,$role)
	{
		$query = "INSERT INTO equipmentTable (serialNumber,availability,dateAdded,workingStatus,role)
		VALUES (" . $this->mDb->qStr($serialNumber) . "," . (int)$availability . "," . $this->mDb->qStr($dateAdded) . "," . $this->mDb->qStr($workingStatus) . "," . $this->mDb->qStr($role) . ");";
		$resultSet = $this->mDb->Execute($query);
		if(!$resultSet)
		{
			if(session_status() === PHP_SESSION_NONE) session_start();
			$_SESSION['ERROR'] = $this->mDb->errorMsg();
			return false;
		}
		return true;
	}

	# Defines a manager Add function for an instance of laptop
	public function mgrAddLaptop($serialNumber,$image)
	{
		$query = "INSERT INTO laptops (serialNumber,image) VALUES (" . $this->mDb->qStr($serialNumber) . "," . $this->mDb->qStr($image) . ");";
		return $this->mDb->Execute($query) ? true : false;
	}

	# Defines a manager Add function for an instance of projector
	public function mgrAddProjector($serialNumber,$connector)
	{
		$query = "INSERT INTO projectors (serialNumber,connector) VALUES (" . $this->mDb->qStr($serialNumber) . "," . $this->mDb->qStr($connector) . ");";
		return $this->mDb->Execute($query) ? true : false;
	}

	# Defines a manager Modify function for an instance of equipment
	public function mgrModifyEquipment($oldSN,$newSN,$availability,$dateAdded,$workingStatus,$role)
	{
		$query = "UPDATE equipmentTable
		SET serialNumber = " . $this->mDb->qStr($newSN) . ", availability = " . (int)$availability . ", dateAdded = " . $this->mDb->qStr($dateAdded) . ", workingStatus = " . $this->mDb->qStr($workingStatus) . ", role = " . $this->mDb->qStr($role) . "
		WHERE serialNumber = " . $this->mDb->qStr($oldSN) . ";";
		$resultSet = $this->mDb->Execute($query);
		if(!$resultSet)
		{
			if(session_status() === PHP_SESSION_NONE) session_start();
			$_SESSION['ERROR'] = $this->mDb->errorMsg();
			return false;
		}
		return true;
	}
	# Defines a manager Modify function for an instance of laptop
		public function mgrModifyLaptop($oldSN,$image)
	{
		$query = "UPDATE laptops
		SET image = " . $this->mDb->qStr($image) . "
		WHERE serialNumber = " . $this->mDb->qStr($oldSN) . ";";
		return $this->mDb->Execute($query) ? true : false;
	}
	# Defines a manager Modify function for an instance of projector
		public function mgrModifyProjector($oldSN,$connector)
	{
		$query = "UPDATE projectors
		SET connector = " . $this->mDb->qStr($connector) . "
		WHERE serialNumber = " . $this->mDb->qStr($oldSN) . ";";
		return $this->mDb->Execute($query) ? true : false;
	}

	# Defines a manager Delete function for an instance of laptop
	public function mgrDeleteLaptop($serialNumber)
	{
		$query = "DELETE FROM laptops WHERE serialNumber = " . $this->mDb->qStr($serialNumber) . ";";
		return $this->mDb->Execute($query) ? true : false;
	}

	# Defines a manager Delete function for an instance of projector
	public function mgrDeleteProjector($serialNumber)
	{
		$query = "DELETE FROM projectors WHERE serialNumber = " . $this->mDb->qStr($serialNumber) . ";";
		return $this->mDb->Execute($query) ? true : false;
	}

	# Defines a manager Delete function for an instance of equipment
		public function mgrDeleteEquipment($serialNumber)
	{
		#	A DELETE OF EQUIPMENT CASCADES TO ITS SUBCLASS TABLES
		$query = "DELETE FROM equipmentTable WHERE serialNumber = " . $this->mDb->qStr($serialNumber) . ";";
		$resultSet = $this->mDb->Execute($query);
		if(!$resultSet)
		{
			if(session_status() === PHP_SESSION_NONE) session_start();
			$_SESSION['ERROR'] = $this->mDb->errorMsg();
			return false;
		}
		return true;
	}

	public function mgrGetAllEquipment()
	{
		$resultArray = array();
		$query = "SELECT equipmentTable.serialNumber,availability,dateAdded,workingStatus,role,
				laptops.image, projectors.connector
			FROM equipmentTable
			LEFT JOIN laptops ON equipmentTable.serialNumber = laptops.serialNumber
			LEFT JOIN projectors ON equipmentTable.serialNumber = projectors.serialNumber
			ORDER BY equipmentTable.serialNumber ASC";
		$resultSet = $this->mDb->Execute($query);
		if($resultSet)
		{
			while(!$resultSet->EOF)
			{
				$tempEquipment = new Equipment();
				$tempEquipment->buildEquipment($resultSet->fields);
				//Make $tempEquipment an object of the correct type (laptop, projector, equipment)
				$tempEquipment = $tempEquipment->determineRole();
				if(is_a($tempEquipment,'Laptop'))
					$tempEquipment->setImage($resultSet->fields['image']);
				if(is_a($tempEquipment,'Projector'))
					$tempEquipment->setConnector($resultSet->fields['connector']);
				$resultArray[] = $tempEquipment;
				$resultSet->MoveNext();
			}
		}
		return $resultArray;
	}
}
?>
