<?php
include("header.php");
?>
    <div id="top" class="logo">
        <a href="index.php"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
    </div>
    <div class="allEvents">
      <?php
        session_start();
        @ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

        if ($db->connect_error) {
            echo "could not connect: " . $db->connect_error;
            exit();
        }


        //add attendee
        //check if plus button is clicked
//         if (isset($_POST['plus'])){
//
//             //set variables, which get data
//             $eventid = $_POST['eventID'];
//             $userid = $_SESSION['userID'];
//
//             //get the eventID from the input and the userID from the session and insert it into the database
//             $insertQuery = "INSERT INTO Attend (eventID, userID) VALUES ($eventid, $userid)";
//
//             //this selects the userID and eventID from the attend db to check if the current user already has attended that event > not supposed to be able to click the same event twice
//             $stmt = $db->prepare("SELECT `eventID`, `userID` FROM `Attend` WHERE userID = $userid  AND eventID = $eventid");
//             $stmt->execute();
//
//             //if the event hasnt been clicked/attended before, there will no rows in the db, which means that the fetch will be empty
//             if (!$stmt->fetch()){
//                // then the userID and eventID will be added in the attend db
//                $stmt = $db->prepare($insertQuery);
//                $stmt->execute();
//                header("location:events.php");
//             }
//         }


        // To delete events if the date is past.
        $current_time = date("Y/m/d");

        $stmt = $db->prepare("DELETE FROM Events WHERE startdate < '$current_time'");
        $stmt->execute();


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
          <form method="POST" action='events.php'>
                  <input type="hidden" value="<?php echo "$eventID"; ?>" name="eventID">
              </form>
          <!---------event information & expand btn-->
          <div class="infoContainer">
              <div class="eventTitle">
                 <?php
                    echo "<h4>$title</h4> <p><strong>Date:</strong> $startdate</p> <p><strong>Time: </strong> $time</p><p><strong>Location: </strong> $location</p>";
                  ?>
              </div>
              <button href="#" class="expanderBtn">
                  <i class="fa fa-angle-down" aria-hidden="true"></i>
              </button>
              <p class="eventDescription">
                <?php
                   echo "$description <br> $host";
                 ?>
              </p>
            </div>
       </div>

       <?php  } ?>




    </div>



<?php
include("footer.php");

?>
