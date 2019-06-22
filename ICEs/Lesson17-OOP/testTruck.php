<?php
	require_once("TruckClass.php");
	
	$myTruck = new Truck();
	
	$myTruck->setTowCapacity(7000);
	if($myTruck->getTowCapacity() == 7000)	
		print("Passed: Truck->get/SetTowCapacity<br/>");
	else 
		print("<b>Failed: </b> Truck->getTowCapacity<br/>");
	
	$myTruck->setFuelTank(25);
	if($myTruck->getFuelTank() == 25)
		print("Passed: Truck->get/setFuelTank<br/>");
	else 
		print("<b>Failed: </b> Truck->get/setFuelTank <br/>");
?>