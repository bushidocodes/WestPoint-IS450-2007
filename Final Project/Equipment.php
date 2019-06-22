<?php
require_once("EquipmentManager.php");
require_once("Laptop.php");

# Define a base Equipment class.
	class Equipment	{
		protected  $serialNumber;
		protected  $availability;
		protected  $dateAdded;
		protected  $workingStatus;
		protected  $role;
		protected  $mgr;

	# Define a constructor for the base Equipment class.
		public function __construct($equipment = null)
		{
			if($equipment)
			{
				$this->setSerialNumber($equipment->getSerialNumber());
				$this->setAvailability($equipment->getAvailability());	
				$this->setDateAdded($equipment->getDateAdded());
				$this->setWorkingStatus($equipment->getWorkingStatus());
				$this->setRole($equipment->getRole());
			}	
			else 
			{	
				$this->serialNumber = null;
				$this->availability = null;
				$this->dateAdded = null;
				$this->workingStatus = null;
				$this->role = null;			
			}
			$this->mgr = new EquipmentManager();				
		}
	# Define a setter for the private $serialNumber member.
		function setSerialNumber($num){$this->serialNumber = $num;}
	# Define a getter for the private $serialNumber member.
		function getSerialNumber(){return $this->serialNumber;}
	# Define a setter for the private $availability member.		
		function setAvailability($num){$this->availability = $num;}
	# Define a getter for the private $availability member.		
		function getAvailability(){return $this->availability;}
	# Define a setter for the private $dateAdded member.		
		function setDateAdded($date){$this->dateAdded = $date;}
	# Define a getter for the private $dateAdded member.		
		function getDateAdded(){return $this->dateAdded;}
	# Define a setter for the private $workingStatus member.		
		function setWorkingStatus($status){$this->workingStatus = $status;}	
	# Define a getter for the private $workingStatus member.		
		function getWorkingStatus(){return $this->workingStatus;}
	# Define a getter for the private $role member.		
		function getRole () {return $this->role;}
	# Define a setter for the private $role member.		
		function setRole($role) {$this->role = $role;}	
	# Define the makeArray function.
	#	function makeArray(	
	# Define the buildEquipment function.
		function buildEquipment($arrayOfValues)
			{
				$this->setSerialNumber($arrayOfValues['serialNumber']);
				$this->setAvailability($arrayOfValues['availability']);
				$this->setDateAdded($arrayOfValues['dateAdded']);
				$this->setWorkingStatus($arrayOfValues['workingStatus']);
				$this->setRole($arrayOfValues['role']);
				return $this;
			}	
	# Define the validateEquipment function
		protected function validateEquipment($equip)
			{
				if($equip->getSerialNumber() == null)
					return false;
				if($equip->getAvailability() == null)
					return false;
				if($equip->getDateAdded() == null)
					return false;
				if($equip->getWorkingStatus() == null)
					return false;
				return true;
	}
	/* DetermineRole()
	 * In:  Accepts an object of type equipment
	 * Out: Returns an object of the correct type based on the role the 
	 *		equipment has in the system
	 *
	 * This code must change if roles are added to the system, but this
	 *	is the only place that the code must be modified
	 */
		public function determineRole()
			{
				switch ($this->getRole())
				{
					case "laptop":
						$equipment = new Laptop($this);
						break;
					default:
						return $this;
						break;	
				}	
				return $equipment;	
			}
	/* search()
	 * This method returns the current equipment object
	 * It is needed to support polymorphism - subclasses will actually
	 * implement different functionality for the search
	 */
		public function search()
		{
			return $this;	
		}
	#This function searches for equipment by serial number
		function searchForEquipmentBySerialNumber($serialNumber)
		{
			$equip = new Equipment();
			$equip = $this->mgr->mgrSearchForEquipmentBySerialNumber($serialNumber);	
			if($equip)
			{
				$equip = $equip->determineRole();
				if (is_a($equip,'Laptop'))
				{
					$equip = $this->mgr->mgrSearchForLaptopBySerialNumber($serialNumber);	
				}
				$equip = $equip->search();
			}
			return $equip;
		}
	#This function adds equipment
		function addEquipment($serialNumber,$availability,$dateAdded,$workingStatus,$role,$image)
		{
			$equip = new Equipment();
#			$equip->setSerialNumber($serialNumber);
#			$equip->setAvailability($availability);
#			$equip->setDateAdded($dateAdded);
#			$equip->setWorkingStatus($workingStatus);
#			$equip->setRole($role);
#			$equip->setImage($image);
#			$equip = $this->buildEquipment($equip);	
#			if ($this->validateEquipment($equip) )
#			{
#				$equip = $equip->determineRole();
#				$equip = $equip->search();
#			} $serialNumber,$availability,$dateAdded,$workingStatus,$role
			$equip = $equip->determineRole();
			$equip = $equip->search();
			$equip = $this->mgr->mgrAddEquipment($serialNumber,$availability,$dateAdded,$workingStatus,$role);			
			if(!$equip)
			{
				print("Equipment Insertion - Class Error</br>");
				return false;
			}
			else 
			{
				print("Equipment Insertion - Class OK</br>");
			}
			if($role = 'laptop')
			{
				$equip = $this->mgr->mgrAddLaptop($serialNumber,$image);			
				if(!$equip)
				{
					print("Laptop Insertion - Class Error</br>");
					return false;
				}
				else 
				{
					print("Laptop Insertion - Class OK</br>");
					return true;
				}
			}
			else 
			{
			return true;
			}
		}
	#This function modifies equipment
		function modifyEquipment($oldSN,$newSN,$availability,$dateAdded,$workingStatus,$role,$image)
		{
			$equip = new Equipment();
			$equip->setSerialNumber($oldSN);
			$equip->setAvailability($availability);
			$equip->setDateAdded($dateAdded);
			$equip->setWorkingStatus($workingStatus);
			$equip->setRole($role);
			$equip = $equip->determineRole();
			$equip = $equip->search();
			if(is_a($equip,'laptop'))
			{
				$equip = $this->mgr->mgrModifyLaptop($oldSN,$image);		
			}
			$equip = $this->mgr->mgrModifyEquipment($oldSN,$newSN,$availability,$dateAdded,$workingStatus,$role);
			if(!$equip)
			{
				print("Equipment Modification - Class Error</br>");
				return false;
			}
			else 
			{
				print("Equipment Modification - Class OK</br>");
				return true;
			}
		}
	#This function deletes equipment
		function deleteEquipment($serialNumber)
		{
			$equip = new Equipment();
			$equip = $equip->determineRole();
			$equip = $equip->search();
#			UNNEEDED BECAUSE A DELETION OF EQUIPMENT CASCADES TO LAPTOP
#			if(is_a($equip,'laptop'))
#			{
#				$equip = $this->mgr->mgrDeleteLaptop($serialNumber);		
#			}
			$equip = $this->mgr->mgrDeleteEquipment($serialNumber);		
			if(!$equip)
			{
				print("Equipment Deletion - Class Error</br>");
				return false;
			}
			else 
			{
				print("Equipment Deletion - Class OK</br>");
				return true;
			}
		}
		function getAllEquipment()
		{
			$equipmentArray = array();
			$equipmentArray = $this->mgr->mgrGetAllEquipment();
			return $equipmentArray;	
		}
		function printEquipmentTable($arrayOfEquipment, $addedSerialNumber = null)
		{

			print("<table border = '1' id='smallTable'>
				<thead>
					<tr> <th> Serial Number </th> <th> Availability </th> <th> Date Added </th> <th>Working Status</th> 
							<th>Role</th><th>Image</th>
				</thead>
				<tbody>");		
			foreach ($arrayOfEquipment as $equipment)
			{
#				if ($addedSerialNumber == $equipment->getSerialNumber())
					$color =  "#FFFF00";
#				else
#					$color = "";
#				//This is polymorphism!!!!  Regardless of the type of person (cadet, instructor, admin)
#				//	The person will display the proper attributes.  The display() function is defined
#				//	for each class
				$equipment->display($color, false);
			}
			print("</tbody> </table>");
		}
		public function display($color=null, $displayOne = true)
		{
			if($displayOne)
			{
				print("<table border = '1'>
				<thead>
					<tr> <th> Serial Number </th> <th> Availability </th> <th> Date Added </th> <th>Working Status</th> 
							<th>Role</th><th>Image</th>
				</thead>
				<tbody>");		
			}
			print("<tr bgcolor = '" . $color . "'> <td>".$this->getSerialNumber(). "</td><td>". $this->getAvailability() . "</td><td>".$this->getSerialNumber(). "</td><td>".$this->getSerialNumber(). "</td><td>".$this->getSerialNumber(). "</td><td>".$this->getSerialNumber(). "</td><td>" .
				$this->getDateAdded() . "</td>");
			if($displayOne)
			{
				print("</tbody> </table>");
			}
		
	}		
	} # End Equipment class
	
?>