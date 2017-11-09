<?php
//-- PAGE SETUP ----------------------------------------------------------------


//-- INCLUDE
include("admin_header.php");
include("../menu.php");
include("../userinfo.php");


//--ADMIN SECURITY
$type = $_SESSION['type'];

if ( $type == 'student'){
  header("location:../user.php");
  exit();
}else if ($type == 'organisation'){
  header("location:../organisation.php");
}


//-- DATABASE CONNECTION
@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
if ($db->connect_error) {
   echo "could not connect: " . $db->connect_error;
   header("Location: index.php");
   exit();
}


//-- DELETE USER ---------------------------------------------------------------
if(isset($_POST['minus'])){
    $userID = $_POST['userID'];
    $deleteQuery = "DELETE FROM Users WHERE userID = '{$userID}'";
    $stmt = $db->prepare($deleteQuery);
    $stmt->execute();
    //header("location:admin_deleteuser.php");
}

//-- GET USER DATA -------------------------------------------------------------
$query="SELECT userID, type, userpass, email, image, school, firstname, lastname, organisation FROM Users";
$stmt = $db->prepare($query);
$stmt->bind_result($userID, $type, $userpass, $email, $image, $school, $firstname, $lastname, $organisation);
$stmt->execute();
?>

<!-- USER CARDS --------------------------------------------------------------->
<div class="allUsers" style="padding-top 30px;">
<?php
while($stmt->fetch()){
    //-- DRAW STUDENT CARD -----------------------------------------------------
    if($type == 'student'){?>
       <div class="userContainer">
         <div class="student">
             <h3><?php echo "$firstname $lastname"; ?></h3>
         </div>
          <form method="POST" action='admin_deleteuser.php'>
                  <input type="submit" value="—" class="plusBtn" name="minus">
                  <input type="hidden" value="<?php echo "$userID"; ?>" name="userID">
              </form>
          <div class="infoContainer">
              <p class="user">
                 <?php echo "<p><strong>User ID:</strong> $userID </p> <p><strong>Email:</strong> $email </p> <p><strong>School:</strong> $school </p>"; ?>
              </p>
            </div>
        </div>
    <?php }
    //-- DRAW ORGANISATION CARD ------------------------------------------------
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
}?>
</div>



<?php
include("../footer.php");

?>
