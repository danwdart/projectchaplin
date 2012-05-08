<?php
/**
 * @category   MongoDB
 * @package    Mongo
 * @copyright  2010, Campaign and Digital Intelligence Ltd
 * @license    New BSD License
 * @author     Tim Langley
**/
class Mongo_Exception extends Exception	
{
    const ERROR_COLLECTION_NULL				= "Collection parameter is empty";
	const ERROR_COLLECTION_WRONG_COLLECTION	= "Integrity error - this collection '{%s}' doesn't belong in this collection '{%s}'";
	const ERROR_COLLECTION_WRONG_DATABASE	= "Integrity error - this database '{%s}' doesn't belong in this database '{%s}'";
	const ERROR_CONNECTION_NULL				= "Connection empty";
	const ERROR_CURSOR_NULL					= "Cursor null";
	const ERROR_FILE_NOT_FOUND				= "File not found";
	const ERROR_MISSING_DATABASE			= "Database name parameter is empty";
	const ERROR_MISSING_VALUES				= "Missing values";
	const ERROR_NOT_NULL					= "Value can't be empty";
	const ERROR_UNKNOWN						= "An unknown error has occurred";
}
