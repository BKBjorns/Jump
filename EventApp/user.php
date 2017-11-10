<?php
//-- PAGE SETUP ----------------------------------------------------------------



//-- INCLUDE
include("header.php");
include("menu.php");
include("userinfo.php");

//-- DATABASE CONNECTION
@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
if ($db->connect_error) {
   echo "could not connect: " . $db->connect_error;
   header("Location: index.php");
   exit();
}
$userID = $_SESSION['userID'];
?>

<!-- EVENT CARDS -------------------------------------------------------------->
<div style="margin-top: 130px;" class="allEvents">
<?php

    //-- DELETE PASSED EVENTS --------------------------------------------------
    $current_time = date("Y/m/d");

    $stmt = $db->prepare("DELETE FROM Events WHERE startdate < '$current_time'");
    $stmt->execute();

  //-- DELETE EVENT ------------------------------------------------------------
  if (isset($_POST['minus'])){
    $eventid = $_POST['eventID'];

    $deleteQuery = "DELETE FROM Attend WHERE userID = '{$userID}' AND eventID = '{$eventid}' ";
    $stmt = $db->prepare($deleteQuery);
    $stmt->execute();
  }




//  //-- IF THE USER HAS NO ATTENDED EVENTS ----------------------------------------
//
//  $attendQuery = "SELECT eventID FROM Attend WHERE userID = '{$userid}' ";
//
//        $attend_stmt = $db->prepare($attendQuery);
//        $attend_stmt->execute();
//        $attend_stmt->bind_result($eventID);
//
//        $array = array();
//        //-- STORE EVENTID IN ARRAY
//        while($attend_stmt->fetch()){
//            echo $eventID;
//            $array[] = $eventID;
//        }
//    //print_r ($array);
//        $attend_stmt->close();

  $attendQuery = "SELECT eventID FROM Attend WHERE userID = '$userID'";

  $attend_stmt = $db->prepare($attendQuery);

  $attend_stmt->execute();
  $attend_stmt->bind_result($eventID);

  $attend_stmt->store_result();
  $attend_result = $attend_stmt->num_rows();

  if($attend_result == 0){
    echo "<h2>You have not attended any event!</h2>";
  }else{



//-- JOIN TO GET EVENTS USER ATTENDED ----------------------------------------
  $query = "SELECT Events.eventID, Events.title, Events.description, Events.startdate, Events.enddate, Events.time, Events.price, Events.location, Events.image, Events.link, Events.host FROM Events
  JOIN Attend on Events.eventID = Attend.eventID
  JOIN Users on Users.userID = Attend.userID
  WHERE Users.userID = '{$userID}' ORDER BY startdate, time";

  $stmt = $db->prepare($query);
  $stmt->bind_result($eventID, $title, $description, $startdate, $enddate, $time, $price, $location, $image, $link, $host);
  $stmt->execute();

//    print_r ($array);
//  if(empty($array)){
//      echo "<h4 style='margin-top: 10px;'>You have no attended events</h4>";
//      exit();
//  }

  while($stmt->fetch()){ ?>

 <div class="eventContainerOne">
      <div class="imgContainer" style="background-image: url('uploadedfiles/<?php echo "$image"; ?>');"></div>
      <form method="POST" action='user.php'>
            <input type="submit" value="-" class="plusBtn" name="minus">
            <input type="hidden" value="<?php echo "$eventID"; ?>" name="eventID">
        </form>
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
        <p class="descriptionHost">
          <?php
          echo "<strong>$host</strong>" ?>
        </p>
      </div>
  </div>
 <?php  } ?>
 </div>

<?php } ?>

<?php
include("footer.php");
?>
