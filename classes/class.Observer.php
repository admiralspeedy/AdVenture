<?php
if(!defined('ALLOW_ACCESS')) exit(header('Location: /')); //Only allow access to this file through the router

//The Observer interface specifies the methods that must be implemented by Observer objects
interface Observer
{
    public function update(Subject $subject); //Update the observer with the new subject state
}