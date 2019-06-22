
<?php
class Automobile
{ 	//instance variables
	private $engineSize;
	private $fuelTank;
	
	//constructor
	public function __construct()
	{
		echo "... Automobile constructor executing ... <br/>";
		$this->engineSize = 0;
		$this->fuelTank = 0;
	}
	
	//accessor methods
	public function getEngineSize()
	{
		return $this->engineSize;
	}
	public function setEngineSize($size)
	{
		$this->engineSize = $size;
	}
	public function getFuelTank() 
	{
		return $this->fuelTank;
	}
	public function setFuelTank($size) 
	{
		$this->fuelTank = $size;
	}
	
	public function fillTank($cost)
	{
		return $cost * $this->fuelTank;
	}
		
	
} //end class Automobile
?>
