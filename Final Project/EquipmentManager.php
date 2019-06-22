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
		$query = "SELECT * FROM equipmentTable WHERE serialNumber = '" . $serialNumber . "';";
		$resultSet = $this->mDb->Execute($query);
		if(!$resultSet->fields)
		{
			print("Equipment Selection - Manager Error</br>");
			return false;
		}
		else 
		{
			$tempEquipment->buildEquipment($resultSet->fields);
			print("Equipment Selection - Manager OK</br>");
			return $tempEquipment;
		}
	}
	# Defines a manager Select funciton for an instance of laptop
		function mgrSearchForLaptopBySerialNumber($serialNumber)
		{
			$tempLaptop = new Laptop();
			$query = "SELECT laptops.serialNumber,availability,dateAdded, workingStatus,role,image
			FROM equipmentTable
			JOIN submitReservationTable
			ON equipmentTable.serialNumber=submitReservationTable.serialNumber
			JOIN laptops
			ON equipmentTable.serialNumber=laptops.serialNumber
			WHERE laptops.serialNumber = '" . $serialNumber . "';";
			$resultSet = $this->mDb->Execute($query);
			if(!$resultSet->fields)
			{
				return false;
			}
			else 
			{
				$tempLaptop->buildLaptop($resultSet->fields);
				return $tempLaptop;
			}
		}		
	# Defines a manager Add function for an instance of equipment
	function mgrAddEquipment($serialNumber,$availability,$dateAdded,$workingStatus,$role)
	{
		$query = "INSERT INTO equipmentTable (serialNumber,availability,dateAdded,workingStatus,role)
		VALUES ($serialNumber,$availability,'" . $dateAdded . "','" . $workingStatus . "','" . $role . "');";
		$resultSet = $this->mDb->Execute($query);
		if($resultSet->fields)
		{
			print("Equipment Insertion - Manager Error</br>");
			return false;
		}
		else 
		{
			print("Equipment Insertion - Manager OK</br>");
			return true;
		}
	}		
	
	# Defines a manager Add function for an instance of laptop
	public function mgrAddLaptop($serialNumber,$image)
	{
		$query = "INSERT INTO laptops (serialNumber,image) VALUES ($serialNumber,'$image');";
		$resultSet = $this->mDb->Execute($query);
		if($resultSet->fields)
		{
			print("Error Inputing Data into laptops Table</br>");
			return false;
		}
		else 
		{
			print("Data Entry into laptops Complete</br>");
			return true;
		}
	}		
	
	# Defines a manager Modify function for an instance of equipment
	public function mgrModifyEquipment($oldSN,$newSN,$availability,$dateAdded,$workingStatus,$role)
	{
		$query = "UPDATE equipmentTable
		SET serialNumber = $newSN, availability = $availability, dateAdded = '" . $dateAdded . "', workingStatus = '" . $workingStatus . "', role = '" . $role . "'
		WHERE serialNumber = $oldSN;";
		$resultSet = $this->mDb->Execute($query);
		if($resultSet->fields)
		{
			print("Equipment Modification - Manager Error</br>");
			return false;
		}
		else 
		{
			print("Equipment Modification - Manager OK</br>");
			return true;
		}
	}		
	# Defines a manager Modify function for an instance of laptop
		public function mgrModifyLaptop($oldSN,$image)
	{
		$query = "UPDATE laptops 
		SET image = '$image' 
		WHERE serialNumber = $oldSN;";
		$resultSet = $this->mDb->Execute($query);
		if($resultSet->fields)
		{
			print("Error updating laptops");
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
		if($resultSet->fields)
		{
			print("Equipment Deletion - Manager Error</br>");
			return false;
		}
		else 
		{
			print("Equipment Deletion - Manager OK</br>");
			return true;
		}
	}		
#		THIS IS UNNEEDED BECAUSE A DELETE OF EQUIPMENT CASCADES TO ITS SUBCLASSES
#	 	Defines a manager Delete function for an instance of laptop
#		public function mgrDeleteLaptop($serialNumber)
#	{
#		$query = "DELETE FROM laptops WHERE serialNumber = $serialNumber;";
#		$resultSet = $this->mDb->Execute($queryOne);
#		if(!$resultSet->fields)
#		{
#			print("Error Deleting Data from laptops</br>");
#			return false;
#		}
#		else 
#		{
#			print("Data Deletion from laptops Complete");
#			return true;
#		}
#		$queryTwo = "DELETE FROM equipmentTable WHERE serialNumber = $serialNumber;";
#		$resultSetTwo = $this->mDb->Execute($queryTwo);
#		if(!$resultSet->fields)
#		{
#			print("Equipment Deletion - Manager Error</br>");
#			return false;
#		}
#		else 
#		{
#			print("Equipment Deletion - Manager OK</br>");
#		}
#	}
	public function mgrGetAllEquipment()
	{
		$tempEquipment = new Equipment();
		$resultArray = array();
		$query = "SELECT * FROM equipmentTable ORDER BY serialNumber ASC";
		$resultSet = $this->mDb->Execute($query);
		if(!resultSet)
		{
			print($this->mDb->errorMsg());
		}
		else 
		{
			while(!$resultSet->EOF) 
			{
				$tempEquipment = new Equipment();
				$tempEquipment->buildEquipment($resultSet->fields); 
				//Make $tempEquipment an object of the correct type (cadet, instructor, person)
				$tempEquipment = $tempEquipment->determineRole();
				$tempEquipment->search();			
				$resultArray[] = $tempEquipment;
				$resultSet->MoveNext();
			}
		}
		return $resultArray;
	}			
}
?>