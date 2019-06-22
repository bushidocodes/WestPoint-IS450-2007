<?php
require_once("AbstractManager.php");

class PersonManager extends AbstractManager 
{
	public function __construct()
	{
		parent::__construct();	
	}
	
	
	public function mgrSearchPersonByID($id)
	{
		$tempPerson = new Person();
		$query = "SELECT * FROM personTable WHERE userID = '" . $id . "'";
		$resultSet = $this->mDb->Execute($query);
		if(!$resultSet->fields)
		{
			$_SESSION['ERROR'] = "The person does not exist";
			return false;
		}
		else 
		{
			$tempPerson->buildPerson($resultSet->fields);
			return $tempPerson;
		}
		
	}
	
	public function mgrSearchPersonByLastName($name)
	{
		$tempPerson = new Person();
		$query = "SELECT * FROM personTable WHERE lastName = '" . $name . "'";
		$resultSet = $this->mDb->Execute($query);
		if(!$resultSet)
		{
			$_SESSION['ERROR'] = $this->mDb->errorMsg();
		}
		else 
		{
			while(!$resultSet->EOF) 
			{
				$tempPerson = new Person();
				$tempPerson->buildPerson($resultSet->fields); 
				//Make $tempPerson an object of the correct type (cadet, instructor, person)
				$tempPerson = $tempPerson->determineRole();
				$tempPerson->search();			
				$resultArray[] = $tempPerson;
				$resultSet->MoveNext();
			}			
		}
		return $resultArray;
		
	}	
	
	public function mgrGetInstructor($instructor)
	{
		//Now add the attributes that are specific to an instructor
		$query = "SELECT * FROM instructorTable WHERE userID = '" . $instructor->getUserID() . "'";
		$result = $this->mDb->Execute($query);
		if(!$result)
			print($this->mDb->errorMsg());
		else 
		{
			$instructor->setCourse($result->fields['course']);
			$instructor->setPhoneNum($result->fields['phoneNum']);
		}
		return $instructor;
	}

	public function mgrGetCadet($cadet)
	{	
		//Now add the attributes that are specific to an instructor
		$query = "SELECT * FROM cadetTable WHERE userID = '" . $cadet->getUserID() . "'";
		$result = $this->mDb->Execute($query);
		if(!$result)
			print($this->mDb->errorMsg());
		else 
		{
			$cadet->setInstructor($result->fields['instructor']);
			$cadet->setPhoneNum($result->fields['phoneNum']);
			$cadet->setCompany($result->fields['company']);
			$cadet->setYear($result->fields['year']);
		}
		return $cadet;
	}	
	
	
	public function mgrGetAllPeople()
	{
		$tempPerson = new Person();
		$resultArray = array();
		$query = "SELECT * FROM personTable ORDER BY lastName ASC";
		$resultSet = $this->mDb->Execute($query);
		if(!resultSet)
		{
			print($this->mDb->errorMsg());
		}
		else 
		{
			while(!$resultSet->EOF) 
			{
				$tempPerson = new Person();
				$tempPerson->buildPerson($resultSet->fields); 
				//Make $tempPerson an object of the correct type (cadet, instructor, person)
				$tempPerson = $tempPerson->determineRole();
				$tempPerson->search();			
				$resultArray[] = $tempPerson;
				$resultSet->MoveNext();
			}
		}
		return $resultArray;
	}	
		
}


?>