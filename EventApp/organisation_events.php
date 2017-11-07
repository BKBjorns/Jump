<?php

session_start();
    if (!isset($_SESSION['userID'])) {
        header("Location:../index.php");
    }

include("header.php");
include("menu.php");
include("userinfo.php");
?>

    <div class="allEvents" style="margin-top: 130px;">
      <?php
        //session_start();
        @ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

        if ($db->connect_error) {
            echo "could not connect: " . $db->connect_error;
            exit();
        }

        // To delete events if the date is past.
        $current_time = date("Y/m/d");

        $stmt = $db->prepare("DELETE FROM Events WHERE startdate < '$current_time'");
        $stmt->execute();



        //-----GET EVENTS ------------------------------------------------------------------------------------------------

        //this selects all event information from the Events db
        $query = "SELECT eventID, title, description, startdate, enddate, time, price, location, image, link, host FROM Events ORDER by startdate, time";
        $stmt = $db->prepare($query);
        //binds the db information to the variables
        $stmt->bind_result($eventID, $title, $description, $startdate, $enddate, $time, $price, $location, $image, $link, $host);
        $stmt->execute();

        //gets every event in a row and displays the information in the div "eventContainer"
        while($stmt->fetch()){ ?>

       <!---------------------------------EVENT ONE-->
       <div class="eventContainerOne">
          <!-----------event img & attend event btn-->
          <div class="imgContainer" style="background-image: url('uploadedfiles/<?php echo "$image"; ?>');"></div>

              <?php
              echo $eventID;
                echo '<a class="editBtn" href="organisation_edit.php?eventID=' . urlencode($eventID) . '">
                     <i class="fa fa-pencil" aria-hidden="true"></i></a>';
                ?>

          <!---------event information & expand btn-->
          <div class="infoContainer">
                <div class="eventTitle">
                 <?php
                    echo "<h4>$title</h4> <p><strong>Date:</strong> $startdate</p> <p><strong>Time: </strong> $time</p>";
                  ?>
              </div>
              <a href="#" class="expanderBtn">
                  <i class="fa fa-angle-down" aria-hidden="true"></i>
              </a>
              <p class="eventDescription">
                <?php echo "$description";?>
              </p>
            </div>
       </div>

       <?php  }
        ?>




    </div>



<?php
include("footer.php");

?>
