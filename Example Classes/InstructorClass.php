
<?php
require_once("PersonClass.php");

class Instructor extends Person 

{

	private $course;
	private $phoneNum;
	
	function __construct($person=null)
	{
		parent::__construct($person);
		$this->course = null;
		$this->phoneNum = null;
	}
	
	function getCourse() { return $this->course; }
	function setCourse($course) {$this->course = $course;}
	
	function getPhoneNum() {return $this->phoneNum;}
	function setPhoneNum($phone) {$this->phoneNum = $phone;}
	
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
		print("<td>".$this->getPhoneNum() . "</td><td>". $this->getCourse() . 
								"</td><td> NA</td><td>NA</td></tr>");	
		if($displayOne)
		{								
			print("</tbody> </table>");
		}
	}	
	
	public function search()
	{
		return $this->mgr->mgrGetInstructor($this);	
	}
		
}

?>