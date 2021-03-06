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
?>



<div class="allEvents" style="padding-top: 100px;">
<?php
  //-- DELETE EVENT -------------------------------------------------------------
  if (isset($_POST['minus'])){

    $eventid = $_POST['eventID'];
    $deleteQuery = "DELETE FROM Events WHERE eventID = '{$eventid}'";
    $stmt = $db->prepare($deleteQuery);
    $stmt->execute();

    }


    //-- SELECT ALL EVENT DATA -------------------------------------------------
    $query = "SELECT eventID, title, description, startdate, enddate, time, price, location, image, link, host FROM Events ORDER by startdate, time";
    $stmt = $db->prepare($query);
    $stmt->bind_result($eventID, $title, $description, $startdate, $enddate, $time, $price, $location, $image, $link, $host);
    $stmt->execute();

    //-- DRAW EVENT CARDS ------------------------------------------------------
    while($stmt->fetch()){ ?>
       <div class="eventContainerOne">
          <!-- event img & attend event btn -->
          <div class="imgContainer" style="background-image: url('../uploadedfiles/<?php echo "$image"; ?>');"></div>
          <form method="POST" action='admin_delete.php'>
                  <input type="submit" value="—" class="plusBtn" name="minus">
                  <input type="hidden" value="<?php echo "$eventID"; ?>" name="eventID">
              </form>
          <!-- event information & expand btn -->
          <div class="infoContainer">
              <div class="eventTitle">
                 <?php
                 echo "<h4>$title</h4> <p><strong>Date:</strong> $startdate</p> <p><strong>Time: </strong> $time</p> <p><strong>Location: </strong> $location</p><p class='descriptionHost'><strong>$host</strong>
                 </p>";
                  ?>
              </div>
              <button href="#" class="expanderBtn">
                  <i class="fa fa-angle-down" aria-hidden="true"></i>
              </button>
              <p class="eventDescription">
                <?php
                   echo "$description";
                 ?>
              </p>
              
            </div>
       </div>
    <?php  }
    ?>
</div>


<?php
include("../footer.php");

?>
