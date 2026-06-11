<?php
require_once("EquipmentManager.php");
require_once("Laptop.php");
require_once("Projector.php");

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
					case "projector":
						$equipment = new Projector($this);
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
				elseif (is_a($equip,'Projector'))
				{
					$equip = $this->mgr->mgrSearchForProjectorBySerialNumber($serialNumber);
				}
				$equip = $equip->search();
			}
			return $equip;
		}
	#This function adds equipment.  $detail is the subtype attribute:
	#	the image for a laptop, the connector for a projector.
		function addEquipment($serialNumber,$availability,$dateAdded,$workingStatus,$role,$detail = null)
		{
			$result = $this->mgr->mgrAddEquipment($serialNumber,$availability,$dateAdded,$workingStatus,$role);
			if(!$result)
			{
				return false;
			}
			if($role == 'laptop')
			{
				return $this->mgr->mgrAddLaptop($serialNumber,$detail);
			}
			if($role == 'projector')
			{
				return $this->mgr->mgrAddProjector($serialNumber,$detail);
			}
			return true;
		}
	#This function modifies equipment.  The subtype detail row is rebuilt so
	#	a type change (e.g. laptop reclassified as projector) stays consistent.
		function modifyEquipment($oldSN,$newSN,$availability,$dateAdded,$workingStatus,$role,$detail = null)
		{
			$result = $this->mgr->mgrModifyEquipment($oldSN,$newSN,$availability,$dateAdded,$workingStatus,$role);
			if(!$result)
			{
				return false;
			}
			$this->mgr->mgrDeleteLaptop($newSN);
			$this->mgr->mgrDeleteProjector($newSN);
			if($role == 'laptop')
			{
				$this->mgr->mgrAddLaptop($newSN,$detail);
			}
			elseif($role == 'projector')
			{
				$this->mgr->mgrAddProjector($newSN,$detail);
			}
			return true;
		}
	#This function deletes equipment
		function deleteEquipment($serialNumber)
		{
			#	A DELETION OF EQUIPMENT CASCADES TO ITS SUBCLASS TABLES
			return $this->mgr->mgrDeleteEquipment($serialNumber);
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
							<th>Type</th><th>Detail</th><th>Actions</th></tr>
				</thead>
				<tbody>");
			foreach ($arrayOfEquipment as $equipment)
			{
				if ($addedSerialNumber == $equipment->getSerialNumber())
					$color =  "#FFFF00";
				else
					$color = "";
				//This is polymorphism!!!!  Regardless of the type of equipment (laptop, projector)
				//	the equipment will display the proper attributes.
				$equipment->display($color, false);
			}
			print("</tbody> </table>");
		}
	# Returns the subtype attribute shown in the Detail column
		protected function getDetail()
		{
			return '';
		}
		protected function printActionCell()
		{
			$sn = urlencode($this->getSerialNumber());
			print("<td><a href='/app/equipment/edit.php?sn=" . $sn . "'>Edit</a>
				<form method='post' action='/app/equipment/delete.php' style='display:inline'
					onsubmit=\"return confirm('Delete " . htmlspecialchars($this->getSerialNumber(), ENT_QUOTES) . "?');\">
					<input type='hidden' name='serialNumber' value='" . htmlspecialchars($this->getSerialNumber(), ENT_QUOTES) . "'/>
					<input type='submit' value='Delete'/>
				</form></td>");
		}
		public function display($color=null, $displayOne = true)
		{
			if($displayOne)
			{
				print("<table border = '1'>
				<thead>
					<tr> <th> Serial Number </th> <th> Availability </th> <th> Date Added </th> <th>Working Status</th>
							<th>Type</th><th>Detail</th><th>Actions</th></tr>
				</thead>
				<tbody>");
			}
			$availability = $this->getAvailability() ? "Available" : "Checked Out";
			print("<tr bgcolor = '" . $color . "'> <td>".$this->getSerialNumber(). "</td><td>". $availability . "</td><td>".$this->getDateAdded(). "</td><td>".$this->getWorkingStatus(). "</td><td>".$this->getRole(). "</td><td>" .
				$this->getDetail() . "</td>");
			$this->printActionCell();
			print("</tr>");
			if($displayOne)
			{
				print("</tbody> </table>");
			}

	}
	} # End Equipment class

?>
