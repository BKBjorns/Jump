<?php
//-- PAGE SETUP ----------------------------------------------------------------

//-- DATABASE CONNECTION
@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
if ($db->connect_error) {
   echo "could not connect: " . $db->connect_error;
   header("Location: index.php");
   exit();
}

$userID = $_SESSION['userID'];


//-- GET USER IMAGE ------------------------------------------------------------
$userquery = "SELECT type, image FROM Users WHERE userID = '{$userID}' ";
$stmt = $db->prepare($userquery);
$stmt->bind_result($type, $image);
$stmt->execute();

while($stmt->fetch()){
  if ($type == 'organisation' || $type == 'student'){?>
      <div class="userInfo">
          <div class="userImg" style="background-image: url('uploadedfiles/<?php echo "$image"; ?>');"></div>
          <div class="userWelcome">Hi <?php
              echo $_SESSION['username'];
              echo $_SESSION['organisation'];
              ?>! Welcome to your page.
          </div>
      </div>
<?php
    }else if ($type == 'admin'){?>
        <div class="userInfo">
            <div class="userImg" style="background-image: url('../uploadedfiles/<?php echo "$image"; ?>');"></div>
            <div class="userWelcome">Hi <?php
                echo $_SESSION['username'];
                echo $_SESSION['organisation'];
                ?>! Welcome to your page.
            </div>
        </div>
    <?php
    }

} ?>
