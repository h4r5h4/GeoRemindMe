<?php
 
class DB_Functions {
 
    private $db;
 
    //put your code here
    // constructor
    function __construct() {
        require_once 'DB_Connect.php';
        // connecting to database
        $this->db = new DB_Connect();
        $this->db->connect();
    }
 
    // destructor
    function __destruct() {
         
    }
 
    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($name, $email, $password) {
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt
        $result = mysql_query("INSERT INTO users(unique_id, name, email, encrypted_password, salt, created_at) VALUES('$uuid', '$name', '$email', '$encrypted_password', '$salt', NOW())");
        // check for successful store
        if ($result) {
            // get user details 
            $uid = mysql_insert_id(); // last inserted id
            $result = mysql_query("SELECT * FROM users WHERE uid = $uid");
            // return user details
            return mysql_fetch_array($result);
        } else {
            return false;
        }
    }
    
     public function updateRequest($fromid, $toid) {
        $result = mysql_query("INSERT INTO FriendStatus(FromUserId, ToUserId, StatusId ) VALUES('$fromid', '$toid', 0)");
        if($result)
        	return true;
        else
        	return false;
        	
       }
         public function updateFriendRequest($fromid, $toid, $status) {
         $qq = "UPDATE FriendStatus SET StatusId = $status WHERE FromUserId=$fromid AND ToUserId=$toid";
        
        $result = mysql_query($qq);
        //echo $result;
        if($result)
        	return true;
        else
        	return false;
        	
       }
       
       public function getRelation($fromid, $toid) {
        $result = mysql_query("SELECT * from FriendStatus WHERE FromUserId = '$fromid' and ToUserId = '$toid' ");
        $row = mysql_fetch_array($result);
        $no_of_rows = mysql_num_rows($result);
      if ($no_of_rows > 0) {
        	
        	//echo $row['StatusId'];
        	return $row['StatusId'];
        	}
        else{
        	//echo  "false";
        	return -1;
        	}
        	
       }
       
        public function getUid($email) {
        $result = mysql_query("SELECT * from users WHERE email = '$email'");
        $row = mysql_fetch_array($result);
        $no_of_rows = mysql_num_rows($result);
        
       if ($no_of_rows > 0) {
        	
        	//echo $row['uid'];
        	return $row['uid'];
        	}
        else{
        	//echo  "false";
        	return -1;
        	}
        	
        	
       }
       
       public function setReminder($title, $setBy, $setFor, $latitude, $longitude, $address, $radius, $name) {
        $result = mysql_query("INSERT INTO reminder(title, setBy, setFor, latitude, longitude, address, radius, setByUser) VALUES('$title', '$setBy', '$setFor', $latitude, $longitude, '$address', $radius, '$name')");
        if($result)
        	return true;
        else
        	return false;
        	
       }
       
         public function deleteReminder($rid) {
        $result = mysql_query("DELETE FROM reminder WHERE id=$rid");
        if($result)
        	return true;
        else
        	return false;
        	
       }
       
        public function getEmailfromUid($uid) {
        $result = mysql_query("SELECT * from users WHERE uid = '$uid'");
        $row = mysql_fetch_array($result);
        $no_of_rows = mysql_num_rows($result);
        
       if ($no_of_rows > 0) {
        	
        	//echo $row['uid'];
        	return $row['email'];
        	}
        else{
        	//echo  "false";
        	return -1;
        	}
        	
        	
       }
       
       public function getName($email) {
        $result = mysql_query("SELECT * from users WHERE email = '$email'");
        $row = mysql_fetch_array($result);
        $no_of_rows = mysql_num_rows($result);
        
       if ($no_of_rows > 0) {
        	
        	//echo $row['uid'];
        	return $row['name'];
        	}
        else{
        	//echo  "false";
        	return -1;
        	}
        	
        	
       }
       
       
       public function getPendingRequests($uid) {
        $result = mysql_query("SELECT * from FriendStatus WHERE ToUserId = '$uid' and StatusId = 0");
        $no_of_rows = mysql_num_rows($result);
        $i = 0;
         if ($no_of_rows > 0) {
        while($row = mysql_fetch_array($result)) {
  		$all[$i] = $row['FromUserId'];
  		$i++;
		}
		return $all;
     
        	}
        else{
        	return -1;
        	}
        	
        	
       }
   public function getReminders($uid) {
        $result = mysql_query("SELECT * from reminder WHERE setFor = $uid");
        $no_of_rows = mysql_num_rows($result);
        $i = 0;
         if ($no_of_rows > 0) {
        while($row = mysql_fetch_array($result)) {
  		$all[$i] = $row;
  		$i++;
		}
		return $all;
     
        	}
        else{
        	return -1;
        	}
        	
        	
       }
    /**
     * Get user by email and password
     */
    public function getUserByEmailAndPassword($email, $password) {
        $result = mysql_query("SELECT * FROM users WHERE email = '$email'") or die(mysql_error());
        // check for result 
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysql_fetch_array($result);
            $salt = $result['salt'];
            $encrypted_password = $result['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $result;
            }
        } else {
            // user not found
            return false;
        }
    }
 
    /**
     * Check user is existed or not
     */
    public function isUserExisted($email) {
        $result = mysql_query("SELECT email from users WHERE email = '$email'");
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            // user existed 
            return true;
        } else {
            // user not existed
            return false;
        }
    }
 
    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {
 
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }
 
    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {
 
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
 
        return $hash;
    }
 
}
 
?>