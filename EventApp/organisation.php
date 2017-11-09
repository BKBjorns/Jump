<?php
//-- PAGE SETUP ----------------------------------------------------------------


//-- INCLUDE
include("header.php");
include("menu.php");
include("userinfo.php");

//--ORGANISATION SECURITY
$type = $_SESSION['type'];

if ( $type == 'student'){
  header("location:user.php");
  exit();
}

//-- DATABASE CONNECTION
@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
if ($db->connect_error) {
   echo "could not connect: " . $db->connect_error;
   header("Location: index.php");
   exit();
}
?>

<!-- ADD EVENT BTN ------------------------------------------------------------>
<div class="containerBTN">
  <div class="btn">
      <a href="addevent.php">Add Event</a>
  </div>
</div>

<!-- EVENT CARDS -------------------------------------------------------------->
<div style="margin-top: 200px;" class="allEvents">
  <?php
    $userID = $_SESSION['userID'];
    $organisation = $_SESSION['organisation'];

    //-- DELETE PASSED EVENTS --------------------------------------------------
    $current_time = date("Y/m/d");

    $stmt = $db->prepare("DELETE FROM Events WHERE startdate < '$current_time'");
    $stmt->execute();


    //-- DELETE EVENT ----------------------------------------------------------
    if (isset($_POST['minus'])){
        $eventid = $_POST['eventID'];

        $deleteQuery = "DELETE FROM Events WHERE eventID = '{$eventid}'";
        $stmt = $db->prepare($deleteQuery);
        $stmt->execute();
    }


    //-- GET EVENT DATA FROM ORG's EVENTS --------------------------------------
    $query = "SELECT eventID, title, description, startdate, time, location, image, host FROM Events WHERE host = '{$organisation}' ORDER BY startdate, time";
    $stmt = $db->prepare($query);
    $stmt->bind_result($eventID, $title, $description, $startdate, $time, $location, $image, $host);
    $stmt->execute();

    //-- Draw event cards with minus btn
    while($stmt->fetch()){ ?>
   <div class="eventContainerOne">
      <div class="imgContainer" style="background-image: url('uploadedfiles/<?php echo "$image"; ?>');"></div>
      <form method="POST" action=''>
          <input type="submit" value="—" class="plusBtn" name="minus">
          <input type="hidden" value="<?php echo "$eventID"; ?>" name="eventID">
      </form>
      <?php
        echo '<a class="orgEditBtn" href="organisation_edit.php?eventID=' . urlencode($eventID) . '">
             <i class="fa fa-pencil" aria-hidden="true"></i></a>';
        ?>
      <div class="infoContainer">
        <div class="eventTitle">
           <?php
              echo "<h4>$title</h4> <p><strong>Date:</strong> $startdate</p> <p><strong>Time: </strong> $time</p> <p><strong>Location: </strong> $location</p>";
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
<?php



include("footer.php");
?>
