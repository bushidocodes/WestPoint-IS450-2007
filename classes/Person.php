<?php
require_once("PersonManager.php");
require_once("Instructor.php");
require_once("Cadet.php");

class Person
{

	protected  $userID;
	protected  $lastName;
	protected  $firstName;
	protected  $role;
	protected  $email;
	protected  $department;
	protected  $phoneNumber;
	protected  $password;
	protected  $mgr;  //Must be protected so that child classes can inherit

	function __construct($person = null)
	{
		if($person)
		{
			$this->setUserID($person->getUserID());
			$this->setLastName($person->getLastName());
			$this->setFirstName($person->getFirstName());
			$this->setRole($person->getRole());
			$this->setEmail($person->getEmail());
			$this->setDepartment($person->getDepartment());
			$this->setPhoneNumber($person->getPhoneNumber());
		}
		else
		{
			$this->userID = null;
			$this->lastName = null;
			$this->firstName = null;
			$this->role = null;
			$this->email = null;
			$this->department = null;
			$this->phoneNumber = null;
		}
		$this->password = null;
		$this->mgr = new PersonManager();

	}

	function getUserID() { return $this->userID; }
	function setUserID($id) {$this->userID = $id;}

	function getLastName() {return $this->lastName;}
	function setLastName($name) {$this->lastName = $name;}

	function getFirstName() {return $this->firstName;}
	function setFirstName($name) {$this->firstName = $name;}

	function getRole () {return $this->role;}
	function setRole($role) {$this->role = $role;}

	function getPassword () {return $this->password;}
	function setPassword($pw) {$this->password = $pw;}

	function getEmail() {return $this->email;}
	function setEmail($mail) {$this->email = $mail;}

	function getDepartment() {return $this->department;}
	function setDepartment($department) {$this->department = $department;}

	function getPhoneNumber() {return $this->phoneNumber;}
	function setPhoneNumber($phoneNumber) {$this->phoneNumber = $phoneNumber;}

	function searchPersonByID($id)
	{
		$user = new Person();
		$user = $this->mgr->mgrSearchPersonByID($id);
		if($user)
		{
			$user = $user->determineRole();
			$user = $user->search();
		}
		return $user;
	}

	/* DetermineRole()
	 * In:  Accepts an object of type person
	 * Out: Returns an object of the correct type based on the role the
	 *		person has in the system
	 *
	 * This code must change if roles are added to the system, but this
	 *	is the only place that the code must be modified
	 */

	public function determineRole()
	{
		switch ($this->getRole())
		{
			case "instructor":
				$user = new Instructor($this);
				break;
			case "cadet":
				$user = new Cadet($this);
				break;
			default:
				return $this;
				break;
		}
		return $user;
	}

	function searchPersonByLastName($name)
	{
		$userArray = array();
		$userArray = $this->mgr->mgrSearchPersonByLastName($name);
		return $userArray;
	}

	/* search()
	 * This method returns the current person object
	 * It is needed to support polymorphism - subclasses will actually
	 * implement different functionality for the search
	 */
	public function search()
	{
		return $this;
	}

	function getAllPeople()
	{
		$userArray = array();
		$userArray = $this->mgr->mgrGetAllPeople();
		return $userArray;
	}

	/* addPerson()
	 * Inserts a row in personTable and, depending on $role, a detail row
	 * in cadetTable or instructorTable.  $details is an associative array
	 * with the role-specific fields:
	 *   cadet:      instructor, phoneNum, company, year
	 *   instructor: course, phoneNum
	 */
	function addPerson($userID,$lastName,$firstName,$email,$department,$phoneNumber,$role,$details = array())
	{
		$result = $this->mgr->mgrAddPerson($userID,$lastName,$firstName,$email,$department,$phoneNumber,$role);
		if(!$result)
			return false;
		if($role == 'cadet')
			return $this->mgr->mgrAddCadet($userID,
				$details['instructor'] ?? '', $details['phoneNum'] ?? '',
				$details['company'] ?? '', $details['year'] ?? '');
		if($role == 'instructor')
			return $this->mgr->mgrAddInstructor($userID,
				$details['course'] ?? '', $details['phoneNum'] ?? '');
		return true;
	}

	/* modifyPerson()
	 * Updates personTable and rebuilds the role-specific detail row so a
	 * role change (e.g. cadet promoted to instructor) stays consistent.
	 */
	function modifyPerson($userID,$lastName,$firstName,$email,$department,$phoneNumber,$role,$details = array())
	{
		$result = $this->mgr->mgrModifyPerson($userID,$lastName,$firstName,$email,$department,$phoneNumber,$role);
		if(!$result)
			return false;
		$this->mgr->mgrDeleteCadet($userID);
		$this->mgr->mgrDeleteInstructor($userID);
		if($role == 'cadet')
			return $this->mgr->mgrAddCadet($userID,
				$details['instructor'] ?? '', $details['phoneNum'] ?? '',
				$details['company'] ?? '', $details['year'] ?? '');
		if($role == 'instructor')
			return $this->mgr->mgrAddInstructor($userID,
				$details['course'] ?? '', $details['phoneNum'] ?? '');
		return true;
	}

	function deletePerson($userID)
	{
		return $this->mgr->mgrDeletePerson($userID);
	}

	function printPersonTable($arrayOfPeople, $addedPersonID = null)
	{

		print("<table border = '1' id='smallTable'>
			<thead>
				<tr> <th> UserID </th> <th> Last Name </th> <th> First Name </th> <th>E-Mail</th>
						<th>Phone</th><th>Course</th>
						<th>Company</th><th>Year</th><th>Actions</th></tr>
			</thead>
			<tbody>");
		foreach ($arrayOfPeople as $person)
		{
			if ($addedPersonID == $person->getUserID())
				$color =  "#FFFF00";
			else
				$color = "";
			//This is polymorphism!!!!  Regardless of the type of person (cadet, instructor, admin)
			//	The person will display the proper attributes.  The display() function is defined
			//	for each class
			$person->display($color, false);
		}
		print("</tbody> </table>");
	}

	protected function printActionCell()
	{
		$id = urlencode($this->getUserID());
		print("<td><a href='/app/people/edit.php?id=" . $id . "'>Edit</a>
			<form method='post' action='/app/people/delete.php' style='display:inline'
				onsubmit=\"return confirm('Delete " . htmlspecialchars($this->getUserID(), ENT_QUOTES) . "?');\">
				<input type='hidden' name='userID' value='" . htmlspecialchars($this->getUserID(), ENT_QUOTES) . "'/>
				<input type='submit' value='Delete'/>
			</form></td>");
	}

	public function display($color=null, $displayOne = true)
	{
		if($displayOne)
		{
			print("<table border = '1'>
				<thead>
					<tr> <th> UserID </th> <th> Last Name </th> <th> First Name </th> <th>E-Mail</th>
							<th>Phone</th><th>Course</th>
							<th>Company</th><th>Year</th><th>Actions</th></tr>
				</thead>
				<tbody>");
		}
		print("<tr bgcolor = '" . $color . "'> <td>".$this->getUserID(). "</td><td>". $this->getLastName() . "</td><td>" .
				$this->getFirstName() . "</td>");
		print("<td><a href='mailto:". $this->getEmail() . "'>" . $this->getEmail() . "</a> </td>");
		print("<td>NA</td><td>NA</td><td>NA</td><td>NA</td>");
		$this->printActionCell();
		print("</tr>");
		if($displayOne)
		{
			print("</tbody> </table>");
		}

	}

	protected function validatePerson($person)
	{
		if($person->getUserID() == null)
			return false;
		if($person->getLastName() == null)
			return false;
		if($person->getFirstName() == null)
			return false;
		if($person->getEmail() == null)
			return false;
		if($person->getRole() == null)
			return false;
		return true;
	}

	public function getRoles()
	{
		return $this->mgr->mgrGetRoles();
	}

	public function getDepartments()
	{
		return $this->mgr->mgrGetDepartments();
	}

	public function buildPerson($arrayOfValues)
	{
		$this->setUserID($arrayOfValues['userID']);
		$this->setFirstName($arrayOfValues['firstName']);
		$this->setLastName($arrayOfValues['lastName']);
		$this->setRole($arrayOfValues['role']);
		$this->setEmail($arrayOfValues['email']);
		$this->setDepartment($arrayOfValues['department'] ?? null);
		$this->setPhoneNumber($arrayOfValues['phoneNumber'] ?? null);
		return $this;
	}


}

?>
