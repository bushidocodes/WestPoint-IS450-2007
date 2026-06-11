<?php
	require_once(dirname(__FILE__) . "/../../classes/Person.php");

	$pageTitle = "Edit Person";

	//Handle the form submission before any output so we can redirect
	if($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		if(session_status() === PHP_SESSION_NONE) session_start();
		$person = new Person();
		$userID = trim($_POST['userID'] ?? '');
		$role = $_POST['role'] ?? '';
		$details = array(
			'instructor' => trim($_POST['cadetInstructor'] ?? ''),
			'phoneNum' => trim($_POST[$role === 'instructor' ? 'detailPhoneInstructor' : 'detailPhone'] ?? ''),
			'company' => trim($_POST['company'] ?? ''),
			'year' => trim($_POST['year'] ?? ''),
			'course' => trim($_POST['course'] ?? ''),
		);
		$result = $person->modifyPerson(
			$userID,
			trim($_POST['lastName'] ?? ''),
			trim($_POST['firstName'] ?? ''),
			trim($_POST['email'] ?? ''),
			$_POST['department'] ?? '',
			trim($_POST['phoneNumber'] ?? '0'),
			$role,
			$details);
		if($result)
		{
			header("Location: index.php?added=" . urlencode($userID) .
				"&message=" . urlencode("Updated " . $userID));
			exit;
		}
		$errorMessage = $_SESSION['ERROR'] ?? 'Could not update the person';
	}

	require_once(dirname(__FILE__) . "/../includes/header.php");

	$id = $_GET['id'] ?? ($_POST['userID'] ?? '');
	$personForForm = new Person();
	$existing = $id !== '' ? $personForForm->searchPersonByID($id) : false;
	$departments = $personForForm->getDepartments();
?>
	<h2>Edit Person</h2>
<?php
	if(isset($errorMessage))
		print("<div class='error'>" . htmlspecialchars($errorMessage) . "</div>");

	if(!$existing)
	{
		print("<div class='error'>" . htmlspecialchars($_SESSION['ERROR'] ?? 'The person does not exist') . "</div>");
		require_once(dirname(__FILE__) . "/../includes/footer.php");
		exit;
	}

	$existingRole = $existing->getRole();
	$cadetInstructor = is_a($existing, 'Cadet') ? $existing->getInstructor() : '';
	$company = is_a($existing, 'Cadet') ? $existing->getCompany() : '';
	$year = is_a($existing, 'Cadet') ? $existing->getYear() : '';
	$course = is_a($existing, 'Instructor') ? $existing->getCourse() : '';
	$detailPhone = method_exists($existing, 'getPhoneNum') ? $existing->getPhoneNum() : '';
?>
	<form class="stacked" method="post" action="edit.php">
		<label>User ID</label>
		<input type="text" name="userID" readonly value="<?php print(htmlspecialchars($existing->getUserID())); ?>"/>
		<label>Last Name</label>
		<input type="text" name="lastName" value="<?php print(htmlspecialchars($existing->getLastName())); ?>"/>
		<label>First Name</label>
		<input type="text" name="firstName" value="<?php print(htmlspecialchars($existing->getFirstName())); ?>"/>
		<label>E-Mail</label>
		<input type="text" name="email" value="<?php print(htmlspecialchars($existing->getEmail())); ?>"/>
		<label>Department</label>
		<select name="department">
<?php
	foreach($departments as $department)
	{
		$selected = ($existing->getDepartment() == $department) ? 'selected' : '';
		print("<option value='" . htmlspecialchars($department, ENT_QUOTES) . "' $selected>" .
			htmlspecialchars($department) . "</option>");
	}
?>
		</select>
		<label>Phone Number</label>
		<input type="text" name="phoneNumber" value="<?php print(htmlspecialchars($existing->getPhoneNumber() ?? '')); ?>"/>
		<label>Role</label>
		<select name="role" onchange="showDetails(this.value)">
			<option value="cadet" <?php if($existingRole == 'cadet') print('selected'); ?>>cadet</option>
			<option value="instructor" <?php if($existingRole == 'instructor') print('selected'); ?>>instructor</option>
			<option value="admin" <?php if($existingRole != 'cadet' && $existingRole != 'instructor') print('selected'); ?>>admin</option>
		</select>

		<fieldset id="cadetFields" <?php if($existingRole != 'cadet') print("style='display:none'"); ?>>
			<legend>Cadet details</legend>
			<label>Instructor (User ID)</label>
			<input type="text" name="cadetInstructor" value="<?php print(htmlspecialchars($cadetInstructor ?? '')); ?>"/>
			<label>Company</label>
			<input type="text" name="company" value="<?php print(htmlspecialchars($company ?? '')); ?>"/>
			<label>Year</label>
			<input type="text" name="year" maxlength="4" value="<?php print(htmlspecialchars($year ?? '')); ?>"/>
			<label>Phone</label>
			<input type="text" name="detailPhone" value="<?php print(htmlspecialchars($existingRole == 'cadet' ? ($detailPhone ?? '') : '')); ?>"/>
		</fieldset>

		<fieldset id="instructorFields" <?php if($existingRole != 'instructor') print("style='display:none'"); ?>>
			<legend>Instructor details</legend>
			<label>Course</label>
			<input type="text" name="course" value="<?php print(htmlspecialchars($course ?? '')); ?>"/>
			<label>Phone</label>
			<input type="text" name="detailPhoneInstructor" value="<?php print(htmlspecialchars($existingRole == 'instructor' ? ($detailPhone ?? '') : '')); ?>"/>
		</fieldset>

		<input type="submit" value="Save Changes"/>
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
