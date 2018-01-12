<?php
if(!defined('ALLOW_ACCESS')) exit(header('Location: /')); //Only allow access to this file through the router

//The Database class is a wrapper for PHP's PDO library that handles errors/exceptions as well as connecting to and querying databases
class Database
{
	private $database; //Stores the database connection

	public function __construct($host, $port, $database, $username, $password) //Create a connection to the database
	{
        try //Try to connect to the specified database and catch any exceptions
        {
            $DSN = "mysql:host={$host};port={$port};dbname={$database};charset=utf8"; //Set the connection string

            //Set the PDO options
            $options = [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false
                       ];

            $this->database = new PDO($DSN, $username, $password, $options); //Attempt to connect to the database
        }
        catch (PDOException $e) //Catch any exceptions
        {
            exit(print('MySQL database connection failed.')); //Output an error message and kill the script
        }

	}

    public function query($sql, ...$params) //Query the database
    {
        try //Try to run the specified query with the specified paramters
        {
            $query = $this->database->prepare($sql); //Prepare the SQL statement

            if(!empty($params) && is_array($params[0])) //The user passed an array rather than a list of parameters
            {
                $params = $params[0]; //Remove the top level of the array
            }

            foreach($params as $i => $value) //Loop through the specified paramters
            {
                $query->bindValue($i + 1, $value); //Bind each parameter to the query
            }

            $query->execute(); //Attempt to execute the query

            return $query; //Return the result of the query
        }
        catch (PDOException $e) //Catch any exceptions
        {
            return false; //Return false to signify a failed query
        }
    }

    public function fetch($query) //Fetch a row from a successful query
    {
        if($query && $query->rowCount()) //The query was successful and returned at least one row
        {
            return $query->fetch(); //Fetch and return a row
        }

        return false; //Otherwise return false because the query was invalid or returned no rows
    }

    public function rowCount($query) //Get the number of rows returned by a query
    {
        if($query) //The query was successful
        {
            return $query->rowCount(); //Return the number of rows returned by the query
        }

        return false; //Otherwise return false because the specified was invalid
    }
}