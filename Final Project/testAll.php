
	<?php
		require_once("Equipment.php");
		require_once("Laptop.php");
		require_once("EquipmentManager.php");
		
		$testLaptop = new Laptop();
		$tempLaptop = new Laptop();
		$tempEquipment = new Equipment();
		$testEquipment = new Laptop();
		$testArray = array();
		
		//*************************************************************
		//**** searchForEquipmentBySerialNumber
		$tempEquipment = $testEquipment->searchForEquipmentBySerialNumber("000111");
		
		if(!$tempEquipment)
		{
			print("FAILED - searchForEquipmentBySerialNumber (laptop/equipment)<br/>");
		}
		elseif ($tempEquipment->getRole() == 'laptop')
		{
			print("PASSED - searchForEquipmentBySerialNumber (laptop/equipment)<br/>");
		}
		else 
		{
			print("FAILED - searchForEquipmentBySerialNumber (equipment)<br/>");
		}

		//*************************************************************
		//**** addEquipment (equipment)
		$tempEquipment = $testEquipment->addEquipment("222223","0","2006-12-1","burnt bulb","laptop","Linux");

		if(!$tempEquipment)
		{
			print("FAILED - addEquipment (laptop/equipment)<br/>");
		}
		else 
		{
			print("PASSED - addEquipment (laptop/equipment)<br/>");
		}		

		//*************************************************************
		//**** modifyEquipment (equipment)
		$tempEquipment = $testEquipment->modifyEquipment("222223","222223","1","2006-12-1","no problems","laptop","Unix");
		
		if(!$tempEquipment)
		{
			print("FAILED - modifyEquipment (laptop/equipment)<br/>");
		}
		else 
		{
			print("PASSED - modifyEquipment (laptop/equipment)<br/>");
		}
				
		//*************************************************************
		//**** deleteEquipment (equipment)
		$tempEquipment = $testEquipment->deleteEquipment("222223");
		if(!$tempEquipment)
		{
			print("FAILED - deleteEquipment (laptop/equipment)<br/>");
		}
		else 
		{
			print("PASSED - deleteEquipment (laptop/equipment)<br/>");
		}		

		//*************************************************************
		//**** Get All Users
		$testArray = $testEquipment->getAllEquipment();
		
		if(!$testArray)
		{
			print("FAILED - getAllEquipment<br/>");
		}
		else 
		{
			print("PASSED - getAllEquipment <br/>");
		}
		
		$testEquipment->printEquipmentTable($testArray);
		
	?>
