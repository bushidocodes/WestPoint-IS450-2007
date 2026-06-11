<?php
	require_once(dirname(__FILE__) . "/../../classes/Person.php");

	$pageTitle = "Add Person";

	//Handle the form submission before any output so we can redirect
	if($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		if(session_status() === PHP_SESSION_NONE) session_start();
		$person = new Person();
		$role = $_POST['role'] ?? '';
		$details = array(
			'instructor' => trim($_POST['cadetInstructor'] ?? ''),
			'phoneNum' => trim($_POST[$role === 'instructor' ? 'detailPhoneInstructor' : 'detailPhone'] ?? ''),
			'company' => trim($_POST['company'] ?? ''),
			'year' => trim($_POST['year'] ?? ''),
			'course' => trim($_POST['course'] ?? ''),
		);
		$userID = trim($_POST['userID'] ?? '');
		$result = false;
		if($userID !== '')
		{
			$result = $person->addPerson(
				$userID,
				trim($_POST['lastName'] ?? ''),
				trim($_POST['firstName'] ?? ''),
				trim($_POST['email'] ?? ''),
				$_POST['department'] ?? '',
				trim($_POST['phoneNumber'] ?? '0'),
				$role,
				$details);
		}
		else
		{
			$_SESSION['ERROR'] = "User ID is required";
		}
		if($result)
		{
			header("Location: index.php?added=" . urlencode($userID) .
				"&message=" . urlencode("Added " . $userID));
			exit;
		}
		$errorMessage = $_SESSION['ERROR'] ?? 'Could not add the person';
	}

	require_once(dirname(__FILE__) . "/../includes/header.php");

	$personForForm = new Person();
	$departments = $personForForm->getDepartments();
?>
	<h2>Add Person</h2>
<?php
	if(isset($errorMessage))
		print("<div class='error'>" . htmlspecialchars($errorMessage) . "</div>");
?>
	<form class="stacked" method="post" action="add.php">
		<label>User ID</label>
		<input type="text" name="userID" maxlength="6" value="<?php print(htmlspecialchars($_POST['userID'] ?? '')); ?>"/>
		<label>Last Name</label>
		<input type="text" name="lastName" value="<?php print(htmlspecialchars($_POST['lastName'] ?? '')); ?>"/>
		<label>First Name</label>
		<input type="text" name="firstName" value="<?php print(htmlspecialchars($_POST['firstName'] ?? '')); ?>"/>
		<label>E-Mail</label>
		<input type="text" name="email" value="<?php print(htmlspecialchars($_POST['email'] ?? '')); ?>"/>
		<label>Department</label>
		<select name="department">
<?php
	foreach($departments as $department)
	{
		$selected = (($_POST['department'] ?? '') == $department) ? 'selected' : '';
		print("<option value='" . htmlspecialchars($department, ENT_QUOTES) . "' $selected>" .
			htmlspecialchars($department) . "</option>");
	}
?>
		</select>
		<label>Phone Number</label>
		<input type="text" name="phoneNumber" value="<?php print(htmlspecialchars($_POST['phoneNumber'] ?? '')); ?>"/>
		<label>Role</label>
		<select name="role" onchange="showDetails(this.value)">
			<option value="cadet">cadet</option>
			<option value="instructor">instructor</option>
			<option value="admin">admin</option>
		</select>

		<fieldset id="cadetFields">
			<legend>Cadet details</legend>
			<label>Instructor (User ID)</label>
			<input type="text" name="cadetInstructor"/>
			<label>Company</label>
			<input type="text" name="company"/>
			<label>Year</label>
			<input type="text" name="year" maxlength="4"/>
			<label>Phone</label>
			<input type="text" name="detailPhone"/>
		</fieldset>

		<fieldset id="instructorFields" style="display:none">
			<legend>Instructor details</legend>
			<label>Course</label>
			<input type="text" name="course"/>
			<label>Phone</label>
			<input type="text" name="detailPhoneInstructor"/>
		</fieldset>

		<input type="submit" value="Add Person"/>
	</form>
	<script>
		function showDetails(role) {
			document.getElementById('cadetFields').style.display = (role === 'cadet') ? '' : 'none';
			document.getElementById('instructorFields').style.display = (role === 'instructor') ? '' : 'none';
		}
	</script>
<?php
	require_once(dirname(__FILE__) . "/../includes/footer.php");
?>
