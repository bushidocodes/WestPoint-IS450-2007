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
		$query = "SELECT * FROM personTable WHERE userID = " . $this->mDb->qStr($id);
		$resultSet = $this->mDb->Execute($query);
		if(!$resultSet || !$resultSet->fields)
		{
			if(session_status() === PHP_SESSION_NONE) session_start();
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
		$resultArray = array();
		$query = "SELECT * FROM personTable WHERE lastName = " . $this->mDb->qStr($name);
		$resultSet = $this->mDb->Execute($query);
		if(!$resultSet)
		{
			if(session_status() === PHP_SESSION_NONE) session_start();
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
		$query = "SELECT * FROM instructorTable WHERE userID = " . $this->mDb->qStr($instructor->getUserID());
		$result = $this->mDb->Execute($query);
		if($result && $result->fields)
		{
			$instructor->setCourse($result->fields['course']);
			$instructor->setPhoneNum($result->fields['phoneNum']);
		}
		return $instructor;
	}

	public function mgrGetCadet($cadet)
	{
		//Now add the attributes that are specific to a cadet
		$query = "SELECT * FROM cadetTable WHERE userID = " . $this->mDb->qStr($cadet->getUserID());
		$result = $this->mDb->Execute($query);
		if($result && $result->fields)
		{
			$cadet->setInstructor($result->fields['instructor']);
			$cadet->setPhoneNum($result->fields['phoneNum']);
			$cadet->setCompany($result->fields['company']);
			$cadet->setYear($result->fields['year']);
		}
		return $cadet;
	}


	public function mgrGetRoles()
	{
		$rolesArray = array();
		$query = "SELECT DISTINCT role FROM personTable ORDER BY role ASC";
		$resultSet = $this->mDb->Execute($query);
		if($resultSet)
		{
			while(!$resultSet->EOF)
			{
				$rolesArray[] = $resultSet->fields['role'];
				$resultSet->MoveNext();
			}
		}
		return $rolesArray;
	}

	public function mgrGetDepartments()
	{
		$departmentsArray = array();
		$query = "SELECT department FROM departmentTable ORDER BY department ASC";
		$resultSet = $this->mDb->Execute($query);
		if($resultSet)
		{
			while(!$resultSet->EOF)
			{
				$departmentsArray[] = $resultSet->fields['department'];
				$resultSet->MoveNext();
			}
		}
		return $departmentsArray;
	}

	public function mgrGetAllPeople()
	{
		$tempPerson = new Person();
		$resultArray = array();
		$query = "SELECT * FROM personTable ORDER BY lastName ASC";
		$resultSet = $this->mDb->Execute($query);
		if($resultSet)
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

	public function mgrAddPerson($userID,$lastName,$firstName,$email,$department,$phoneNumber,$role)
	{
		$query = "INSERT INTO personTable (userID,lastName,firstName,email,department,phoneNumber,role)
			VALUES (" . $this->mDb->qStr($userID) . "," . $this->mDb->qStr($lastName) . "," .
			$this->mDb->qStr($firstName) . "," . $this->mDb->qStr($email) . "," .
			$this->mDb->qStr($department) . "," . (int)$phoneNumber . "," . $this->mDb->qStr($role) . ")";
		$resultSet = $this->mDb->Execute($query);
		if(!$resultSet)
		{
			if(session_status() === PHP_SESSION_NONE) session_start();
			$_SESSION['ERROR'] = $this->mDb->errorMsg();
			return false;
		}
		return true;
	}

	public function mgrModifyPerson($userID,$lastName,$firstName,$email,$department,$phoneNumber,$role)
	{
		$query = "UPDATE personTable
			SET lastName = " . $this->mDb->qStr($lastName) . ", firstName = " . $this->mDb->qStr($firstName) . ",
				email = " . $this->mDb->qStr($email) . ", department = " . $this->mDb->qStr($department) . ",
				phoneNumber = " . (int)$phoneNumber . ", role = " . $this->mDb->qStr($role) . "
			WHERE userID = " . $this->mDb->qStr($userID);
		$resultSet = $this->mDb->Execute($query);
		if(!$resultSet)
		{
			if(session_status() === PHP_SESSION_NONE) session_start();
			$_SESSION['ERROR'] = $this->mDb->errorMsg();
			return false;
		}
		return true;
	}

	public function mgrDeletePerson($userID)
	{
		//Deletes in cadetTable, instructorTable, authenticationTable and
		//submitReservationTable cascade via foreign keys
		$query = "DELETE FROM personTable WHERE userID = " . $this->mDb->qStr($userID);
		$resultSet = $this->mDb->Execute($query);
		if(!$resultSet)
		{
			if(session_status() === PHP_SESSION_NONE) session_start();
			$_SESSION['ERROR'] = $this->mDb->errorMsg();
			return false;
		}
		return true;
	}

	public function mgrAddCadet($userID,$instructor,$phoneNum,$company,$year)
	{
		$query = "INSERT INTO cadetTable (userID,instructor,phoneNum,company,year)
			VALUES (" . $this->mDb->qStr($userID) . "," . $this->mDb->qStr($instructor) . "," .
			$this->mDb->qStr($phoneNum) . "," . $this->mDb->qStr($company) . "," . $this->mDb->qStr($year) . ")";
		return $this->mDb->Execute($query) ? true : false;
	}

	public function mgrAddInstructor($userID,$course,$phoneNum)
	{
		$query = "INSERT INTO instructorTable (userID,course,phoneNum)
			VALUES (" . $this->mDb->qStr($userID) . "," . $this->mDb->qStr($course) . "," .
			$this->mDb->qStr($phoneNum) . ")";
		return $this->mDb->Execute($query) ? true : false;
	}

	public function mgrDeleteCadet($userID)
	{
		$query = "DELETE FROM cadetTable WHERE userID = " . $this->mDb->qStr($userID);
		return $this->mDb->Execute($query) ? true : false;
	}

	public function mgrDeleteInstructor($userID)
	{
		$query = "DELETE FROM instructorTable WHERE userID = " . $this->mDb->qStr($userID);
		return $this->mDb->Execute($query) ? true : false;
	}

}


?>
