<?php
require_once("AbstractManager.php");

/**
 *	Data access for the authenticationTable. Verifies a userID/password
 *	pair against the credentials seeded alongside personTable.
 */
class AuthManager extends AbstractManager
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Verify a login. Returns the userID on success, or false on failure.
	 *
	 * Passwords are stored in plaintext in the period-authentic 2007 schema
	 * (see authenticationTable). We still compare in constant time with
	 * hash_equals() so the check does not leak the stored value via timing.
	 */
	public function verifyCredentials($userID, $password)
	{
		$query = "SELECT password FROM authenticationTable WHERE userID = " . $this->mDb->qStr($userID);
		$resultSet = $this->mDb->Execute($query);
		if(!$resultSet || !$resultSet->fields)
		{
			return false;
		}
		$stored = (string)$resultSet->fields['password'];
		return hash_equals($stored, (string)$password) ? $userID : false;
	}
}
?>
