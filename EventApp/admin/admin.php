<?php 

session_start();
    if (!isset($_SESSION['userID'])) {
        header("Location:../index.php");
    }

include("admin_header.php");
include("admin_menu.php");
include("../userinfo.php");

?>


<?php
    @ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
    
    //check if there is a connection to the database
	if ($db->connect_error) {
		echo "could not connect: " . $db->connect_error;
		exit();
	}

    $query = "SELECT type FROM Users WHERE userID = '{$userID}'";
    $stmt = $db->prepare($query);
    $stmt->bind_result($type);
    $stmt->execute();
	//echo "$userID";
    //echo "$type";
    
//    if ($type == 'admin'){
//        header("Location:admin.php"); 
//            exit();
//    } else {
//        header("Location:../index.php"); 
//            exit();
//    }
?>
     
      <div class="btnContainer">
          <div class="btn">
              <a href="admin_add.php">Add Event</a>
          </div>
          <div class="btn">
              <a href="admin_events.php">Edit Event</a>
          </div>
          <div class="btn">
              <a href="admin_delete.php">Delete Event</a>
          </div>
          <div class="btn">
              <a href="admin_adduser.php">Add User</a>
          </div>
          <div class="btn">
              <a href="admin_deleteuser.php">Delete User</a>
          </div>
      </div>
   




<?php 
include("../footer.php");

?>