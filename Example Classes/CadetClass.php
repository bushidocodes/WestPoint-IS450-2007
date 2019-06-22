
<?php
require_once("PersonClass.php");

class Cadet extends Person 

{

	private $instructor;
	private $phoneNum;
	private $company;
	private $year;
	
	function __construct($person = null)
	{
		parent::__construct($person);
		$this->instructor = null;
		$this->phoneNum = null;
		$this->company = null;
		$this->year = null;
	}
	
	function getCompany() { return $this->company; }
	function setCompany($co) {$this->company = $co;}
	
	function getPhoneNum() {return $this->phoneNum;}
	function setPhoneNum($phone) {$this->phoneNum = $phone;}
	
	function getInstructor() { return $this->instructor; }
	function setInstructor($ins) {$this->instructor = $ins;}
	
	function getYear() {return $this->year;}
	function setYear($class) {$this->year = $class;}
	
	public function display($color=null, $displayOne = true)
	{
		if($displayOne)
		{
			print("<table border = '1'>
				<thead>
					<tr> <th> UserID </th> <th> Last Name </th> <th> First Name </th> <th>E-Mail</th> 
							<th>Phone</th><th>Course</th>
							<th>Company</th><th>Year</th></tr> 
				</thead>
				<tbody>");		
		}
		print("<tr bgcolor = '" . $color . "'> <td>".$this->getUserID(). "</td><td>". $this->getLastName() . "</td><td>" .
				$this->getFirstName() . "</td>");
		print("<td><a href='mailto:". $this->getEmail() . "'>" . $this->getEmail() . "</a> </td>");
		print("<td>".$this->getPhoneNum() . "</td><td>NA</td>");						
		print("<td>".$this->getCompany(). "</td><td>" . $this->getYear() . "</td></tr>");
		if($displayOne)
		{		
			print("</tbody> </table>");
		}
	}	
	
	public function search()
	{
		return $this->mgr->mgrGetCadet($this);	
	}	
}

?>