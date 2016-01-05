<?php
/**
 * File to handle all API requests
 * Accepts GET and POST
 * 
 * Each request will be identified by TAG
 * Response will be JSON data
 
  /**
 * check for POST request 
 */
if (isset($_POST['tag']) && $_POST['tag'] != '') {
    // get tag
    $tag = $_POST['tag'];
 
    // include db handler
    require_once 'include/DB_Functions.php';
    $db = new DB_Functions();
 
    // response Array
    $response = array("tag" => $tag, "success" => 0, "error" => 0);
 
    // check for tag type
    if ($tag == 'login') {
        // Request type is check Login
        $email = $_POST['email'];
        $password = $_POST['password'];
 
        // check for user
        $user = $db->getUserByEmailAndPassword($email, $password);
        if ($user != false) {
            // user found
            // echo json with success = 1
            $response["success"] = 1;
            $response["uid"] = $user["unique_id"];
            $response["user"]["name"] = $user["name"];
            $response["user"]["email"] = $user["email"];
            $response["user"]["created_at"] = $user["created_at"];
            $response["user"]["updated_at"] = $user["updated_at"];
            echo json_encode($response);
        } else {
            // user not found
            // echo json with error = 1
            $response["error"] = 1;
            $response["error_msg"] = "Incorrect email or password!";
            echo json_encode($response);
        }
    } else  if ($tag == 'sendRequest') {

       $email = $_POST['fromemail'];
       $toEmail = $_POST['toemail'];
       
       $fromid = $db->getUid($email);
       $toid = $db->getUid($toEmail);
       $relation = $db->getRelation($toid, $fromid);
        
       
       if( $relation==-1)
       {
       $relationOp = $db->getRelation($fromid, $toid);
       if($relationOp==-1){
       
        $user = $db->updateRequest($fromid, $toid);
        if ($user != false) {
            // user found
            // echo json with success = 1
            $response["success"] = 1;
            $response["success_msg"] = "Friend Request Sent";
            echo json_encode($response);
        } else {
            // user not found
            // echo json with error = 1
            $response["error"] = 1;
            $response["error_msg"] = "Incorrect email or password!";
            echo json_encode($response);
        }
        }
        else if($relationOp==0)
        {
         $response["error"] = 1;
            $response["error_msg"] = "Friend Request Pending";
            echo json_encode($response);
        }
        }
        else if($relation == 1)
        {
        $response["error"] = 1;
            $response["error_msg"] = "You are already related with the user";
            echo json_encode($response);
        }
         else if($relation == 2)
        {
        $response["error"] = 1;
            $response["error_msg"] = "User is ignoring you";
            echo json_encode($response);
        }
           else if($relation == 0)
        {
        $db->updateFriendRequest($toid, $fromid);
        $response["success"] = 1;
            $response["success_msg"] = "You are now friends";
            echo json_encode($response);
        }
    }
       else  if ($tag == 'addRequest') {

       $fromEmail = $_POST['fromemail'];
       $toEmail = $_POST['toemail'];
       $status = $_POST['status'];
       
       $fromId = $db->getUid($fromEmail);
       $toId = $db->getUid($toEmail);
       
       
       if( $db->getRelation($fromId, $toId)==-1)
       {
            // user not found
            // echo json with error = 1
            $response["error"] = 1;
            $response["error_msg"] = "No Request Found" ;
            echo json_encode($response);
        
        }
        else if( $db->getRelation($fromId, $toId)==0)
        {
        $check = $db->updateFriendRequest($fromId, $toId, $status);
         if ($check != false) {
            // user found
            // echo json with success = 1
            $response["success"] = 1;
            echo json_encode($response);
        }else
        {
        $response["error"] = 1;
            $response["error_msg"] = "Error";
            echo json_encode($response);
        }
        }else if ( $db->getRelation($fromId, $toId)==1)
        {
         $response["error"] = 1;
            $response["error_msg"] = "Already Friends";
            echo json_encode($response);
        }
        
        
    }
    else  if ($tag == 'addReminder') {

       $fromEmail = $_POST['fromemail'];
       $toEmail = $_POST['toemail'];
       $radius = $_POST['radius'];
       $title = $_POST['title'];
       $address= $_POST['address'];
        $latitude= $_POST['latitude'];
         $longitude= $_POST['longitude'];
       
       $fromId = $db->getUid($fromEmail);
        $name = $db->getName($fromEmail);
       $toId = $db->getUid($toEmail);
       
       if($db->getRelation($fromId, $toId) == 1 || $db->getRelation($toId, $fromId) == 1 || $fromId == $toId)
       {
          
       
        $check = $db->setReminder($title, $fromId, $toId, $latitude, $longitude, $address, $radius, $name);
         if ($check != false) {
            // user found
            // echo json with success = 1
            $response["success"] = 1;
            echo json_encode($response);
        }else
        {
        $response["error"] = 1;
            $response["error_msg"] = "Error";
            echo json_encode($response);
        }
        }
        else
        {
        $response["error"] = 1;
            $response["error_msg"] = "You can't set alerts for this user";
            echo json_encode($response);
        }

        
    }
    else  if ($tag == 'checkRequest') {

       $emailId = $_POST['email'];
       $toId = $db->getUid($emailId);
       $idArr = $db->getPendingRequests($toId);
       if($idArr == -1)
       {
       $resp["emails"] = "no friends";
       echo json_encode($resp);
       }
       else
       {
       $resp["id"] = $idArr;
       

for($i = 0; $i<sizeof($idArr);$i++)
	$emailArr[$i] = $db->getEmailfromUid($idArr[$i]);
	


$resp["emails"] = $emailArr;
echo json_encode($resp);
       
        }
    }
      else  if ($tag == 'deleteReminder') {

       $id = $_POST['id'];
     
       $check = $db->deleteReminder($id);
       if($check)
       {
            $response["success"] = 1;
            echo json_encode($response);
        }else
        {
        $response["error"] = 1;
            $response["error_msg"] = "Reminder Not Found";
            echo json_encode($response);
        }


	



       
        
    }
        else  if ($tag == 'checkReminder') {

       $emailId = $_POST['email'];
       $toId = $db->getUid($emailId);
       $idArr = $db->getReminders($toId);
       if($idArr == -1)
       {
       $resp["reminders"] = "no reminders";
       echo json_encode($resp);
       }
       else
       {
     
      


$resp["reminders"] = $idArr;
echo json_encode($resp);
       
        }
    }
    else if ($tag == 'register') {
        // Request type is Register new user
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
 
        // check if user is already existed
        if ($db->isUserExisted($email)) {
            // user is already existed - error response
            $response["error"] = 2;
            $response["error_msg"] = "User already existed";
            echo json_encode($response);
        } else {
            // store user
            $user = $db->storeUser($name, $email, $password);
            if ($user) {
                // user stored successfully
                $response["success"] = 1;
                $response["uid"] = $user["unique_id"];
                $response["user"]["name"] = $user["name"];
                $response["user"]["email"] = $user["email"];
                $response["user"]["created_at"] = $user["created_at"];
                $response["user"]["updated_at"] = $user["updated_at"];
                echo json_encode($response);
            } else {
                // user failed to store
                $response["error"] = 1;
                $response["error_msg"] = "Error occured in Registartion";
                echo json_encode($response);
            }
        }
    } else {
        echo "Invalid Request";
    }
} else {
    echo "Access Denied";
}
?>