<?php
# Refers to the file being tested
	require_once("Laptop.php");
	
# Creates a new laptop called $thing	
	$thing = new Laptop();
	
# Sets $thing's serialNumber to 23
	$thing->setSerialNumber(23);
	
# Tests the serial number getter and setter	
	if($thing->getSerialNumber() == 23)
		print("Passed: Equipment/Laptop->get/setSerialNumber<br/>");
	else 	
		print("<b>Failed: </b> Equipment/Laptop->get/setSerialNumber<br/>");

# Sets $thing's availability to 1	
	$thing->setAvailability(1);
	
# Tests the availability getter and setter	
	if($thing->getAvailability() == 1)
		print("Passed: Equipment/Laptop->get/setAvailability<br/>");
	else 	
		print("<b>Failed: </b> Equipment/Laptop->get/setAvailability<br/>");
		
# Sets $thing's date added to '1985-12-1'
	$thing->setDateAdded('1985-12-1');
	
# Tests the date added getter and setter	
	if($thing->getDateAdded() == '1985-12-1')
		print("Passed: Equipment/Laptop->get/setDateAdded<br/>");
	else 	
		print("<b>Failed: </b> Equipment/Laptop->get/setDateAdded<br/>");	
		
# Sets $thing's working status to 'Does not function'	
	$thing->setWorkingStatus('Does not function');
	
# Tests the working status getter and setter		
	if($thing->getWorkingStatus() == 'Does not function')
		print("Passed: Equipment/Laptop->get/setWorkingStatus<br/>");
	else 	
		print("<b>Failed: </b> Equipment/Laptop->get/setWorkingStatus<br/>");

# Sets $thing's image to 'Windows 2000'
	$thing->setImage('Windows 2000');

# Tests the image getter and setter
	if($thing->getImage() == 'Windows 2000')
		print("Passed: Laptop->get/setImage</br>");
	else 	
		print("<b>Failed: </b> Laptop->get/setImage<br/>");

?>