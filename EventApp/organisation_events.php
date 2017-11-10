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

<!-- EVENT CARDS -------------------------------------------------------------->
    <div class="allEvents" style="margin-top: 130px;">
      <?php

      //-----DELETE PASSED EVENTS---------------------------------------------
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


      //-----GET EVENTS FROM ORGANISATION (TABLE EVENTS)------------------------
      $userid = $_SESSION['userID'];
      $organisation = $_SESSION['organisation'];

      $eventQuery = "SELECT eventID FROM Events WHERE host = '{$organisation}' ";
      $event_stmt = $db->prepare($eventQuery);
      $event_stmt->execute();
      $event_stmt->bind_result($eventID);

      $array = array();

      while($event_stmt->fetch()){
          $array[] = $eventID;
      }
      $event_stmt->close();



      //-----GET EVENTS & DRAW CARDS--------------------------------------------

      //-- get events from db
      $query = "SELECT eventID, title, description, startdate, enddate, time, price, location, image, link, host FROM Events ORDER by startdate, time";
      $stmt = $db->prepare($query);
      $stmt->execute();
      $stmt->bind_result($eventID, $title, $description, $startdate, $enddate, $time, $price, $location, $image, $link, $host);

      while($stmt->fetch()){
        //-- organisation's own events, edit btn
          if ((in_array ($eventID, $array))){ ?>
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
                          echo "<h4>$title</h4> <p><strong>Date:</strong> $startdate</p> <p><strong>Time: </strong> $time</p>";
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
          <?php
          //-- events not hosted by that organisation
          }else { ?>
              <div class="eventContainerOne">
                  <div class="imgContainer" style="background-image: url('uploadedfiles/<?php echo "$image"; ?>');"></div>
                  <div class="infoContainer">
                    <div class="eventTitle">
                       <?php
                          echo "<h4>$title</h4> <p><strong>Date:</strong> $startdate</p> <p><strong>Time: </strong> $time</p>";
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
          <?php
          }
     }?>



<?php
include("footer.php");

?>
