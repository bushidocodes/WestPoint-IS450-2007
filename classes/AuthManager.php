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
	 * Passwords are stored as bcrypt hashes (PHP password_hash with
	 * PASSWORD_DEFAULT) and verified with password_verify(), which compares in
	 * constant time. On a successful login we transparently re-hash the stored
	 * value if it needs upgrading: an old hashing cost, or a legacy plaintext
	 * row left over from the original 2007 schema.
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
		$password = (string)$password;

		//Modern path: stored value is a password_hash() hash.
		if(password_verify($password, $stored))
		{
			if(password_needs_rehash($stored, PASSWORD_DEFAULT))
			{
				$this->updatePassword($userID, $password);
			}
			return $userID;
		}

		//Legacy path: a plaintext row from the original schema. Compare in
		//constant time, and upgrade it to a hash on the way in.
		if(!$this->looksHashed($stored) && $stored !== '' && hash_equals($stored, $password))
		{
			$this->updatePassword($userID, $password);
			return $userID;
		}

		return false;
	}

	/**
	 * Store a fresh password_hash() for the given user.
	 */
	private function updatePassword($userID, $password)
	{
		$hash = password_hash($password, PASSWORD_DEFAULT);
		$query = "UPDATE authenticationTable SET password = " . $this->mDb->qStr($hash) .
			" WHERE userID = " . $this->mDb->qStr($userID);
		$this->mDb->Execute($query);
	}

	/**
	 * Heuristic: does the stored value look like a Crypt/password_hash() string?
	 * Used only to decide whether to treat a non-matching value as legacy
	 * plaintext worth upgrading.
	 */
	private function looksHashed($stored)
	{
		$info = password_get_info($stored);
		return $info['algo'] !== null && $info['algo'] !== 0;
	}
}
?>
