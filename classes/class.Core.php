<?php
if(!defined('ALLOW_ACCESS')) exit(header('Location: /')); //Only allow access to this file through the router

//The Core class provides the core functionality for the entire system by creating user objects, routing requests, uploading images, and expiring old ads
class Core
{
    private $database, //Stores the database connection
            $user; //Stores the user object

    public function __construct($database) //Set up the core by copying the specified database connection and creating a new user object
    {
        session_start(); //Start a PHP session
        mb_internal_encoding('UTF-8'); //Set the default internal encoding
        //date_default_timezone_set('UTC'); //Set the default timezone to UTC to make life easy
        date_default_timezone_set('America/Regina'); //Set the default timezone to Regina

        $this->database = $database; //Copy the database connection

        /*
            We always need one of the three user objects when someone is accessing the system
            so we can determine what functionality they have. To do this we check if they have a
            PHP session containing their rank, and if they do we create the appropriate user object,
            otherwise we create a simple customer object.
        */
        if(isset($_SESSION['rank']) && $_SESSION['rank'] == 1) //An admin is accessing the system
        {
            $this->user = new Admin($this->database); //Create an Admin object for extra functionality
        }
        else if(isset($_SESSION['rank']) && $_SESSION['rank'] == 0) //A registered user is accessing the system
        {
            $this->user = new RegUser($this->database); //Create a RegUser object for extra functionality
            $this->user->checkIfBanned(); //Check if the user is banned, and if so, log them out
        }
        else //An unregistered user is trying to access the system
        {
            $this->user = new UnregUser($this->database); //Create an UnregUser object for extra functionality
        }
    }

	public function route() //Route the user's request to the appropriate function or page
	{
        $request = array_slice(explode('/', str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI'])), 1); //Split the request into an array

        if($this->user->validSession()) //The user has a valid session
        {
            if($request[0] == 'logout')
            {
                $this->user->logout(); //Log the user out
            }
            else if($request[0] == 'myprofile' || $request[0] == 'profile' || $request[0] == 'messages' || $request[0] == 'post' || $request[0] == 'general' || $request[0] == 'vehicle' || $request[0] == 'property' || $request[0] == 'edit' || $request[0] == 'ad' || $request[0] == 'search' || $request[0] == 'messages' || $request[0] == 'convo')
            {
                $this->user->view($request[0]); //Send the user to the appropriate view
            }
            else if($request[0] == 'delete' && isset($_GET['id']))
            {
                $this->user->deleteAd($_GET['id']); //Delete the specified ad
            }
            else if($request[0] == 'system') //System functions
            {
                if($request[1] == 'myads')
                {
                    $this->user->getUserAds($_SESSION['id']); //Get the user's own ads
                }
                else if($request[1] == 'userads' && isset($_GET['id']))
                {
                    $this->user->getUserAds($_GET['id']); //Get the specified user's ads
                }
                else if($request[1] == 'latestads') //Get the latest ads
                {
                    $this->user->getLatestAds();
                }
                else if($request[1] == 'rate')
                {
                    $this->user->rateUser($_POST); //Give the specified user the specified rating
                }
                else if($request[1] == 'search')
                {
                    $this->user->searchAds($_POST); //Perform a search for ads
                }
                else if($request[1] == 'generalad')
                {
                    $this->user->postAd(0, $_POST); //Post a general ad
                }
                else if($request[1] == 'vehiclead')
                {
                    $this->user->postAd(1, $_POST); //Post a vehicle ad
                }
                else if($request[1] == 'propertyad')
                {
                    $this->user->postAd(2, $_POST); //Post a property ad
                }
                else if($request[1] == 'editgeneralad' && isset($_GET['adid']))
                {
                    $this->user->editAd(0, $_GET['adid'], $_POST); //Edit the specified general ad
                }
                else if($request[1] == 'editvehiclead' && isset($_GET['adid']))
                {
                    $this->user->editAd(1, $_GET['adid'], $_POST); //Edit the specified vehicle ad
                }
                else if($request[1] == 'editpropertyad' && isset($_GET['adid']))
                {
                    $this->user->editAd(2, $_GET['adid'], $_POST); //Edit the specified property ad
                }
                else if($request[1] == 'conversations')
                {
                    $this->user->getConversations(); //Get the user's conversation list
                }
                else if($request[1] == 'pollconversations')
                {
                    $this->user->pollConversations(); //Poll for new or updated conversations
                }
                else if($request[1] == 'messages' && isset($_GET['convoid']))
                {
                    $this->user->getMessages($_GET['convoid']); //Get the specified conversation's messages
                }
                else if($request[1] == 'pollmessages' && isset($_GET['convoid']))
                {
                    $this->user->pollMessages($_GET['convoid']); //Poll for new messages for the specified conversation
                }
                else if($request[1] == 'send')
                {
                    $this->user->sendMessage($_POST); //Send the specified message to the specified conversation
                }
                else if($request[1] == 'openconvo' && isset($_GET['userid']))
                {
                    $this->user->openConvo($_GET['userid']); //Open a conversation with the specified user
                }
                else if($request[1] == 'ban' && isset($_GET['userid']) && $_SESSION['rank'] > 0)
                {
                    $this->user->banUser($_GET['userid']); //Ban the specified user
                }
                else //Invalid request
                {
                    exit(header('Location: /')); //Send the user back to the index page and kill the script
                }
            }
            else if($request[0] == 'core') //System function request
            {
                if($request[1] == 'upload')
                {
                    $this->uploadImages(); //Upload images
                }
                else if($request[1] == 'expireads')
                {
                    $this->expireAds(); //Expire old ads
                }
                else //Invalid request
                {
                    exit(header('Location: /')); //Send the user back to the index page and kill the script
                }
            }
            else if($request[0] != '') //Invalid request
            {
                exit(header('Location: /')); //Send the user back to the index page and kill the script
            }
            else
            {
                include(__DIR__ . '/../pages/index.php'); //Display the index page
            }
        }
        else //The user doesn't have a valid session
        {
            if($request[0] == 'login' || $request[0] == 'register' || $request[0] == 'profile' || $request[0] == 'ad' || $request[0] == 'search')
            {
                $this->user->view($request[0]); //Send the user to the appropriate view
            }
            else if($request[0] == 'system') //System functions
            {
                if($request[1] == 'login')
                {
                    $this->user->login($_POST); //Log the user in
                }
                else if($request[1] == 'register')
                {
                    $this->user->register($_POST); //Register the user
                }
                else if($request[1] == 'userads')
                {
                    $this->user->getUserAds($_GET['id']); //Get the specified user's ads
                }
                else if($request[1] == 'latestads')
                {
                    $this->user->getLatestAds(); //Get the latest ads
                }
                else if($request[1] == 'search')
                {
                    $this->user->searchAds($_POST); //Perform a search for ads
                }
            }
            else if($request[0] == 'core') //System function request
            {
                if($request[1] == 'expireads')
                {
                    $this->expireAds(); //Expire old ads
                }
                else //Invalid request
                {
                    exit(header('Location: /')); //Send the user back to the index page and kill the script
                }
            }
            else if($request[0] != '') //Invalid request
            {
                exit(header('Location: /')); //Send the user back to the index page and kill the script
            }
            else
            {
                include(__DIR__ . '/../pages/index.php'); //Display the index page
            }
        }
	}

    function uploadImages() //Upload images to the /uploads folder with AJAX
    {
        header('Content-type: application/json; charset=utf-8'); //Set the content type to JSON
        $response = array(); //Stores the response to send back to the requesting script

        if(count($_FILES)) //A file was specified
        {
            for($i = 0; $i < count($_FILES); $i++) //Loop through all of the uploaded files
            {
                $directory = __DIR__ . '/../uploads/'; //Set the target directory to /uploads
                $extension = strtolower(pathinfo(basename($_FILES[$i]['name']), PATHINFO_EXTENSION)); //Get the file extension
                $file = round(microtime(true)) . '.' . $extension; //Generate a file name from the current time
                $valid = true; //Mark the file to valid before we check it

                if(getimagesize($_FILES[$i]['tmp_name']) == false) //If we can't get the image size, the user didn't upload an image
                {
                    $valid = false; //Mark the file as invalid
                }

                if ($_FILES[$i]['size'] > 3000000) //The image is too large (> 3MB)
                {
                    $valid = false; //Mark the file as invalid
                }

                if($extension != 'png' && $extension != 'jpg' && $extension != 'jpeg') //The image has an invalid extension
                {
                    $valid = false; //Mark the file as invalid
                }

                while (file_exists(__DIR__ . '/../uploads/' . $file)) //If somehow (magic?) another file is uploaded at the exact same time and has the same name, we need to loop until we find a new valid name
                {
                    $file = round(microtime(true)) . '.' . $extension; //Generate a new file name from the current time
                }

                if($valid && move_uploaded_file($_FILES[$i]['tmp_name'], $directory . $file)) //The image is valid and was successfully moved to /uploads
                {
                    $response[] = array($i => $file, 'success' => true); //Add the new image name to the response array
                }
                else //The image was not uploaded
                {
                   exit(print json_encode(array(0 => array('success' => false)))); //Return a failure state
                }
            }

            print json_encode($response); //Output the image names in an array
        }
        else //A file was not specified
        {
            exit(print json_encode(array(0 => array('success' => false)))); //Return a failure state
        }
    }

    function expireAds() //Expire ads that are more than 60 days old
    {
        $time = time() - 5184000; //Get the current unix timestamp - 60 days

        $this->database->query('UPDATE advertisements SET deleted = 1 WHERE time < ?', $time); //Expire old ads

        exit(header('Location: /')); //Send the user back to the index page and kill the script
    }
}