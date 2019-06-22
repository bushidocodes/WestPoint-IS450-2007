<?php
# Refers to the file with the parent class.
	require_once("Equipment.php");

# Define a Laptop class that inherits from Equipment
	class Laptop extends Equipment {
		private $image;

	# Define a constructor for the Laptop class.
	function __construct($equipment = null)
	{
		parent::__construct($equipment);
		$this->image = null;
	}
	
	# Define a setter for the private $image member.
		public function setImage($image){$this->image = $image;}
	# Define a setter for the private $image member.
		public function getImage(){return $this->image;}
	# Define the buildLaptop function.
		public function buildLaptop($arrayOfValues)
			{
				$this->setSerialNumber($arrayOfValues['serialNumber']);
				$this->setAvailability($arrayOfValues['availability']);
				$this->setDateAdded($arrayOfValues['dateAdded']);
				$this->setWorkingStatus($arrayOfValues['workingStatus']);
				$this->setRole($arrayOfValues['role']);
				$this->setImage($arrayOfValues['image']);
				return $this;
			}
	} # End Laptop class
?>