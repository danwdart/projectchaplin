<?php
/**
 * @category   MongoDB
 * @package    Mongo
 * @copyright  2010, Campaign and Digital Intelligence Ltd
 * @license    New BSD License
 * @author     Tim Langley
 * @todo:		Should make this more Zend friendly (ie taking a config object)
 * @todo:		Should support multiple connections (and slave only - read only connections)
**/

class Mongo_Connection
{
	const	CONN_HOST_STRING						= "%s:%d,";
	const	CONN_AUTH								= "%s:%s@";
	
	const	DEFAULT_HOST							= Mongo::DEFAULT_HOST;
	const	DEFAULT_URL								= 'mongodb://';
	const	DEFAULT_PORT							= Mongo::DEFAULT_PORT;
	
	const	STR_CONNECTION							= 'connection';
	const 	STR_DATABASE							= "database";
		
	const   MONGO_FIELD_ID                          = '_id';
	
	private static $_defaultConnectionString		= null;
	private static $_defaultDatabaseName			= null;
	
	/**
	 *	@purpose:	This private function manages the _raw_mongo parameter ensuring it's connected
	 *	@return:	The _raw_mongo (Mongo) object
	**/
	private $_raw_mongo	= null;
	private function raw_mongo()
	{
		if($this->b_IsConnected)
			return $this->_raw_mongo;
		$this->_raw_mongo->Connect();
		
		return $this->_raw_mongo;
	}
	/**
	 *	@purpose:	This private function manages the _arrRawMongoDB parameter ensuring it's connected to a DB
	 *	@return:	the _arrRawMongoDB (Mongo) object
	**/
	private $_arrRawMongoDB	= array();
	private function raw_mongoDB($strDatabaseName)
	{
		if(is_null($strDatabaseName)) {
            throw new Mongo_Exception(Mongo_Exception::ERROR_MISSING_DATABASE);
		}
			
		if($this->isConnected($strDatabaseName))
			return $this->_arrRawMongoDB[$strDatabaseName];
			
		//Otherwise the hard work - lets make a connection to the right database
		$this->_arrRawMongoDB[$strDatabaseName]		= $this->raw_mongo()->selectDB($strDatabaseName);
		return $this->_arrRawMongoDB[$strDatabaseName];
	}
	/**
	 *	@purpose:	Constructs a new Mongo_Connection
	 *	@param:		$connection		= see $this->createConnectionString (but array, string, Zend_Config object)
	 *	@param:		$options		= array of Mongo options
	 *
	 *	@todo:		Improve the $options so that this can take a Zend object too 	
	**/
	public  function __construct($connection  = null, $options = array())
	{
		$arrConnection		 	= $this->createConnectionArray($connection);
		$connectionString		= $arrConnection[self::STR_CONNECTION];
		$databaseName			= $arrConnection[self::STR_DATABASE];
	
		//NOTE: This overrides to make sure that it only connects when required
		$this->b_IsConnected	= false;
		$options['connect'] 	= false;
		$this->_raw_mongo		= new Mongo($connectionString, $options);
	}
	/**
	 *	@purpose: 	If connected this returns the Mongo instance
	 *					otherwise it tries to connect
	 *	@param:		$strDatabaseName (if this is null then tries default
	 *	@return:	true | exception
	**/
	public 	function connect($strDatabaseName 		= null)
	{
		$this->raw_mongoDB($strDatabaseName);
		return true;
	}
	/**
	 * Runs the distinct command
	 *
	 * @param string $strDatabaseName 
	 * @param string $strCollection 
	 * @param string $strKey 
	 * @param Array $arrQuery 
	 * @return void
	 * @author Tim Langley
	**/
	public function distinct($strDatabaseName, $strCollection, $strKey, Array $arrQuery = array())
	{
	    $arrCommand = array( 'distinct' => $strCollection
	                        ,'key'      => $strKey);
	    if(!empty($arrQuery))
	        $arrCommand['query']        = $arrQuery;
	    $arrReturn  = $this->raw_mongoDB($strDatabaseName)->command($arrCommand);
	    if(!isset($arrReturn['values'])) {
	        $strErrorMessage = isset($arrReturn['errmsg'])?$arrReturn['errmsg']:Mongo_Exception::ERROR_UNKNOWN;
	        throw new Mongo_Exception($strErrorMessage);
	    }
	    return $arrReturn['values'];
	}
	
	const MR_MapReduce = 'mapreduce';
	const MR_Map = 'map';
	const MR_Reduce = 'reduce';
	const MR_Finalize = 'finalize';
	const MR_Out = 'out';
	const MR_Query = 'query';
	const MR_Sort = 'sort';
	const MR_Limit = 'limit';
	const MR_Inline = 'inline';
	const MR_Verbose = 'verbose';
	const MR_Results = 'results';
	const MR_Value = 'value';
	
	/**
     * Run a MapReduce query
     *
     * @param string $strDatabase - database name
     * @param string $strCollection - collection name
     * @param MongoCode $map - JS code for map function
     * @param MongoCode $reduce - JS code for reduce function
     * @param MongoCode $finalize - JS code for finalize function
     * @param string | array $out - either the collection to save results in or array with details (see reference)
     * @param Array | null $arrQuery - standard query array
     * @param string | null $sort - input field to sort upon
     * @param int | null $limit - limit the results
     * @return Array - output
     * @see http://www.mongodb.org/display/DOCS/MapReduce
     * @author Dan Dart
     */
	public function mapReduce($strDatabase, $strCollection, MongoCode $map, MongoCode $reduce, MongoCode $finalize, $out, Array $arrQuery = array(), $sort = null, $limit = null)
	{
	    $arrCommand = array(
	       self::MR_MapReduce => $strCollection,
	       self::MR_Map => $map,
	       self::MR_Reduce => $reduce,
	       self::MR_Finalize => $finalize,
	       self::MR_Out => $out,
	       self::MR_Query => $arrQuery,
	       self::MR_Sort => $sort,
	       self::MR_Limit => $limit,
	       self::MR_Verbose => false
	    );
	    
	    return $this->raw_mongoDB($strDatabase)->command($arrCommand);
	}
	
	/**
	 *	@purpose: 	This drops the database 
	 *				(if it's already connected to a different DB then this is cached and returned to later)
	 *	@param:		$strDatabaseName 
	**/
	public  function dropDatabase($strDatabaseName)
	{
		if(is_null($strDatabaseName))
			throw new Mongo_Exception(Mongo_Exception::ERROR_MISSING_DATABASE);
		
		$this->raw_mongoDB($strDatabaseName)->drop();
		if(array_key_exists($strDatabaseName,$this->_arrRawMongoDB))
			unset($this->_arrRawMongoDB[$strDatabaseName]);
		return true;
	}
	/**
	 *	@purpose: 	This opens a file from the file system and runs it within the MongoDb
	 *				This is mainly used for unit testing where the file will contain details how to set-up the test
	 *	@param:		$strFileNameAndPath - full name and path to the file
	**/
	public 	function executeFile($strFileNameAndPath, $strDatabaseName = "local")
	{
		$handle					= fopen($strFileNameAndPath, "r");
		if(!$handle)
			throw new Mongo_Exception(Mongo_Exception::ERROR_FILE_NOT_FOUND);
		$strJavascriptString	= fread($handle, filesize($strFileNameAndPath));
		fclose($handle);
		$mongoCode				= new MongoCode($strJavascriptString);
		return $this->raw_mongoDB($strDatabaseName)->execute($mongoCode);
	}
	/**
	 * @purpose:	This sets the current collection to $strCollectionName
	 * @return:		This returns a Mongo_Collection (or child of this)
	**/
	public  function getCollection($strDatabaseName, $strCollectionName)
	{
		$colCollection	= new Mongo_Collection($strDatabaseName, $strCollectionName);
		$colCollection->setConnection($this);
		return $colCollection;
	}
	/**
	 *	@purpose:	Returns an array of MongoConnection objects
	 *	@return: 	array(MongoConnection, MongoConnection etc...)
	 *	@todo: 		probably we should put this in to a "wrapped class" CollectionIterator
	**/
	public  function getCollections($strDatabaseName)
	{
		return $this->raw_mongoDB($strDatabaseName)->listCollections();
	}
	/**
	 *	@purpose: 	Returns a list of all the databases on this Mongo server
	 *	@NOTE:		This function can be called WITHOUT the DatabaseName parameter being selected
	**/
	public 	function getDatabases()
	{
		$arrDatabases	= $this->raw_mongo()->listDBs();
		//Time for some major sanity checking
		if(!is_array($arrDatabases) 
		&& !is_set($arrDatabases["ok"]) 
		&& 1 != $arrDatabases["ok"] 
		&& !is_set($arrDatabases["databases"]))
			throw new Mongo_Exception(Mongo_Exception::ERROR_UNKNOWN);
		return $arrDatabases["databases"];
	}
	/**
	 * @purpose:	This sets the current collection to $strCollectionName
	 * @NOTE:		This is a "naughty helper function for other classes"
	 * @return:		This returns a MongoCollection
	**/
	public  function getrawCollection($strDatabaseName, $strCollectionName)
	{
		if(is_null($strCollectionName))
			throw new Mongo_Exception(Mongo_Exception::ERROR_COLLECTION_NULL);
		
		return $this->raw_mongoDB($strDatabaseName)->selectCollection($strCollectionName);
	}
	/**
	 * @purpose:	This returns the current database object
	 * @NOTE:		This is a "naughty helper function for other classes"
	 * @return:		This returns a MongoDB
	**/
	public 	function getrawDatabase($strDatabaseName)
	{
		return $this->raw_mongoDB($strDatabaseName);
	}
	/**
	 *	@purpose: 	Is this connected to a Mongo server (and database)
	 *	@return:	true | false
	**/
	public  function isConnected($strDatabaseName)
	{
		return (array_key_exists($strDatabaseName,$this->_arrRawMongoDB) && !is_null($this->_arrRawMongoDB[$strDatabaseName]));
	}
	/**
	 *	@purpose:	Creates the Mongo Connection string
	 *	@param:		$connection	- can be:	1. 	null						=> use the default parameters
	 *										2.	a string					=> we assume that this is correct and use it
	 *																			NOTE: the php engine doesn't like hots:port/dbname
	 *																			NOTE: therefore at moment you have to pass this 
	 *																			NOTE: separately if you want to use the string
	 *										3.	an array 					=> 
	 *								associative array like:		hosts 		=> array("name" => array( host => , port => ))
	 *															auth  		=> array(username =>, password => )
	 *																				(if host = null then DEFAULT is taken)
	 *																				(if port = null then DEFAULT is taken)
	 *															defaultDB 	=> string
	 *										4. 	a Zend_Config object
	 *															mongo.hosts.HOST_NAME.host	= 127.0.0.1
	 *															mongo.hosts.HOST_NAME.port	= 27017
	 *															mongo.auth.username			= "Tim"
	 *															mongo.auth.password			= "abc123";	
	 *															mongo.defaultDB				= ""								
	 *	@return:	array[0] = the connection string
	 *				array[1] = the databasename
	**/
	private static function createConnectionArray($connection = null)
	{
		
		if(is_null($connection)) {
			//If $connection is null then try to load the default one first
			$strConnectionString		= Mongo_Connection::$_defaultConnectionString;
			if(is_null($strConnectionString))
				$strConnectionString	= self::DEFAULT_URL.
											trim(sprintf(self::CONN_HOST_STRING, self::DEFAULT_HOST, self::DEFAULT_PORT),",");
			$databaseName				= Mongo_Connection::$_defaultDatabaseName;
			return array(self::STR_CONNECTION 	=> $strConnectionString
						,self::STR_DATABASE 	=> $databaseName);
		}
		if(is_string($connection)) {
//@TODO HERE - have to parse the Connection
			$strConnectionString	= $connection;
			$databaseName 			= "";
			return array(self::STR_CONNECTION 	=> $strConnectionString
						,self::STR_DATABASE 	=> $databaseName);
		}
			
		if (is_a($connection, "Zend_Config"))
            $connection 				= $connection->toArray();
        
		$strConnectionString			= "";
		//Now build the connectionString from the array parameters
		if(isset($connection["hosts"]) && is_array($connection["hosts"]))
			foreach($connection["hosts"] AS $arrHost) {
				$strHost				= (array_key_exists("host", $arrHost))?$arrHost["host"]:self::DEFAULT_HOST;
				$intPort				= (array_key_exists("port", $arrHost))?$arrHost["port"]:self::DEFAULT_PORT;	
				$strConnectionString	.=sprintf(self::CONN_HOST_STRING, $strHost, $intPort);
			}
		else
			$strConnectionString		= sprintf(self::CONN_HOST_STRING, self::DEFAULT_HOST, self::DEFAULT_PORT);
		
		$strConnectionString			= trim($strConnectionString, ',');
		if(isset($connection["auth"]) && is_array($connection["auth"]))
			$strConnectionString		= sprintf(self::CONN_AUTH, $connection["auth"]["username"], $connection["auth"]["password"])
											.$strConnectionString;
		$strConnectionString			= self::DEFAULT_URL.$strConnectionString;
		
		$databaseName					= isset($connection["defaultDB"])?
												$connection["defaultDB"]:Mongo_Connection::$_defaultDatabaseName;
		
		return array(self::STR_CONNECTION 	=> $strConnectionString
					,self::STR_DATABASE 	=> $databaseName); 
	}
	public 	static function setDefaultConnectionString($connection)
	{
		$arrConnection								= Mongo_Connection::createConnectionArray($connection);
		Mongo_Connection::$_defaultConnectionString	= $arrConnection[self::STR_CONNECTION];
		Mongo_Connection::$_defaultDatabaseName		= $arrConnection[self::STR_DATABASE];
	}
	public 	static function getDefaultConnectionString()
	{
		return Mongo_Connection::$_defaultConnectionString;
	}
}