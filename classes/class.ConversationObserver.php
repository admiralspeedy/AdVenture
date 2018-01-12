<?php
if(!defined('ALLOW_ACCESS')) exit(header('Location: /')); //Only allow access to this file through the router

//The ConversationObserver class implements the Observer interface
class ConversationObserver implements Observer
{
    private $database, //Stores the database connection
            $convoID, //Stores the conversation ID
            $userID, //Stores the user ID
            $viewMode, //Stores the view mode for returning the correct type of data
            $state; //Stores the state of the observer, which is pulled from the subject

    public function __construct($database, $convoID, $userID, $viewMode) //Set up the customer by copying the specified database connection
    {
        $this->database = $database; //Copy the database connection
        $this->convoID = $convoID; //Copy the conversation ID
        $this->userID = $userID; //Copy the user ID
        $this->viewMode = $viewMode; //Copy the view mode
    }

    public function update(Subject $subject) //Update the observer's state with the subject's state
    {
        $this->state = $subject->getState(); //Store the subject's state
        $this->checkState(); //Check the state to see if the observer's conversation is within the new state
    }

    private function checkState() //Check the state to see if the observer's conversation is within the new state
    {
        //Required to check the connection status for some reason
        ignore_user_abort(true);
        echo "\n";
        ob_flush();
        flush();

        if(in_array($this->convoID, $this->state)) //The observer's conversation has new messages
        {
            $convoQuery = $this->database->query('SELECT * FROM conversations WHERE id = ? LIMIT 1', $this->convoID); //Get the conversation from the database
            $convoRow = $this->database->fetch($convoQuery); //Fetch the conversation row
            $data = array('success' => true, 'msgs' => array()); //Set up the return data array

            if($this->viewMode == 0) //We are returning data to the conversations list
            {
                $msgQuery = $this->database->query('SELECT * FROM messages WHERE convo = ? ORDER BY id DESC LIMIT 1', $this->convoID); //Get the latest message from the conversation
            }
            else //We are returning data to a single conversation
            {
                if($convoRow['user1'] == $this->userID) //The user is user 1 of the conversation
                {
                    $msgQuery = $this->database->query('SELECT * FROM messages WHERE convo = ? AND in1MessageView = 0 ORDER BY id ASC', $this->convoID); //Get the latest messages for user 1
                }
                else //The user is user 2 of the conversation
                {
                    $msgQuery = $this->database->query('SELECT * FROM messages WHERE convo = ? AND in2MessageView = 0 ORDER BY id ASC', $this->convoID); //Get the latest messages for user 2
                }
            }

            if($this->database->rowCount($msgQuery)) //The message query returned new messages
            {
                $newestMessageID = -1; //Stores the ID of the newest message

                while($msgRow = $this->database->fetch($msgQuery)) //Loop through the messages
                {
                    if($msgRow['id'] > $newestMessageID) //This ID is newer
                    {
                        $newestMessageID = $msgRow['id']; //Update the newest ID
                    }

                    if($msgRow['sender'] == $this->userID) //The user is sender
                    {
                        $msgRow['type'] = 'myMessageBackground'; //Set the message type

                        if($convoRow['user1'] == $this->userID) //The user is user 1 of the conversation
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

                        if($convoRow['user1'] == $this->userID) //The user is user 1 of the conversation
                        {
                            $msgRow['sender'] = $convoRow['user2Name']; //Set the sender name
                        }
                        else //The user is user 2 of the conversation
                        {
                            $msgRow['sender'] = $convoRow['user1Name']; //Set the sender name
                        }
                    }

                    if($convoRow['user1'] == $this->userID) //The user is user 1 of the conversation
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

                //Required to check the connection status for some reason
                echo "\n";
                ob_flush();
                flush();

                if(connection_aborted()) //The client is no longer connected
                {
                    exit(); //Kill the script before setting the message as shown
                }

                if($this->viewMode == 0) //We are returning data to the conversations list
                {
                    if($convoRow['user1'] == $this->userID) //The user is user 1 of the conversation
                    {
                        $this->database->query('UPDATE messages SET in1ConvoView = 1 WHERE convo = ? AND id <= ?', $this->convoID, $newestMessageID); //Set all messages as shown in the conversation view for user 1
                    }
                    else //The user is user 2 of the conversation
                    {
                        $this->database->query('UPDATE messages SET in2ConvoView = 1 WHERE convo = ? AND id <= ?', $this->convoID, $newestMessageID); //Set all messages as shown in the conversation view for user 2
                    }
                }
                else //We are returning data to a single conversation
                {
                    if($convoRow['user1'] == $this->userID) //The user is user 1 of the conversation
                    {
                        $this->database->query('UPDATE messages SET in1MessageView = 1 WHERE convo = ? AND id <= ?', $this->convoID, $newestMessageID); //Set all messages as shown in the message view for user 1
                    }
                    else //The user is user 2 of the conversation
                    {
                        $this->database->query('UPDATE messages SET in2MessageView = 1 WHERE convo = ? AND id <= ?', $this->convoID, $newestMessageID); //Set all messages as shown in the message view for user 2
                    }
                }

                exit(print json_encode($data)); //Output the data array as JSON
            }
        }
    }
}