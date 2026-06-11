<?php
# Refers to the file with the parent class.
	require_once("Equipment.php");

# Define a Projector class that inherits from Equipment
	class Projector extends Equipment {
		private $connector;

	# Define a constructor for the Projector class.
	function __construct($equipment = null)
	{
		parent::__construct($equipment);
		$this->connector = null;
	}

	# Define a setter for the private $connector member.
		public function setConnector($connector){$this->connector = $connector;}
	# Define a getter for the private $connector member.
		public function getConnector(){return $this->connector;}
	# The projector's Detail column is its connector type.
		protected function getDetail(){return $this->connector;}
	# Define the buildProjector function.
		public function buildProjector($arrayOfValues)
			{
				$this->setSerialNumber($arrayOfValues['serialNumber']);
				$this->setAvailability($arrayOfValues['availability']);
				$this->setDateAdded($arrayOfValues['dateAdded']);
				$this->setWorkingStatus($arrayOfValues['workingStatus']);
				$this->setRole($arrayOfValues['role']);
				$this->setConnector($arrayOfValues['connector']);
				return $this;
			}
	} # End Projector class
?>
