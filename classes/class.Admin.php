<?php
if(!defined('ALLOW_ACCESS')) exit(header('Location: /')); //Only allow access to this file through the router

//The Admin class extends the RegUser class, providing additional administrator functionality of deleting any ad and banning users
class Admin extends RegUser
{
    public function deleteAd($adID) //Delete the specified ad and send a message to the owner
    {
        if($this->validSession() && $_SESSION['rank'] > 0) //The user has a valid session
        {
            $this->database->query('UPDATE advertisements SET deleted = 1 WHERE id = ?', $adID); //Delete the ad
            $adQuery = $this->database->query('SELECT * FROM advertisements WHERE id = ?', $adID); //Get the deleted ad's information from the database


            if($this->database->rowCount($adQuery)) //The query was successful and returned a row
            {
                $adRow = $this->database->fetch($adQuery); //Fetch the adrow

                if($_SESSION['id'] != $adRow['userid']) //The admin isn't deleting their own ad
                {
                    $convoQuery = $this->database->query('SELECT id FROM conversations WHERE (user1 = ? AND user2 = ?) OR (user1 = ? AND user2 = ?)', $adRow['userid'], $_SESSION['id'], $_SESSION['id'], $adRow['userid']); //Check if the user already has a conversation with the specified user

                    if($this->database->rowCount($convoQuery)) //The query was successful and returned a row
                    {
                        $convoRow = $this->database->fetch($convoQuery); //Fetch the conversation row
                    }
                    else
                    {
                        $this->getUserBy('id', $_SESSION['id']); //Get the user's info
                        $name1 = $this->data['username']; //Store the user's name
                        $this->getUserBy('id', $adRow['userid']); //Get the specified user's info
                        $name2 = $this->data['username']; //Store the specified user's name
                        $this->database->query('INSERT INTO conversations (user1, user2, user1Name, user2Name) VALUES (?, ?, ?, ?)', $_SESSION['id'], $adRow['userid'], $name1, $name2); //Start the conversation
                        $convoQuery = $this->database->query('SELECT id FROM conversations WHERE user1 = ? AND user2 = ?', $_SESSION['id'], $adRow['userid']); //Get the new conversation ID
                        $convoRow = $this->database->fetch($convoQuery); //Fetch the conversation row
                    }

                    $this->sendMessage(array('convo' => $convoRow['id'], 'message' => 'Your "' . $adRow['title'] . '" ad has been removed.')); //Send the message

                    exit(header('Location:' . $_SERVER['HTTP_REFERER'])); //Send the user back to the page they were on
                }

                exit(header('Location: /myprofile')); //Send the user back to their profile
            }
        }

        exit(header('Location: /')); //Send the user back to the homepage and kill the script
    }

    public function banUser($userID) //Ban the specified user
    {
        if($this->validSession() && $_SESSION['rank'] > 0) //The user has a valid session
        {
            $this->database->query('UPDATE advertisements SET deleted = 1 WHERE userid = ?', $userID); //Delete the specified user's ads
            $this->database->query('UPDATE users SET banned = 1 WHERE id = ?', $userID); //Delete the ad
        }
    }
}
