<?php
if(!defined('ALLOW_ACCESS')) exit(header('Location: /')); //Only allow access to this file through the router

//The Ad interface specifies the methods that must be implemented by Ad products produced by the Ad factory
interface Ad
{
	public function getAdData($adID); //Get the ad's data from the database
    public function postAd($userID, $formData); //Add the ad to the database
    public function editAd($userID, $adID, $formData); //Edit the specified ad
    public function showPostView(); //Show the ad posting form view
    public function showEditView($userID, $adID); //Show the ad editing form view
    public function showAdView($adID, $posterName, $loggedIn); //Show the ad view
}