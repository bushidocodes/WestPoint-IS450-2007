<?php
require_once(dirname(__FILE__) . '/../adodb/adodb.inc.php');

/**
 *	AbstractManager that raises all standard operations to the parent
 *  class
 */
	abstract class AbstractManager
	{

		protected $mDb;  //available to this class and subclasses
		private $isConnected;


	/**
	 * Constructor for the AbstractManager
	 */
	protected function __construct($adodb = null)
	{
		$this->mDb = null;
		$this->OpenDb();
	} //end __construct()


	/**
	 *	Checks for existence of a connection to a ADODB supported
	 *  database. Failing a connection it opens the database with
	 *  ADODB generic connections.
	 */
	private function OpenDb ()
	{
		$resultCode = false;
		if (is_null($this->mDb))
		{
			$host = getenv('MYSQL_HOST') ?: 'localhost';
			$this->mDb = ADONewConnection('mysqli');
			$this->mDb->Connect($host, 'root', 'abc', 'isd');
			$this->mDb->SetFetchMode(ADODB_FETCH_ASSOC);
			if(!$this->mDb)
			{
				die( "<br/>AbstractManager::OpenDb() Could not " .
					"connect to Database Server: " . $host );
			}
			else
			{
				$this->isConnected = true;
				$resultCode = true;
			}
		}
		return $resultCode;
	}


	/**
	 * Closes a connection to the database with ADODB supported
	 * database command
	 */
	protected function CloseDb()
	{
		$this->mDb->Close();
		$this->isConnected = false;
		$this->mDb = null;
		print("Database is closed\n");
		return true;
	} //end CloseDb()

	/**
	 * Determine if there is a valid connection
	 */
	final public function getConnection ()
	{
		return $this->isConnected;
	}


} //end AbstractManager
?>
