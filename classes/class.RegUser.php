<?php
if(!defined('ALLOW_ACCESS')) exit(header('Location: /')); //Only allow access to this file through the router

//The RegUser class extends the UnregUser class, providing the ability to post, edit and delete ads and rate other users
class RegUser extends UnregUser
{
    public function logout() //Log the user out of the system
    {
        session_destroy(); //Destroy the user's session
        exit(header('Location: /')); //Send the user back to the index page and kill the script
    }

    public function view($view) //Show the specified view
    {
        if($this->validSession()) //The user is properly logged in
        {
            if($view == 'myprofile')
            {
                $this->getUserBy('id', $_SESSION['id']); //Get the user's info
                $rating = $this->getUserRating($_SESSION['id']); //Get the user's rating

                include(__DIR__ . '/../pages/myprofile.php'); //Display the my profile view
            }
            else if($view == 'search')
            {
                include(__DIR__ . '/../pages/search.php'); //Display the search view
            }
            else if($view == 'messages')
            {
                include(__DIR__ . '/../pages/conversations.php'); //Display the conversations view
            }
            else if($view == 'convo' && isset($_GET['id']))
            {
                $convoQuery = $this->database->query('SELECT * FROM conversations WHERE id = ? AND (user1 = ? OR user2 = ?)', $_GET['id'], $_SESSION['id'], $_SESSION['id']); //Check if the user is part of the conversation

                if($this->database->rowCount($convoQuery)) //The query was successful and returned at least one row
                {
                    include(__DIR__ . '/../pages/convo.php'); //Display the single conversation view
                }
                else //The query returned no rows
                {
                    exit(header('Location: /')); //Send the user back to the homepage
                }
            }
            else if($view == 'profile' && isset($_GET['id']))
            {
                if($_GET['id'] == $_SESSION['id']) //The user is logged in and trying to view their profile incorrectly
                {
                    exit(header('Location: /myprofile')); //Send the user back to their own profile
                }

                if($this->getUserBy('id', $_GET['id'])) //Get the user's info
                {
                    $id = $this->data['id']; //Set the ID
                    $rating = $this->getUserRating($_GET['id']); //Set the rating

                    include(__DIR__ . '/../pages/profile.php'); //Display the profile view
                }
                else //The user was not found
                {
                    exit(header('Location: /')); //Send the user back to the index page and kill the script
                }
            }
            else if($view == 'post')
            {
                $limit = 10; //Set the default limit

                if($_SESSION['premium']) // The user is a premium user
                {
                    $limit *= 2; //Double the limit
                }

                $limitReached = $this->adLimitReached($_SESSION['id']); //Check if the user has reached their ad limit

                include(__DIR__ . '/../pages/post.php'); //Display the ad type choice view
            }
            else if($view == 'general' || $view == 'vehicle' || $view == 'property')
            {
                if(!$this->adLimitReached($_SESSION['id'])) //The user has not reached their ad limit
                {
                    $type = -1; //Stores the type based on the requested view

                    //Convert requested view to a type
                    if($view == 'general') $type = 0;
                    else if($view == 'vehicle') $type = 1;
                    else if($view == 'property') $type = 2;

                    $adFactory = new AdCreator($this->database); //Create an ad factory
                    $adFactory->factoryMethod($type)->showPostView(); //Create an ad of the approriate type
                }
                else //The user has reached their ad limit
                {
                    exit(header('Location: /')); //Send the user back to the homepage and kill the script
                }
            }
            else if($view == 'edit' && isset($_GET['id']))
            {
                $adFactory = new AdCreator($this->database); //Create an ad factory
                $editAd = $adFactory->factoryMethod($this->getAdType($_GET['id'])); //Create an ad of the approriate type

                if($editAd) //An ad object was created
                {
                    $editAd->showEditView($_SESSION['id'], $_GET['id']);
                }
                else //An ad object was not created (invalid type/ad not found)
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

                    $viewAd->showAdView($_GET['id'], $posterName, true); //Show the ad view
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
        else //The user is not logged in properly (this should never happen here)
        {
            exit(header('Location: /')); //Send the user back to the homepage and kill the script
        }
    }

    public function rateUser($ratingData) //Give the specified user the specified rating
    {
        if(!$this->validSession() || !isset($ratingData['userid']) || !isset($ratingData['rating']) || $_SESSION['id'] == $ratingData['userid']) //The user is not logged in or is trying to rate themself or a variable is missing
        {
            exit(); //Kill the script
        }

        $oldRating = -1; //Initialize the old rating value

        $selQuery = $this->database->query('SELECT rating FROM ratings WHERE raterid = ? AND userid = ?', $_SESSION['id'], $ratingData['userid']); //Check if the user has already rated the specified user

        if($this->database->rowCount($selQuery)) //The user has already rated the specified user
        {
            //Get and store the user's old rating
            $selRow = $this->database->fetch($selQuery);
            $oldRating = $selRow['rating'];
        }

        $delQuery = $this->database->query('DELETE FROM ratings WHERE raterid = ? AND userid = ?', $_SESSION['id'], $ratingData['userid']); //Delete the user's old rating

        if($delQuery && $oldRating != $ratingData['rating']) //The user is giving the specified user a new rating
        {
            $this->database->query('INSERT INTO ratings (userid, raterid, rating) VALUES (?, ?, ?)', $ratingData['userid'], $_SESSION['id'], $ratingData['rating']); //Insert the new rating into the database
        }

        exit(print json_encode(array('newrating' => $this->getUserRating($ratingData['userid'])))); //Get the specified user's new rounded average rating and output it
    }

    public function postAd($type, $formData) //Post an ad of the specified type
    {
        if($this->validSession()) //The user is logged in
        {
            if($type >= 0 && $type <= 2 && !$this->adLimitReached($_SESSION['id'])) //A valid type was specified and the user doesn't have too many ads
            {
                header('Content-type: application/json; charset=utf-8'); //Set the content type to JSON

                $adFactory = new AdCreator($this->database); //Create an ad factory
                $newAd = $adFactory->factoryMethod($type); //Create a new ad of the approriate type

                exit(print $newAd->postAd($_SESSION['id'], $formData)); //Post the ad and output the result
            }
            else //A valid type wasn't specified or the user has too many ads
            {
                exit(print json_encode(array('error' => 'An error occurred.', 'success' => false))); //Output an error message
            }
        }
        else //The user is not logged in
        {
            exit(print json_encode(array('error' => 'An error occurred.', 'success' => false))); //Output an error message
        }
    }

    public function editAd($type, $adID, $formData) //Edit the specified ad
    {
        if($this->validSession()) //The user is logged in
        {
            if($type >= 0 && $type <= 2) //A valid type was specified
            {
                header('Content-type: application/json; charset=utf-8'); //Set the content type to JSON

                $adFactory = new AdCreator($this->database); //Create an ad factory
                $newAd = $adFactory->factoryMethod($type); //Create a new ad of the approriate type

                exit(print $newAd->editAd($_SESSION['id'], $adID, $formData)); //Post the ad and output the result
            }
            else //A valid type wasn't specified
            {
                exit(print json_encode(array('error' => 'An error occurred.', 'success' => false))); //Output an error message
            }
        }
        else //The user is not logged in
        {
            exit(print json_encode(array('error' => 'An error occurred.', 'success' => false))); //Output an error message
        }
    }

    public function adLimitReached($userID) //Return whether or not the user has hit their ad limit
    {
        $premQuery = $this->database->query('SELECT premium FROM users WHERE id = ? LIMIT 1', $userID); //Get the user's premium status
        $premRow = $this->database->fetch($premQuery); //Fetch the user row
        $countQuery = $this->database->query('SELECT id FROM advertisements WHERE userid = ? AND deleted = 0', $userID); //Get all of the user's active ads
        $limit = 10; //Set the default ad limit

        if($premRow['premium']) //The user is a premium member
        {
            $limit = $limit * 2; //Double the limit
        }

        if($this->database->rowCount($countQuery) >= $limit) //The user has reached their limit
        {
            return true;
        }
        else //The user hasn't reached their limit
        {
            return false;
        }
    }

    public function checkIfBanned()
    {
        if($this->validSession()) //The user has a valid session
        {
            $this->getUserBy('id', $_SESSION['id']); //Get the user's data

            if($this->data['banned'] == 1) //The user is banned
            {
                $this->logout(); //Log the user out
            }
        }
    }

    public function deleteAd($adID) //Delete the specified ad if it belongs to the user
    {
        if($this->validSession()) //The user has a valid session
        {
            $this->database->query('UPDATE advertisements SET deleted = 1 WHERE userid = ? AND id = ?', $_SESSION['id'], $adID); //Delete the ad

            exit(header('Location: /myprofile')); //Send the user back to their profile and kill the script
        }

        exit(header('Location: /')); //Send the user back to the homepage and kill the script
    }

    public function getConversations() //Get the specified user's conversations
    {
        if($this->validSession()) //The user has a valid session
        {
            $convoQuery = $this->database->query('SELECT * FROM conversations WHERE user1 = ? OR user2 = ?', $_SESSION['id'], $_SESSION['id']); //Get the user's conversations from the database

            if($this->database->rowCount($convoQuery)) //The query was successful and returned at least one row
            {
                $data = array('success' => true, 'msgs' => array()); //Create the data array that will be returned

                while($convoRow = $this->database->fetch($convoQuery)) //Loop through the query results
                {
                    $msgQuery = $this->database->query('SELECT * FROM messages WHERE convo = ? ORDER BY id DESC LIMIT 1', $convoRow['id']); //Get the latest message from the conversation

                    if($this->database->rowCount($msgQuery)) //The message query returned new messages
                    {
                        $newestMessageID = -1; //Stores the ID of the newest message

                        while($msgRow = $this->database->fetch($msgQuery)) //Loop through the messages
                        {
                            if($msgRow['id'] > $newestMessageID) //This ID is newer
                            {
                                $newestMessageID = $msgRow['id']; //Update the newest ID
                            }

                            if($msgRow['sender'] == $_SESSION['id']) //The user is sender
                            {
                                $msgRow['type'] = 'myMessageBackground'; //Set the message type

                                if($convoRow['user1'] == $_SESSION['id']) //The user is user 1 of the conversation
                                {
                                    $msgRow['sender'] = $convoRow['user1Name']; //Set the sender name
                                }
                                else //The user is user 2 of the conversation
                                {
                                    $msgRow['sender'] = $convoRow['user2Name']; //Set the sender name
                                }
                            }
                            else //The other user is the sender
                            {
                                $msgRow['type'] = 'receivedMessageBackground'; //Set the message type

                                if($convoRow['user1'] == $_SESSION['id']) //The user is user 1 of the conversation
                                {
                                    $msgRow['sender'] = $convoRow['user2Name']; //Set the sender name
                                }
                                else //The user is user 2 of the conversation
                                {
                                    $msgRow['sender'] = $convoRow['user1Name']; //Set the sender name
                                }
                            }

                            if($convoRow['user1'] == $_SESSION['id']) //The user is user 1 of the conversation
                            {
                                $msgRow['otherName'] = $convoRow['user2Name']; //Set the other name
                            }
                            else //The user is user 2 of the conversation
                            {
                                $msgRow['otherName'] = $convoRow['user1Name']; //Set the other name
                            }

                            $msgRow['time'] = date('g:iA - F jS, Y', $msgRow['time']); //Format the message time

                            $data['msgs'][] = $msgRow; //Add the message to the array
                        }

                        if($convoRow['user1'] == $_SESSION['id']) //The user is user 1 of the conversation
                        {
                            $this->database->query('UPDATE messages SET in1ConvoView = 1 WHERE convo = ? AND id <= ?', $convoRow['id'], $newestMessageID); //Set all messages as shown in the conversation view for user 1
                        }
                        else //The user is user 2 of the conversation
                        {
                            $this->database->query('UPDATE messages SET in2ConvoView = 1 WHERE convo = ? AND id <= ?', $convoRow['id'], $newestMessageID); //Set all messages as shown in the conversation view for user 2
                        }
                    }
                }

                exit(print json_encode($data)); //Output the data array as JSON
            }

            exit(print json_encode(array('success' => false, 'message' => '<h3>No conversations to display!</h3>'))); //Output the data array
        }

        exit(header('Location: /')); //Send the user back to the homepage and kill the script
    }

    public function pollConversations() //Poll for conversations with new messages
    {
        if($this->validSession()) //The user has a valid session
        {
            $userID = $_SESSION['id']; //Save the user's ID before closing the session file
            session_write_close(); //Close the session file so we don't keep it open while the loop runs
            $startTime = time(); //Get the current time
            $subject = new MessagesSubject(); //Create a new subject

            $convoIDs = array(); //Stores the IDs of the user's conversations
            $convoQuery = $this->database->query('SELECT * FROM conversations WHERE user1 = ? OR user2 = ?', $userID, $userID); //Get all of the user's conversations

            if($this->database->rowCount($convoQuery)) //The query was successful and returned a row
            {
                while($convoRow = $this->database->fetch($convoQuery)) //Loop through the query results
                {
                    $convoIDs[] = $convoRow['id']; //Put the conversation ID on the array
                    $subject->attach(new ConversationObserver($this->database, $convoRow['id'], $userID, 0)); //Attach a conversation observer to the subject for each conversation
                }
            }

            while(1) //Loop forever
            {
                if((time() - $startTime) >= 20) //The script has run for 20 seconds
                {
                    exit(print json_encode(array('success' => false))); //Kill the script
                }

                $newMessages = array(); //Stores the IDs of the conversations with new messages
                $convoQuery = $this->database->query('SELECT * FROM conversations WHERE user1 = ? OR user2 = ?', $userID, $userID); //Get all of the user's conversations

                if($this->database->rowCount($convoQuery)) //The query was successful and returned a row
                {
                    while($convoRow = $this->database->fetch($convoQuery)) //Loop through the query results
                    {
                        if(!in_array($convoRow['id'], $convoIDs)) //The conversation is new
                        {
                            $convoIDs[] = $convoRow['id']; //Put the conversation ID on the array
                            $subject->attach(new ConversationObserver($this->database, $convoRow['id'], $userID, 0)); //Attach a conversation observer to the subject for the new conversation
                        }

                        if($convoRow['user1'] == $userID) //The user is user 1 of the conversation
                        {
                            $msgQuery = $this->database->query('SELECT NULL FROM messages WHERE in1ConvoView = 0 AND convo = ?', $convoRow['id']); //Check if the conversation has new messages for the user
                        }
                        else //The user is user 2 of the conversation
                        {
                            $msgQuery = $this->database->query('SELECT NULL FROM messages WHERE in2ConvoView = 0 AND convo = ?', $convoRow['id']); //Check if the conversation has new messages for the user
                        }

                        if($this->database->rowCount($msgQuery)) //The conversation has new messages for the user
                        {
                            $newMessages[] = $convoRow['id']; //Add the conversation ID to the array
                        }
                    }

                    $subject->setState($newMessages); //Set the state of the subject
                    $subject->notify(); //Notify the subject's observers
                }

                sleep(1); //Sleep for 1 second so we don't destroy the server
            }
        }

        exit(header('Location: /')); //Send the user back to the homepage and kill the script
    }

    public function getMessages($convoID)
    {
        if($this->validSession()) //The user has a valid session
        {
            $convoQuery = $this->database->query('SELECT * FROM conversations WHERE id = ? AND (user1 = ? OR user2 = ?)', $convoID, $_SESSION['id'], $_SESSION['id']); //Get the specified conversation from the database
            $convoRow = $this->database->fetch($convoQuery); //Fetch the conversation row
            $data = array('success' => true, 'msgs' => array()); //Create the data array for holding the messages

            $msgQuery = $this->database->query('SELECT * FROM messages WHERE convo = ? ORDER BY id ASC', $convoID); //Get all of the conversation's messages

            if($this->database->rowCount($msgQuery)) //The query was successful and returned a row
            {
                $newestMessageID = -1; //Stores the ID of the newest message

                while($msgRow = $this->database->fetch($msgQuery)) //Loop through the messages
                {
                    if($msgRow['id'] > $newestMessageID) //This ID is newer
                    {
                        $newestMessageID = $msgRow['id']; //Update the newest ID
                    }

                    if($msgRow['sender'] == $_SESSION['id']) //The user is sender
                    {
                        $msgRow['type'] = 'myMessageBackground'; //Set the message type

                        if($convoRow['user1'] == $_SESSION['id']) //The user is user 1 of the conversation
                        {
                            $msgRow['sender'] = $convoRow['user1Name']; //Set the sender name
                            $msgRow['otherName'] = $convoRow['user2Name']; //Set the other name
                        }
                        else //The user is user 2 of the conversation
                        {
                            $msgRow['sender'] = $convoRow['user2Name']; //Set the sender name
                            $msgRow['otherName'] = $convoRow['user1Name']; //Set the other name
                        }
                    }
                    else //The other user is the sender
                    {
                        $msgRow['type'] = 'receivedMessageBackground'; //Set the message type

                        if($convoRow['user1'] == $_SESSION['id']) //The user is user 1 of the conversation
                        {
                            $msgRow['sender'] = $convoRow['user2Name']; //Set the sender name
                            $msgRow['otherName'] = $convoRow['user2Name']; //Set the other name
                        }
                        else //The user is user 2 of the conversation
                        {
                            $msgRow['sender'] = $convoRow['user1Name']; //Set the sender name
                            $msgRow['otherName'] = $convoRow['user2Name']; //Set the other name
                        }
                    }

                    $msgRow['time'] = date('g:iA - F jS, Y', $msgRow['time']); //Format the message time

                    $data['msgs'][] = $msgRow; //Add the message to the array
                }

                if($convoRow['user1'] == $_SESSION['id']) //The user is user 1 of the conversation
                {
                    $this->database->query('UPDATE messages SET in1MessageView = 1 WHERE convo = ? AND id <= ?', $convoID, $newestMessageID); //Set all messages as shown in the message view for user 1
                }
                else //The user is user 2 of the conversation
                {
                    $this->database->query('UPDATE messages SET in2MessageView = 1 WHERE convo = ? AND id <= ?', $convoID, $newestMessageID); //Set all messages as shown in the message view for user 2
                }

                exit(print json_encode($data)); //Output the data array as JSON
            }

            exit(print json_encode(array('success' => false))); //Output the data array
        }

        exit(header('Location: /')); //Send the user back to the homepage and kill the script
    }

    public function pollMessages($convoID) //Poll for new messages in the specified conversation
    {
        if($this->validSession()) //The user has a valid session
        {
            $userID = $_SESSION['id']; //Save the user's ID before closing the session file
            session_write_close(); //Close the session file so we don't keep it open while the loop runs
            $startTime = time(); //Get the current time
            $subject = new MessagesSubject(); //Create a new subject

            $convoQuery = $this->database->query('SELECT * FROM conversations WHERE id = ? AND (user1 = ? OR user2 = ?)', $convoID, $userID, $userID); //Get the specified conversation

            if($this->database->rowCount($convoQuery)) //The query was successful and returned a row
            {
                $convoRow = $this->database->fetch($convoQuery); //Fetch the conversation row
                $subject->attach(new ConversationObserver($this->database, $convoRow['id'], $userID, 1)); //Attach a conversation observer to the subject for the conversation
            }

            while(1) //Loop forever
            {
                if((time() - $startTime) >= 20) //The script has run for 20 seconds
                {
                    exit(print json_encode(array('success' => false))); //Kill the script
                }

                $newMessages = array(); //Stores the IDs of the conversations with new messages

                if($this->database->rowCount($convoQuery)) //The query was successful and returned a row
                {
                    if($convoRow['user1'] == $userID) //The user is user 1 of the conversation
                    {
                        $msgQuery = $this->database->query('SELECT NULL FROM messages WHERE in1MessageView = 0 AND convo = ?', $convoRow['id']); //Check if the conversation has new messages for the user
                    }
                    else //The user is user 2 of the conversation
                    {
                        $msgQuery = $this->database->query('SELECT NULL FROM messages WHERE in2MessageView = 0 AND convo = ?', $convoRow['id']); //Check if the conversation has new messages for the user
                    }

                    if($this->database->rowCount($msgQuery)) //The conversation has new messages for the user
                    {
                        $newMessages[] = $convoRow['id']; //Add the conversation ID to the array
                    }

                    $subject->setState($newMessages); //Set the state of the subject
                    $subject->notify(); //Notify the subject's observers
                }

                sleep(1); //Sleep for 1 second so we don't destroy the server
            }
        }

        exit(header('Location: /')); //Send the user back to the homepage and kill the script
    }

    public function sendMessage($formData) //Send the specified message to the specified conversation
    {
        if($this->validSession() && isset($formData['convo']) && isset($formData['message']) && strlen(trim($formData['message']))) //The user is logged in and a valid message was sent
        {
            $convoQuery = $this->database->query('SELECT NULL FROM conversations WHERE id = ? AND (user1 = ? OR user2 = ?)', $formData['convo'], $_SESSION['id'], $_SESSION['id']); //Get the specified conversation

            if($this->database->rowCount($convoQuery)) //The query was successful and returned a row
            {
                $this->database->query('INSERT INTO messages (convo, sender, message, time) VALUES (?, ?, ?, ?)', $formData['convo'], $_SESSION['id'], $formData['message'], time()); //Send the message
            }
        }
    }

    public function openConvo($userID) //Get an existing conversation between users or start a new one
    {
        if($this->validSession()) //The user is logged in
        {
            $convoQuery = $this->database->query('SELECT id FROM conversations WHERE (user1 = ? AND user2 = ?) OR (user1 = ? AND user2 = ?)', $userID, $_SESSION['id'], $_SESSION['id'], $userID); //Check if the user already has a conversation with the specified user

            if($this->database->rowCount($convoQuery)) //The query was successful and returned a row
            {
                $convoRow = $this->database->fetch($convoQuery); //Fetch the conversation row
                exit(print json_encode(array('success' => true, 'id' => $convoRow['id']))); //Return the conversation ID
            }
            else
            {
                $this->getUserBy('id', $_SESSION['id']); //Get the user's info
                $name1 = $this->data['username']; //Store the user's name
                $this->getUserBy('id', $userID); //Get the specified user's info
                $name2 = $this->data['username']; //Store the specified user's name

                $this->database->query('INSERT INTO conversations (user1, user2, user1Name, user2Name) VALUES (?, ?, ?, ?)', $_SESSION['id'], $userID, $name1, $name2); //Start the conversation

                $convoQuery = $this->database->query('SELECT id FROM conversations WHERE user1 = ? AND user2 = ?', $_SESSION['id'], $userID); //Get the new conversation ID
                $convoRow = $this->database->fetch($convoQuery); //Fetch the conversation row
                exit(print json_encode(array('success' => true, 'id' => $convoRow['id']))); //Return the conversation ID
            }

            exit(print json_encode(array('success' => false))); //Return an error
        }

        exit(print json_encode(array('success' => false))); //Return an error
    }
}