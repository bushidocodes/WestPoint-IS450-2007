<?php
	require_once("AutomobileClass.php");
	class Truck extends Automobile 
	{
		private $towCapacity;
		
		public function __construct()
		{
			parent::__construct();
			echo "... Truck constructor executing ... <br/>";
			$this->towCapacity = 0;
		}
		
		public function getTowCapacity()
		{
			return $this->towCapacity;
		}
		
		public function setTowCapacity($capacity)
		{
			$this->towCapacity = $capacity;
		}
	}
?>