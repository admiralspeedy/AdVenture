<?php
if(!defined('ALLOW_ACCESS')) exit(header('Location: /')); //Only allow access to this file through the router

//The MessageSubject class extends the Subject class, implementing the getState and setState methods
class MessagesSubject extends Subject
{
    private $state; //Stores an array of conversation IDs that have new messages

    public function getState() //Get the current state of the subject
    {
        return $this->state; //Return the state
    }

    public function setState($newState) //Set the state of the subject
    {
        $this->state = $newState; //Set the state to the specified state
    }
}