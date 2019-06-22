
<?php
	require_once("PersonClass.php");
	require_once("InstructorClass.php");
	require_once("CadetClass.php");
	
	$testPerson = new Person();
	$tempPerson = new Person();
	$tempInstructor = new Instructor();
	$tempCadet = new Cadet();
	$testArray = array();
	
	//*************************************************************
	//**** searchUserByID
	$tempPerson = $testPerson->searchPersonByID("x71234");
	
	if(!$tempPerson)
	{
		print("FAILED - searchUserByID (cadet)<br/>");
	}
	elseif ($tempPerson->getLastName() == 'Cadet')
	{
		print("PASSED - searchUserByID (cadet) <br/>");
	}
	else 
	{
		print("FAILED - searchUserByID (cadet)<br/>");
	}

	//*************************************************************
	//**** searchUserByID (instructor)
	$tempInstructor = $testPerson->searchPersonByID("dp0270");
	
	if(!$tempInstructor)
	{
		print("FAILED - searchUserByID (instructor)<br/>");
	}
	elseif ($tempInstructor->getLastName() == 'Stanton'  && 
			$tempInstructor->getPhoneNum() == '5580')
	{
		print("PASSED - searchUserByID (instructor)<br/>");
	}
	else 
	{
		print("FAILED - searchUserByID (instructor)<br/>");
	}		
	
	//*************************************************************
	//**** searchUserByID (admin)
	$tempPerson = $testPerson->searchPersonByID("admin");
	
	if(!$tempPerson)
	{
		print("FAILED - searchUserByID (admin)<br/>");
	}
	elseif ($tempPerson->getLastName() == 'TestAdmin') 
	{
		print("PASSED - searchUserByID (admin)<br/>");
	}
	else 
	{
		print("FAILED - searchUserByID (admin)<br/>");
	}		
	
	//*************************************************************
	//**** searchUserByLastName
	$tempArray = $testPerson->searchPersonByLastName("Cadet");
	
	if(!$tempArray)
	{
		print("FAILED - searchUserByID (cadet)<br/>");
	}
	elseif ($tempArray[0]->getLastName() == 'Cadet')
	{
		print("PASSED - searchUserByID (cadet) <br/>");
	}
	else 
	{
		print("FAILED - searchUserByID (cadet)<br/>");
	}

	//*************************************************************
	//**** searchUserByLastName (instructor)
	$tempArray = $testPerson->searchPersonByLastName("Stanton");
	
	if(!$tempArray)
	{
		print("FAILED - searchUserByID (instructor)<br/>");
	}
	elseif ($tempArray[0]->getLastName() == 'Stanton')
	{
		print("PASSED - searchUserByID (instructor)<br/>");
	}
	else 
	{
		print("FAILED - searchUserByID (instructor)<br/>");
	}		
	
	//*************************************************************
	//**** searchUserByLastName (admin)
	$tempArray = $testPerson->searchPersonByLastName("TestAdmin");
	
	if(!$tempArray)
	{
		print("FAILED - searchUserByID (admin)<br/>");
	}
	elseif ($tempArray[0]->getLastName() == 'TestAdmin') 
	{
		print("PASSED - searchUserByID (admin)<br/>");
	}
	else 
	{
		print("FAILED - searchUserByID (admin)<br/>");
	}		
	
	//*************************************************************
	//**** Get All Users
	$testArray = $testPerson->getAllPeople();
	
	if(!$testArray)
	{
		print("FAILED - getAllUsers<br/>");
	}
	else 
	{
		print("PASSED - getAllUsers <br/>");
	}
	
	$testPerson->printPersonTable($testArray);

?>
