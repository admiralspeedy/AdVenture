<?php
if(!defined('ALLOW_ACCESS')) exit(header('Location: /')); //Only allow access to this file through the router

//The abstract Subject class implements the attach, detach, and notify methods and specifies the getState and setSate methods for concrete subjects to implement
abstract class Subject
{
    protected $observers; //Stores the list of observers

    abstract public function getState(); //Require the extending subjects to implement a getState method
    abstract public function setState($newState); //Require the extending subjects to implement a setState method

    public function attach($observer) //Attach the specified observer to the subject
    {
        $this->observers[] = $observer; //Add the specified observer to the observer array
    }

    public function detach($observer) //Detach the specified observer from
    {
        foreach($this->observers as $key => $value) //Loop through the array of observers
        {
            if($this->observers[$key] == $observer) //The specified observer was found
            {
                unset($this->observers[$key]); //Detach the observer
                break; //Break out of the loop
            }
        }
    }

    public function notify() //Notify all of the observers
    {
        foreach($this->observers as $value) //Loop through the array of observers
        {
            $value->update($this); //Call each observer's update function with an instance of the subject
        }
    }
}