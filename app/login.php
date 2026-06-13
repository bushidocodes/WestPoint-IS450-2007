<?php
	require_once(dirname(__FILE__) . "/../classes/AuthManager.php");

	if(session_status() === PHP_SESSION_NONE) session_start();

	$pageTitle = "Log In";

	//Already logged in? Nothing to do here.
	if(!empty($_SESSION['userID']))
	{
		header("Location: /app/");
		exit;
	}

	//Handle the form submission before any output so we can redirect
	if($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		$userID = trim($_POST['userID'] ?? '');
		$password = $_POST['password'] ?? '';
		$auth = new AuthManager();
		if($userID !== '' && $auth->verifyCredentials($userID, $password))
		{
			//Guard against session fixation now that privileges change
			session_regenerate_id(true);
			$_SESSION['userID'] = $userID;

			//Send the user back where they were headed, but only within /app/
			$target = $_SESSION['LOGIN_REDIRECT'] ?? '/app/';
			unset($_SESSION['LOGIN_REDIRECT']);
			if(!is_string($target) || strncmp($target, '/app/', 5) !== 0)
			{
				$target = '/app/';
			}
			header("Location: " . $target);
			exit;
		}
		$errorMessage = "Invalid user ID or password";
	}

	require_once(dirname(__FILE__) . "/includes/header.php");
?>
	<h2>Log In</h2>
<?php
	if(isset($errorMessage))
		print("<div class='error'>" . htmlspecialchars($errorMessage) . "</div>");
?>
	<form class="stacked" method="post" action="login.php">
		<label>User ID</label>
		<input type="text" name="userID" maxlength="6" value="<?php print(htmlspecialchars($_POST['userID'] ?? '')); ?>"/>
		<label>Password</label>
		<input type="password" name="password"/>
		<input type="submit" value="Log In"/>
	</form>
<?php
	require_once(dirname(__FILE__) . "/includes/footer.php");
?>
