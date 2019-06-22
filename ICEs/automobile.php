<?php

	class Automobile
	{
		private $engineSize;
		private $fuelTank;
		
		public function __construct()
		{
			$this->engineSize = 0;
			$this->fuelTank = 0;
		}
		
		public function setFuelTank($size)
		{
			$this->fuelTank = $size;
		}
		
		public function getFuelTank()
		
		{
			return $this->fuelTank;
		}
		
	}
?>