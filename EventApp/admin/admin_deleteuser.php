<?php 
session_start();
    if (!isset($_SESSION['userID'])) {
        header("Location:../index.php");
    }

include("admin_header.php");
include("admin_menu.php");
include("../userinfo.php");
?>
<div class="addEvent">
        <a href="admin_adduser.php"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
</div>

<?php


@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

if ($db->connect_error) {
  echo "could not connect: " . $db->connect_error;
  exit();
}

//This deletes the user
if(isset($_POST['minus'])){
    
    $userID = $_POST['userID'];
    
    $deleteQuery = "DELETE FROM Users WHERE userID = '{$userID}'";
//    echo "$deleteQuery";
//    exit();
    $stmt = $db->prepare($deleteQuery);
    $stmt->execute();
    //header("location:admin_deleteuser.php");
}


$query="SELECT userID, type, userpass, email, image, school, firstname, lastname, organisation FROM Users";
$stmt = $db->prepare($query);
$stmt->bind_result($userID, $type, $userpass, $email, $image, $school, $firstname, $lastname, $organisation);
$stmt->execute();


?> 
<div class="allUsers" style="padding-top 30px;">
<?php
while($stmt->fetch()){
    
    if($type == 'student'){?>
       <div class="userContainer">
          
         <div class="student">
             <h3><?php echo "$firstname, $lastname"; ?></h3> 
         </div>
          <form method="POST" action='admin_deleteuser.php'>
                  <input type="submit" value="—" class="plusBtn" name="minus">
                  <input type="hidden" value="<?php echo "$userID"; ?>" name="userID">
              </form>
          <!---------event information & expand btn-->
          <div class="infoContainer">
              <p class="user">
                 <?php echo "<p><strong>User ID:</strong> $userID </p> <p><strong>Email:</strong> $email </p> <p><strong>School:</strong> $school </p>"; ?>
              </p> 
            </div>
        </div> 
    <?php }
    
    else if($type == 'organisation'){?>
       <div class="userContainer">
          
         <div class="organisation">
             <h3><?php echo "$organisation"; ?></h3> 
         </div>
          <form method="POST" action='admin_deleteuser.php'>
                  <input type="submit" value="—" class="plusBtn" name="minus">
                  <input type="hidden" value="<?php echo "$userID"; ?>" name="userID">
              </form>
          <!---------event information & expand btn-->
          <div class="infoContainer">
              <p class="user">
                 <?php echo "<p><strong>User ID:</strong> $userID </p> <p><strong>Email:</strong> $email </p> <p><strong>School:</strong> $school </p>"; ?>
              </p> 
            </div>
        </div> 
    
<?php
    }
}
?>
</div>

<?php 
include("../footer.php");

?>