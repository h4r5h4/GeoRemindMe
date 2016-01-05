<?
require_once 'include/DB_Functions.php';

    $db = new DB_Functions();
    
      $email = "harsha@gmail.com";
      $emailId = $email;

       $toId = $db->getUid($emailId);
       $idArr = $db->getReminders($toId);
       if($idArr == -1)
       {
       $resp["reminders"] = "no reminders";
       echo json_encode($resp);
       }
       else
       {
       $resp["id"] = $idArr;
      


$resp["emails"] = $idArr;
echo json_encode($resp);
}
      /* $toEmail = "harsha.ms7@gmail.com";
      echo $db->deleteReminder(2);
      // echo $arr[0][0];
        /*
       
       echo ($db->getReminders(1));
        /*
       
       $fromid = $db->getUid($email);
       echo $fromid;
       $toid = $db->getUid($toEmail);
       echo "toid" . $toid;
       echo  $db->setReminder("Pick Me up", $fromid, $toid, 70.6588, -85.2365, "Charlotte", 50 );       /*
       if( $db->getRelation($fromid, $toid)==-1)
       {
       
       
        $user = $db->updateRequest($fromid, $toid);
        if ($user != false) {
            // user found
            // echo json with success = 1
            $response["success"] = 1;
            echo json_encode($response);
        } else {
            // user not found
            // echo json with error = 1
            $response["error"] = 1;
            $response["error_msg"] = "Incorrect email or password!";
            echo json_encode($response);
        }
        }
    /*
   // $emailId = "yogi.anugu@gmail.com";
       $toId = $db->getUid("harsha@gmail.com");
       $fromId = $db->getUid("harsha.ms7@gmail.com");
       echo $toId . "<br>";
       echo $fromId . "<br>";
     // echo $db->updateFriendRequest($fromId, $toId);
       echo $db->getRelation($fromId, $toId);*/
       //$idArr = $db->getPendingRequests($toId);
       /*$resp["id"] = $idArr;

for($i = 0; $i<sizeof($idArr);$i++)
	$email[$i] = $db->getEmailfromUid($idArr[$i]);

$resp["emails"] = $email;
echo json_encode($resp);
*/
//$user = $db->updateRequest();
/*
$toid = $db->getUid("harsha@gmail.com");


$new = $db->getPendingRequests($toid);
$response["id"] = $new;


for($i = 0; $i<sizeof($new);$i++)
	$email[$i] = $db->getEmailfromUid($new[$i]);

$response["email"] = $email;
echo json_encode($response);




/*
  $fromid = $db->getUid("harsha@gmail.com");
       $toid = $db->getUid("harsha.ms7@gmail.com");
       
       
        if( $db->getRelation($fromid, $toid)== -1)
       {
       $user = $db->updateRequest($fromid, $toid);}
       else
       echo "how many times";*/
      /* 
        $db = new DB_Functions();
           $user = $db->getUserByEmailAndPassword("harsha@gmail.com", "harsha");
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
        }*/