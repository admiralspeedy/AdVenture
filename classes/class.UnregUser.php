<?php
if(!defined('ALLOW_ACCESS')) exit(header('Location: /')); //Only allow access to this file through the router

//The UnregUser class provides the basic user functionality of logging in, registering, various types of data retrieval, session validation, and view routing
class UnregUser
{
    protected $database, //Stores the database connection
              $data; //Stores the user's data

    public function __construct($database) //Set up the customer by copying the specified database connection
    {
        $this->database = $database; //Copy the database connection
    }

    public function login($formData) //Log the user into the system by checking the supplied email and password and creating a session
    {
        if($this->validSession()) //The user is already logged in
        {
            exit(header('Location: /')); //Send the user back to their home view and kill the script
        }

        header('Content-type: application/json; charset=utf-8'); //Set the content type to JSON

        if(!isset($formData['email']) || !isset($formData['pass']) || !isset($_SESSION)) //One of the required POST variables is missing or a session hasn't been started
        {
            exit(print json_encode(array('error' => 'You have entered an invalid email and/or password.', 'success' => false))); //Output a JSON encoded error message
        }

        if(filter_var($formData['email'], FILTER_VALIDATE_EMAIL) && strlen($formData['pass'])) //The POST variables are valid
        {
            if($this->getUserBy('email', $formData['email'])) //Get the user's data the supplied email
            {
                if(password_verify($formData['pass'], $this->data['password'])) //The supplied password is correct
                {
                    if($this->data['banned'] == 1) //The user is banned
                    {
                        exit(print json_encode(array('error' => 'Your account has been banned.', 'success' => false))); //Output a JSON encoded error message
                    }

                    //Set up the user's session with their ID, rank, and their premium status
                    $_SESSION['id'] = $this->data['id'];
                    $_SESSION['rank'] = $this->data['rank'];
                    $_SESSION['premium'] = $this->data['premium'];

                    exit(print json_encode(array('success' => true))); //Output a JSON encoded success message
                }
                else //The supplied password is incorrect
                {
                    exit(print json_encode(array('error' => 'The password you entered is incorrect.', 'success' => false))); //Output a JSON encoded error message
                }
            }
            else //The user doesn't exist
            {
                exit(print json_encode(array('error' => 'An account with that email does not exist.', 'success' => false))); //Output a JSON encoded error message
            }
        }
        else //The POST variables are invalid
        {
            exit(print json_encode(array('error' => 'You have entered an invalid email and/or password.', 'success' => false))); //Output a JSON encoded error message
        }
    }

    public function register($formData) //Register the user
    {
        if($this->validSession()) //The user is already logged in
        {
            exit(header('Location: /')); //Send the user back to their home view and kill the script
        }

        header('Content-type: application/json; charset=utf-8'); //Set the content type to JSON

        if(!isset($formData['username']) || !isset($formData['email']) || !isset($formData['pass']) || !isset($formData['confpass']) || !isset($_SESSION)) //One of the required POST variables is missing
        {
            exit(print json_encode(array('error' => 'You have entered invalid information.', 'success' => false))); //Output a JSON encoded error message
        }

        if(strlen($formData['username']) >= 8 && strlen($formData['username']) <= 20 && filter_var($formData['email'], FILTER_VALIDATE_EMAIL) && strlen($formData['pass']) >= 8 && strlen($formData['pass']) <= 25 && strlen($formData['confpass']) >= 8 && strlen($formData['confpass']) <= 25) //The POST variables are valid
        {
            if(!strcmp($formData['pass'], $formData['confpass'])) //The two passwords match
            {
                if($this->database->query('INSERT INTO users (email, password, username) VALUES (?, ?, ?)', $formData['email'], password_hash($formData['pass'], PASSWORD_DEFAULT), $formData['username'])) //Insert the new user into the database
                {
                    $this->getUserBy('email', $formData['email']); //Get the user's data the supplied email

                    //Set up the user's session with their ID, rank, and their premium status
                    $_SESSION['id'] = $this->data['id'];
                    $_SESSION['rank'] = $this->data['rank'];
                    $_SESSION['premium'] = $this->data['premium'];

                    exit(print json_encode(array('success' => true))); //Output a JSON encoded success message
                }
                else //The user wasn't added to the database
                {
                    exit(print json_encode(array('error' => 'An account with that username or email already exists.', 'success' => false))); //Output a JSON encoded error message
                }
            }
            else //The two passwords don't match
            {
                exit(print json_encode(array('error' => 'The two passwords you entered do not match.', 'success' => false))); //Output a JSON encoded error message
            }
        }
        else //The POST variables are invalid
        {
            exit(print json_encode(array('error' => 'You have entered invalid information.', 'success' => false))); //Output a JSON encoded error message
        }
    }

    public function view($view) //Show the specified view
    {
        if(!$this->validSession()) //The user is not logged in
        {
            if($view == 'login' || $view == 'register' || $view == 'search')
            {
                include(__DIR__ . '/../pages/' . $view . '.php'); //Display the login or registration view
            }
            else if($view == 'profile' && isset($_GET['id']))
            {
                if($this->getUserBy('id', $_GET['id'])) //Get the user's info
                {
                    $id = $this->data['id']; //Set the ID
                    $username = $this->data['username']; //Set the username
                    $rating = $this->getUserRating($_GET['id']); //Set the rating

                    include(__DIR__ . '/../pages/profile.php'); //Display the profile view
                }
                else //The user was not found
                {
                    exit(header('Location: /')); //Send the user back to the index page and kill the script
                }
            }
            else if($view == 'ad' && isset($_GET['id']))
            {
                $adFactory = new AdCreator($this->database); //Create an ad factory
                $viewAd = $adFactory->factoryMethod($this->getAdType($_GET['id'])); //Create an ad of the proper type

                if($viewAd) //An ad object was created
                {
                    $this->getUserBy('id', $viewAd->getAdData($_GET['id'])['userid']); //Get the poster's info
                    $posterName = $this->data['username']; //Get the poster's username

                    $viewAd->showAdView($_GET['id'], $posterName, false); //Show the ad view
                }
                else //An ad object was not created (invalid type/ad not found)
                {
                    exit(header('Location: /')); //Send the user back to the index page and kill the script
                }
            }
            else //Invalid view
            {
                exit(header('Location: /')); //Send the user back to the homepage and kill the script
            }
        }
        else //The user is logged in (which should never happen)
        {
            exit(header('Location: /')); //Send the user back to the homepage and kill the script
        }
    }

    public function validSession() //Check whether the user has a valid session or not
    {
        return isset($_SESSION['id']) && $_SESSION['id'] > 0 && isset($_SESSION['rank']) && $_SESSION['rank'] >= 0 && $_SESSION['rank'] <= 1; //Return true if the session is valid or false if itt's invalid
    }

    public function getUserBy($param, $value) //Get the user's data from the database based on the specified parameter and value
    {
        if($param == 'id' || $param == 'email') //A valid parameter was specified
        {
            $userQuery = $this->database->query('SELECT * FROM users WHERE ' . $param . ' = ? LIMIT 1', $value); //Select the user from the database with the specified paramter and value
        }

        if(isset($userQuery) && $this->database->rowCount($userQuery)) //The query was successful and returned a row
        {
            $userRow = $this->database->fetch($userQuery); //Fetch the row

            foreach($userRow as $key => $data) //Loop through each key/data pair in the row
            {
                $this->data[$key] = $data; //Copy each key/data pair into the data array
            }

            return true; //Return true because the user was found
        }

        unset($this->data); //Unset any data that might be set since the user wasn't found

        return false; //Return false because the user wasn't found
    }

    public function getUserRating($userID) //Get the specified user's rating
    {
        $ratingQuery = $this->database->query('SELECT * FROM ratings WHERE userid = ?', $userID); //Get the user's ratings from the database

        $rating = 0; //Initialize the rating

        if($this->database->rowCount($ratingQuery)) //Ratings were returned
        {
            while($ratingRow = $this->database->fetch($ratingQuery)) //Loop through the ratings
            {
                $rating += $ratingRow['rating']; //Add each rating to the rating variable
            }

            return intval(round(floatval($rating) / floatval($this->database->rowCount($ratingQuery)))); //Return a rounded average of all the user's ratings
        }

        return 0; //Return a rating of 0 if no ratings were returned
    }

    public function getUserAds($userID) //Get the specified user's ads
    {
        $adQuery = $this->database->query('SELECT id, type FROM advertisements WHERE userid = ? AND deleted = 0 ORDER BY id DESC', $userID); //Get the user's ads from the database

        if($this->database->rowCount($adQuery)) //Ads were returned
        {
            $data = array('success' => true, 'ads' => array()); //Create the array that will be returned
            $adFactory = new AdCreator($this->database); //Create an ad factory

            while($adRow = $this->database->fetch($adQuery)) //Loop through the query results
            {
                $newAd = $adFactory->factoryMethod($adRow['type']); //Create a new add of the approriate type
                $data['ads'][] = $newAd->getAdData($adRow['id']); //Get and push the ad on to the data array
            }

            exit(print json_encode($data)); //Output the data array
        }
        else //No ads were returned
        {
            exit(print json_encode(array('success' => false, 'message' => '<h3>No advertisements to display!</h3>'))); //Output the data array
        }
    }

    public function getLatestAds() //Get the latest ads
    {
        $adQuery = $this->database->query('SELECT id, type FROM advertisements WHERE deleted = 0 ORDER BY id DESC LIMIT 12'); //Get the latest ads from the database

        if($this->database->rowCount($adQuery)) //Ads were returned
        {
            $data = array('success' => true, 'ads' => array()); //Create the array that will be returned
            $adFactory = new AdCreator($this->database); //Create an ad factory

            while($adRow = $this->database->fetch($adQuery)) //Loop through the query results
            {
                $newAd = $adFactory->factoryMethod($adRow['type']); //Create a new add of the approriate type
                $data['ads'][] = $newAd->getAdData($adRow['id']); //Get and push the ad on to the data array
            }

            exit(print json_encode($data)); //Output the data array
        }
        else //No ads were returned
        {
            exit(print json_encode(array('success' => false, 'message' => '<h3>No advertisements to display!</h3>'))); //Output the data array
        }
    }

    public function getAdType($adID) //Return the type of an existing ad
    {
        $typeQuery = $this->database->query('SELECT type FROM advertisements WHERE id = ? LIMIT 1', $adID); //Get the ad's type from the database

        if($this->database->rowCount($typeQuery)) //A type was returned
        {
            $typeRow = $this->database->fetch($typeQuery);

            return $typeRow['type']; //Return the type
        }

        return -1; //Return a type of -1 because the ad was not found
    }

    public function searchAds($formData) //Search for ads based on the specified parameters
    {
        if(isset($formData['search'])) //A valid search was provided
        {
            $words = explode(" ", trim($formData['search'])); //Explode the search query into words at each space
            $query = 'SELECT id, type FROM advertisements WHERE deleted = 0 AND ('; //Set the first part of the query
            $counter = 0; //Stores a count of the number of parameters for building the query
            $newParams = array(); //Stores the paramaters for the query

            foreach($words as $value) //Loop through the words array
            {
                if($counter) $query .= ' OR'; //This isn't the first set of parameters, so add an 'OR' to the query
                $query .= ' title LIKE ? OR tags LIKE ?'; //Add another set of LIKEs to the query

                //Add the word to the parameter array twice (once for the the title search and once for the tag search)
                $newParams[] = '%' . $value . '%';
                $newParams[] = '%' . $value . '%';

                $counter++;
            }

            if(!isset($formData['type']) || $formData['type'] == '') //No search type is specified
            {
                $query .= ') ORDER BY id DESC'; //Finish building the query
            }
            else if($formData['type'] == 'General') //A general search is being performed
            {
                $query .= ') AND type = 0'; //Set the type to search for

                //Create variables to store the min and max prices
                $minPrice = -1;
                $maxPrice = -1;

                if(isset($formData['price_min']) && is_numeric($formData['price_min']) && $formData['price_min'] > 0 && $formData['price_min'] <= 1000000000) //A valid min was specified
                {
                    $minPrice = $formData['price_min']; //Set the min
                }

                if(isset($formData['price_max']) && is_numeric($formData['price_max']) && $formData['price_max'] > 0 && $formData['price_max'] <= 1000000000) //A valid max was specified
                {
                    $maxPrice = $formData['price_max']; //Set the max
                }

                if($minPrice > 0) //The min price is valid
                {
                    $query .= ' AND price >= ' . $minPrice; //Add the min price to the query
                }

                if($maxPrice > 0 && $maxPrice >= $minPrice) //The max price is valid
                {
                    $query .= ' AND price <= ' . $maxPrice; //Add the max price to the query
                }

                $query .= ' ORDER BY id DESC'; //Finish building the query
            }
            else if($formData['type'] == 'Vehicle') //A vehicle search is being performed
            {
                $query .= ') AND type = 1'; //Set the type to search for

                //Create variables to store the min and max prices
                $minPrice = -1;
                $maxPrice = -1;

                if(isset($formData['price_min']) && is_numeric($formData['price_min']) && $formData['price_min'] > 0 && $formData['price_min'] <= 1000000000) //A valid min was specified
                {
                    $minPrice = $formData['price_min']; //Set the min
                }

                if(isset($formData['price_max']) && is_numeric($formData['price_max']) && $formData['price_max'] > 0 && $formData['price_max'] <= 1000000000) //A valid max was specified
                {
                    $maxPrice = $formData['price_max']; //Set the max
                }

                if($minPrice > 0) //The min price is valid
                {
                    $query .= ' AND price >= ' . $minPrice; //Add the min price to the query
                }

                if($maxPrice > 0 && $maxPrice >= $minPrice) //The max price is valid
                {
                    $query .= ' AND price <= ' . $maxPrice; //Add the max price to the query
                }

                //Create variables to store the min and max years
                $minYear = -1;
                $maxYear = -1;

                if(isset($formData['year_min']) && is_numeric($formData['year_min']) && $formData['year_min'] >= 1908 && $formData['year_min'] <= intval(date('Y')) + 1) //A valid min was specified
                {
                    $minYear = $formData['year_min']; //Set the min
                }

                if(isset($formData['year_max']) && is_numeric($formData['year_max']) && $formData['year_max'] >= 1908 && $formData['year_max'] <= intval(date('Y')) + 1) //A valid max was specified
                {
                    $maxYear = $formData['year_max']; //Set the max
                }

                if($minYear > 0) //The min year is valid
                {
                    $query .= ' AND year >= ' . $minYear; //Add the min year to the query
                }

                if($maxYear > 0 && $maxYear >= $minYear) //The max year is valid
                {
                    $query .= ' AND year <= ' . $maxYear; //Add the max year to the query
                }

                //Create variables to store the min and max kilometers
                $minKM = -1;
                $maxKM = -1;

                if(isset($formData['km_min']) && is_numeric($formData['km_min']) && $formData['km_min'] >= 0 && $formData['km_min'] <= 2000000) //A valid min was specified
                {
                    $minKM = $formData['km_min']; //Set the min
                }

                if(isset($formData['km_max']) && is_numeric($formData['km_max']) && $formData['km_max'] >= 0 && $formData['km_max'] <= 2000000) //A valid max was specified
                {
                    $maxKM = $formData['km_max']; //Set the max
                }

                if($minKM > 0) //The min kilometers is valid
                {
                    $query .= ' AND kilometers >= ' . $minKM; //Add the min kilometers to the query
                }

                if($maxKM > 0 && $maxKM >= $minKM) //The max kilometers is valid
                {
                    $query .= ' AND kilometers <= ' . $maxKM; //Add the max kilometers to the query
                }

                if(isset($formData['trans']) && $formData['trans'] == 'Automatic') //Automatic transmission was chosen
                {
                    $query .= ' AND transmission = \'Automatic\''; //Add the transmission to the query
                }
                else if(isset($formData['trans']) && $formData['trans'] == 'Manual') //Manual transmission was chosen
                {
                    $query .= ' AND transmission = \'Manual\'';  //Add the transmission to the query
                }

                $query .= ' ORDER BY id DESC'; //Finish building the query
            }
            else if($formData['type'] == 'Property') //A property search is being performed
            {
                $query .= ') AND type = 2'; //Set the type to search for

                //Create variables to store the min and max prices
                $minPrice = -1;
                $maxPrice = -1;

                if(isset($formData['price_min']) && is_numeric($formData['price_min']) && $formData['price_min'] > 0 && $formData['price_min'] <= 1000000000) //A valid min was specified
                {
                    $minPrice = $formData['price_min']; //Set the min
                }

                if(isset($formData['price_max']) && is_numeric($formData['price_max']) && $formData['price_max'] > 0 && $formData['price_max'] <= 1000000000) //A valid max was specified
                {
                    $maxPrice = $formData['price_max']; //Set the max
                }

                if($minPrice > 0) //The min price is valid
                {
                    $query .= ' AND price >= ' . $minPrice; //Add the min price to the query
                }

                if($maxPrice > 0 && $maxPrice >= $minPrice) //The max price is valid
                {
                    $query .= ' AND price <= ' . $maxPrice; //Add the max price to the query
                }

                if(isset($formData['propType']) && $formData['propType'] == 'For Rent') //For Rent was chosen
                {
                    $query .= ' AND propertyType = \'For Rent\''; //Add the type to the query
                }
                else if(isset($formData['propType']) && $formData['propType'] == 'For Sale') //For Sale was chosen
                {
                    $query .= ' AND propertyType = \'For Sale\''; //Add the type to the query
                }

                $query .= ' ORDER BY id DESC'; //Finish building the query
            }

            $adQuery = $this->database->query($query, $newParams); //Perform the query

            if($this->database->rowCount($adQuery)) //Ads were returned
            {
                $data = array('success' => true, 'ads' => array()); //Create the array that will be returned
                $adFactory = new AdCreator($this->database); //Create an ad factory

                while($adRow = $this->database->fetch($adQuery)) //Loop through the query results
                {
                    $newAd = $adFactory->factoryMethod($adRow['type']); //Create a new add of the approriate type
                    $data['ads'][] = $newAd->getAdData($adRow['id']); //Get and push the ad on to the data array
                }

                exit(print json_encode($data)); //Output the data array
            }
            else //No ads were return
            {
                exit(print json_encode(array('success' => false, 'message' => '<h3>No advertisements found!</h3>'))); //Output a no results message
            }
        }
        else //An invalid search was provided
        {
            exit(print json_encode(array('success' => false, 'message' => '<h3>No advertisements found!</h3>'))); //Output a no results message
        }
    }
}