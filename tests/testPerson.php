<?php
	require_once(dirname(__FILE__) . "/../classes/Person.php");

	$testPerson = new Person();

	//*************************************************************
	//**** searchPersonByID (cadet)
	$tempPerson = $testPerson->searchPersonByID("x11111");

	if(!$tempPerson)
	{
		print("FAILED - searchPersonByID (cadet)<br/>");
	}
	elseif ($tempPerson->getLastName() == 'Smith' && is_a($tempPerson, 'Cadet'))
	{
		print("PASSED - searchPersonByID (cadet)<br/>");
	}
	else
	{
		print("FAILED - searchPersonByID (cadet)<br/>");
	}

	//*************************************************************
	//**** searchPersonByID (instructor)
	$tempInstructor = $testPerson->searchPersonByID("g11111");

	if(!$tempInstructor)
	{
		print("FAILED - searchPersonByID (instructor)<br/>");
	}
	elseif ($tempInstructor->getLastName() == 'Hooah' &&
			$tempInstructor->getPhoneNum() == '5550001')
	{
		print("PASSED - searchPersonByID (instructor)<br/>");
	}
	else
	{
		print("FAILED - searchPersonByID (instructor)<br/>");
	}

	//*************************************************************
	//**** searchPersonByLastName
	$tempArray = $testPerson->searchPersonByLastName("Williams");

	if(!$tempArray)
	{
		print("FAILED - searchPersonByLastName<br/>");
	}
	elseif ($tempArray[0]->getLastName() == 'Williams')
	{
		print("PASSED - searchPersonByLastName<br/>");
	}
	else
	{
		print("FAILED - searchPersonByLastName<br/>");
	}

	//*************************************************************
	//**** addPerson (cadet) round trip
	$result = $testPerson->addPerson('t99999','Test','Tess','t99999@usma.edu','USCC','5559999','cadet',
		array('instructor' => 'g11111', 'phoneNum' => '5559999', 'company' => 'D4', 'year' => '2010'));
	if(!$result)
		print("FAILED - addPerson (cadet)<br/>");
	else
		print("PASSED - addPerson (cadet)<br/>");

	$tempPerson = $testPerson->searchPersonByID('t99999');
	if(!$tempPerson || $tempPerson->getCompany() != 'D4')
		print("FAILED - searchPersonByID after addPerson<br/>");
	else
		print("PASSED - searchPersonByID after addPerson<br/>");

	//*************************************************************
	//**** modifyPerson - promote the test cadet to instructor
	$result = $testPerson->modifyPerson('t99999','Test','Tess','t99999@usma.edu','EECS','5559999','instructor',
		array('course' => 'IS450', 'phoneNum' => '5559999'));
	$tempPerson = $testPerson->searchPersonByID('t99999');
	if(!$result || !is_a($tempPerson, 'Instructor') || $tempPerson->getCourse() != 'IS450')
		print("FAILED - modifyPerson (role change)<br/>");
	else
		print("PASSED - modifyPerson (role change)<br/>");

	//*************************************************************
	//**** deletePerson (cleanup)
	$result = $testPerson->deletePerson('t99999');
	if(!$result || $testPerson->searchPersonByID('t99999'))
		print("FAILED - deletePerson<br/>");
	else
		print("PASSED - deletePerson<br/>");

	//*************************************************************
	//**** getAllPeople
	$testArray = $testPerson->getAllPeople();

	if(!$testArray)
	{
		print("FAILED - getAllPeople<br/>");
	}
	else
	{
		print("PASSED - getAllPeople<br/>");
	}

	$testPerson->printPersonTable($testArray);

?>
