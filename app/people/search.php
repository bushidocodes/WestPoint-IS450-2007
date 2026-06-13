<?php
	require_once(dirname(__FILE__) . "/../includes/auth_check.php");
	require_once(dirname(__FILE__) . "/../../classes/Person.php");

	$pageTitle = "Find Person";
	require_once(dirname(__FILE__) . "/../includes/header.php");

	$searchType = $_POST['searchType'] ?? null;
	$searchTerm = trim($_POST['searchTerm'] ?? '');
?>
	<h2>Find Person</h2>
	<form class="stacked" method="post" action="search.php">
		<label>Search by</label>
		<select name="searchType">
			<option value="id" <?php if($searchType == 'id') print('selected'); ?>>User ID</option>
			<option value="lastName" <?php if($searchType == 'lastName') print('selected'); ?>>Last Name</option>
		</select>
		<label>Search for</label>
		<input type="text" name="searchTerm" value="<?php print(htmlspecialchars($searchTerm)); ?>"/>
		<br/><input type="submit" value="Search"/>
	</form>
	<br/>
<?php
	if($_SERVER['REQUEST_METHOD'] === 'POST' && $searchTerm !== '')
	{
		$person = new Person();
		if($searchType === 'id')
		{
			$result = $person->searchPersonByID($searchTerm);
			if($result)
				$result->display();
			else
				print("<div class='error'>" . htmlspecialchars($_SESSION['ERROR'] ?? 'Not found') . "</div>");
		}
		else
		{
			$resultArray = $person->searchPersonByLastName($searchTerm);
			if(count($resultArray) > 0)
				$person->printPersonTable($resultArray);
			else
				print("<div class='error'>No people found with last name '" . htmlspecialchars($searchTerm) . "'</div>");
		}
	}
	elseif($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		print("<div class='error'>Please enter a search term.</div>");
	}

	require_once(dirname(__FILE__) . "/../includes/footer.php");
?>
