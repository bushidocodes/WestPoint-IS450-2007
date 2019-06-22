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
		$query = "SELECT * FROM equipmentTable WHERE serialNumber = '" . $serialNumber . "');";
		$resultSet = $this->mDb->Execute($query);
		if(!$resultSet->fields)
		{
			$_SESSION['ERROR'] = "The equipment does not exist";
			return false;
		}
		else 
		{
			$tempEquipment->buildEquipment($resultSet->fields);
			return $tempEquipment;
		}
	}
	# Defines a manager Select funciton for an instance of laptop
	public function mgrSearchForLaptopBySerialNumber($serialNumber)
	{
		$tempLaptop = new Laptop();
		$query = "SELECT DISTINCT laptops.serialNumber,image,dateAdded, workingStatus
			FROM equipmentTable
			JOIN submitReservationTable
			ON equipmentTable.serialNumber=submitReservationTable.serialNumber
			JOIN laptops
			ON equipmentTable.serialNumber=laptops.serialNumber
			WHERE availability='1'
			AND laptops.serialNumber LIKE '$serialNumber'";
		$resultSet = $this->mDb->Execute($query);
		if(!$resultSet->fields)
		{
			$_SESSION['ERROR'] = "The equipment does not exist";
			return false;
		}
		else 
		{
			$tempLaptop->buildLaptop($resultSet->fields);
			return $tempLaptop;
		}
	}	
	
	# Defines a manager Add function for an instance of equipment
	public function mgrAddEquipment($serialNumber,$availability,$dateAdded,$workingStatus)
	{
		$query = "INSERT INTO equipmentTable (serialNumber,availability,dateAdded,workingStatus)
VALUES ($serialNumber,$availability,$dateAdded,$workingStatus);";
		$resultSet = $this->mDb->Execute($query);
		if(!$resultSet->fields)
		{
			$_SESSION['ERROR'] = "Error Inputing Data into equipmentTable";
			return false;
		}
		else 
		{
			print("Data Entry into equipmentTable Complete");
		}
	}		
	
	# Defines a manager Add function for an instance of laptop
	public function mgrAddLaptop($serialNumber,$availability,$dateAdded,$workingStatus,$image)
	{
		$queryOne = "INSERT INTO equipmentTable (serialNumber,availability,dateAdded,workingStatus)
VALUES ($serialNumber,$availability,$dateAdded,$workingStatus);";
		$queryTwo = "INSERT INTO laptops (serialNumber,image) VALUES ($serialNumber,$image);";
		$resultSetOne = $this->mDb->Execute($queryOne);
		if(!$resultSet->fields)
		{
			$_SESSION['ERROR'] = "Error Inputing Data into laptops";
			return false;
		}
		else 
		{
			print("Data Entry into laptops Complete");
		}
		$resultSetTwo = $this->mDb->Execute($queryTwo);
		if(!$resultSet->fields)
		{
			$_SESSION['ERROR'] = "Error Inputing Data into laptops";
			return false;
		}
		else 
		{
			print("Data Entry into laptops Complete");
		}
	}		
	
	# Defines a manager Modify function for an instance of equipment
	public function mgrModifyEquipment($oldSN,$newSN,$availability,$dateAdded,$workingStatus)
	{
		$query = "UPDATE equipmentTable
SET serialNumber = $newSN, availability = $availability, dateAdded = $dateAdded, workingStatus = $workingStatus
WHERE serialNumber = $oldSN;";
		$resultSet = $this->mDb->Execute($query);
		if(!$resultSet->fields)
		{
			$_SESSION['ERROR'] = "Error Updating Row in equipmentTable";
			return false;
		}
		else 
		{
			print("Data Update of equipmentTable Complete");
		}
	}		
	# Defines a manager Modify function for an instance of laptop
		public function mgrModifyLaptop($oldSN,$newSN,$availability,$dateAdded,$workingStatus,$image)
	{
		$queryOne = "UPDATE equipmentTable
SET serialNumber = $newSN, availability = $availability, dateAdded = $dateAdded, workingStatus = $workingStatus
WHERE serialNumber = $oldSN;";
		$queryTwo = "UPDATE laptops
SET serialNumber = $newSN, image = $image
WHERE serialNumber = $oldSN;";
		$resultSetOne = $this->mDb->Execute($queryOne);
		if(!$resultSet->fields)
		{
			$_SESSION['ERROR'] = "Error updating equipmentTable";
			return false;
		}
		else 
		{
			print("Data Update of equipmentTable Complete");
		}
		$resultSetTwo = $this->mDb->Execute($queryTwo);
		if(!$resultSet->fields)
		{
			$_SESSION['ERROR'] = "Error Inputing Data into laptops";
			return false;
		}
		else 
		{
			print("Data Update of laptops Complete");
		}
	}	
	
	# Defines a manager Delete function for an instance of equipment
		public function mgrDeleteEquipment($serialNumber)
	{
		$query = "DELETE FROM equipmentTable WHERE serialNumber = $serialNumber;";
		$resultSet = $this->mDb->Execute($query);
		if(!$resultSet->fields)
		{
			$_SESSION['ERROR'] = "Error Deleting Data from equipmentTable";
			return false;
		}
		else 
		{
			print("Data Deletion from equipmentTable Complete");
		}
	}		
	# Defines a manager Delete function for an instance of laptop
		public function mgrDeleteLaptop($serialNumber)
	{
		$queryOne = "DELETE FROM laptops WHERE serialNumber = $serialNumber;";
		$resultSetOne = $this->mDb->Execute($queryOne);
		if(!$resultSet->fields)
		{
			$_SESSION['ERROR'] = "Error Deleting Data from laptops";
			return false;
		}
		else 
		{
			print("Data Deletion from laptops Complete");
		}
		$queryTwo = "DELETE FROM equipmentTable WHERE serialNumber = $serialNumber;";
		$resultSetTwo = $this->mDb->Execute($queryTwo);
		if(!$resultSet->fields)
		{
			$_SESSION['ERROR'] = "Error Deleting Data from equipmentTable";
			return false;
		}
		else 
		{
			print("Data Deletion from equipmentTable Complete");
		}
	}		
}
?>