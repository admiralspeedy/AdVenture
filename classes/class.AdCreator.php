<?php
if(!defined('ALLOW_ACCESS')) exit(header('Location: /')); //Only allow access to this file through the router

//The AdCreator class is a factory that creates and returns ads
class AdCreator
{
    private $database, //Stores the database connection
            $createdProduct; //Stores the created product

    public function __construct($database) //Set up the creator by copying the specified database connection
    {
        $this->database = $database; //Copy the database connection
    }

	public function factoryMethod($type) //Create an ad based on the specified type
	{
        if($type == 0) //General ad
        {
            $this->createdProduct = new GeneralAd($this->database);
        }
        else if($type == 1) //Vehicle ad
        {
            $this->createdProduct = new VehicleAd($this->database);
        }
        else if($type == 2) //Property ad
        {
            $this->createdProduct = new PropertyAd($this->database);
        }
        else //Invalid type
        {
            $this->createdProduct = false;
        }

		return $this->createdProduct; //Return the new ad
	}
}