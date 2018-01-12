<?php
if(!defined('ALLOW_ACCESS')) exit(header('Location: /')); //Only allow access to this file through the router

//The VehicleAd class implements the Ad interface
class VehicleAd implements Ad
{
    private $database, //Stores the database connection
            $data; //Stores the ad's data

    public function __construct($database) //Set up the ad by copying the specified database connection
    {
        $this->database = $database; //Copy the database connection
    }

    public function getAdData($adID) //Get the ad's data from the database
    {
        $adQuery = $this->database->query('SELECT id, userid, type, title, price, description, tags, time, email, postal, deleted, make, year, kilometers, transmission FROM advertisements WHERE id = ? AND deleted = 0 LIMIT 1', $adID); //Get the ad from the database

        if($this->database->rowCount($adQuery)) //An ad was returned
        {
            $this->data = $this->database->fetch($adQuery); //Get the ad row
            $this->data['image'][0] = 'default.png'; //Set the default image
            $imgQuery = $this->database->query('SELECT image FROM images WHERE adid = ? ORDER BY id ASC', $this->data['id']); //Check if the ad has an image uploaded
            $counter = 0; //Holds the number of images

            if($this->database->rowCount($imgQuery))  //The ad has an image uploaded
            {
                while($imgRow = $this->database->fetch($imgQuery)) //Loop through the image query results
                {
                    $this->data['image'][$counter] = $imgRow['image']; //Add each image to the data array
                    $counter++;
                }
            }

            return $this->data; //Return the ad array
        }

        return false; //Return false if the specified ad was not found
    }

    public function postAd($userID, $formData) //Add the ad to the database
    {
        if(!isset($formData['title']) || !isset($formData['price']) || !isset($formData['description']) || !isset($formData['address']) || !isset($formData['tags']) || !isset($formData['email']) || !isset($formData['make']) || !isset($formData['year']) || !isset($formData['mileage']) || !isset($formData['tranType']) || !isset($formData['images']) || (strlen($formData['email']) > 0 && !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) || (strlen($formData['tags']) > 0 && !preg_match('/^[a-zA-Z0-9,]+$/i', $formData['tags']))) //One of the required POST variables is missing or invalid
        {
            return json_encode(array('error' => 'You have entered invalid information.', 'success' => false)); //Output an error message
        }

        if(strlen(trim($formData['title'])) >= 10 && strlen(trim($formData['title'])) <= 50 && is_numeric($formData['price']) && intval($formData['price']) >= 1 && intval($formData['price']) <= 1000000000 && strlen($formData['description']) >= 1 && strlen($formData['description']) <= 500 && strlen($formData['address']) >= 6 && strlen($formData['address']) <= 7 && strlen($formData['make']) >= 1 && strlen($formData['make']) <= 50 && is_numeric($formData['year']) && intval($formData['year']) >= 1908 && intval($formData['year']) <= date('Y') + 1 && is_numeric($formData['mileage']) && intval($formData['mileage']) >= 1 && intval($formData['mileage']) <= 2000000 && ($formData['tranType'] == 'Automatic' || $formData['tranType'] == 'Manual') && strlen($formData['images'])) //The POST variables are valid
        {
            $images = str_replace(' ', '', $formData['images']); //Remove any spaces from the image string
            $images = explode(",", $images); //Explode the image string into an array
            $time = time(); //Get the current unix timestamp

            if($this->database->query('INSERT INTO advertisements (userid, type, title, price, description, tags, time, email, postal, make, year, kilometers, transmission) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', $userID, 1, trim($formData['title']), $formData['price'], $formData['description'], $formData['tags'], $time, $formData['email'], $formData['address'], $formData['make'], $formData['year'], $formData['mileage'], $formData['tranType'])) //Insert the new ad into the database
            {
                $adQuery = $this->database->query('SELECT id FROM advertisements WHERE userid = ? AND title = ? AND time = ? LIMIT 1', $userID, trim($formData['title']), $time); //Query for the new ad

                if(isset($adQuery) && $this->database->rowCount($adQuery)) //The query was successful and returned a row
                {
                    $adRow = $this->database->fetch($adQuery); //Fetch the ad row

                    for($i = 0; $i < count($images); $i++) //Loop through the images
                    {
                        $this->database->query('INSERT INTO images (adid, image) VALUES (?, ?)', $adRow['id'], $images[$i]); //Insert each image into the database
                    }

                    return json_encode(array('id' => $adRow['id'], 'success' => true)); //Output a success message
                }
                else //The ad wasn't added to the database
                {
                    return json_encode(array('error' => 'An error occurred.', 'success' => false)); //Output an error message
                }
            }
            else //The ad wasn't added to the database
            {
                return json_encode(array('error' => 'An error occurred.', 'success' => false)); //Output an error message
            }
        }
        else //The POST variables are invalid
        {
            return json_encode(array('error' => 'You have entered invalid information.', 'success' => false)); //Output an error message
        }
    }

	public function editAd($userID, $adID, $formData) //Edit the specified ad if the user owns it
    {
        if(!isset($formData['title']) || !isset($formData['price']) || !isset($formData['description']) || !isset($formData['address']) || !isset($formData['tags']) || !isset($formData['email']) || !isset($formData['make']) || !isset($formData['year']) || !isset($formData['mileage']) || !isset($formData['tranType']) || !isset($formData['images']) || (strlen($formData['email']) > 0 && !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) || (strlen($formData['tags']) > 0 && !preg_match('/^[a-zA-Z0-9,]+$/i', $formData['tags']))) //One of the required POST variables is missing or invalid
        {
            return json_encode(array('error' => 'You have entered invalid information.', 'success' => false)); //Output an error message
        }

        if(strlen(trim($formData['title'])) >= 10 && strlen(trim($formData['title'])) <= 50 && is_numeric($formData['price']) && intval($formData['price']) >= 1 && intval($formData['price']) <= 1000000000 && strlen($formData['description']) >= 1 && strlen($formData['description']) <= 500 && strlen($formData['address']) >= 6 && strlen($formData['address']) <= 7 && strlen($formData['make']) >= 1 && strlen($formData['make']) <= 50 && is_numeric($formData['year']) && intval($formData['year']) >= 1908 && intval($formData['year']) <= date('Y') + 1 && is_numeric($formData['mileage']) && intval($formData['mileage']) >= 1 && intval($formData['mileage']) <= 2000000  && ($formData['tranType'] == 'Automatic' || $formData['tranType'] == 'Manual')) //The POST variables are valid
        {
            if($this->database->query('UPDATE advertisements SET title = ?, price = ?, description = ?, tags = ?, email = ?, postal = ?, make = ?, year = ?, kilometers = ?, transmission = ? WHERE userid = ? AND id = ?', trim($formData['title']), $formData['price'], $formData['description'], $formData['tags'], $formData['email'], $formData['address'], $formData['make'], $formData['year'], $formData['mileage'], $formData['tranType'], $userID, $adID)) //Update the specified ad
            {
                if(strlen($formData['images'])) //The user uploaded more images
                {
                    $images = str_replace(' ', '', $formData['images']); //Remove any spaces from the image string
                    $images = explode(",", $images); //Explode the image string into an array

                    for($i = 0; $i < count($images); $i++) //Loop through the images
                    {
                        $this->database->query('INSERT INTO images (adid, image) VALUES (?, ?)', $adID, $images[$i]); //Insert each image into the database
                    }
                }

                return json_encode(array('id' => $adID, 'success' => true)); //Output a success message
            }
            else //The ad wasn't added to the database
            {
                return json_encode(array('error' => 'An error occurred.', 'success' => false)); //Output an error message
            }
        }
        else //The POST variables are invalid
        {
            return json_encode(array('error' => 'You have entered invalid information.', 'success' => false)); //Output an error message
        }
    }

    public function showPostView() //Show the ad posting form view
    {
        include(__DIR__ . '/../pages/vehicleform.php'); //Include the ad posting form
    }

    public function showEditView($userID, $adID) //Show the ad editing form view
    {
        $this->getAdData($adID); //Get the specified ad's data

        if($this->data['userid'] == $userID) //The specified user owns the specified ad
        {
            include(__DIR__ . '/../pages/editvehicleform.php'); //Include the ad edit form
        }
        else //The specified user doesn't own the specified ad
        {
            exit(header('Location: /')); //Kill the script and send the user back to the homepage
        }
    }

    public function showAdView($adID, $posterName, $loggedIn) //Show the ad view
    {
        $this->getAdData($adID); //Get the specified ad's data

        $tags = array(); //Create an array for the tags

        if(strlen($this->data['tags'])) //The ad has tags
        {
            $tags = explode(",", $this->data['tags']); //Explode the tags into an array
        }

        include(__DIR__ . '/../pages/vehicleadview.php'); //Show the ad view
    }
}