<?php

	require_once("AutomobileClass.php");
	
	$myCar = new Automobile();
	if($myCar->getEngineSize() == 0)
		print("Passed:  Automobile->getEngineSize <br/>");
	else 
		print("<b>Failed: </b> Automobile->getEngineSize<br/>");
	
	$myCar->setEngineSize(4.3);
	if($myCar->getEngineSize() == 4.3)
		print("Passed: Automobile->get/setEngineSize<br/>");
	else 	
		print("<b>Failed: </b> Automobile->get/setEngineSize<br/>");
	
	$costPerGallon = 2.89;
	$myCar->setFuelTank(20);
	if($myCar->fillTank($costPerGallon) == (2.89 * 20))
		print("Passed: Automobile->fillTank<br/>");
	else 
		print("<b>Failed: </b> Automobile->fillTank<br/>");
		

	
?>