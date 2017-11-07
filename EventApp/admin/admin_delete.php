<?php
session_start();
    if (!isset($_SESSION['userID'])) {
        header("Location:../index.php");
    }

include("admin_header.php");
include("admin_menu.php");
include("../userinfo.php");
?>



<div class="allEvents" style="padding-top: 100px;">
      <?php
        //session_start();
        @ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

        if ($db->connect_error) {
            echo "could not connect: " . $db->connect_error;
            exit();
        }

    //This deletes the event from the DB.
    if (isset($_POST['minus'])){

        //@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

        $eventid = $_POST['eventID'];

        //get the eventID from the input and the userID from the session and insert it into the database
        $deleteQuery = "DELETE FROM Events WHERE eventID = '{$eventid}'";
        //$stmt->bind_param('i', $eventID);
        $stmt = $db->prepare($deleteQuery);
        $stmt->execute();

    }


        //this selects all event information from the Events db
        $query = "SELECT eventID, title, description, startdate, enddate, time, price, location, image, link, host FROM Events ORDER by startdate AND time";
        $stmt = $db->prepare($query);
        //binds the db information to the variables
        $stmt->bind_result($eventID, $title, $description, $startdate, $enddate, $time, $price, $location, $image, $link, $host);
        $stmt->execute();

        //gets every event in a row and displays the information in the div "eventContainer"
        while($stmt->fetch()){ ?>

       <!---------------------------------EVENT ONE-->
       <div class="eventContainerOne">
          <!-----------event img & attend event btn-->
          <div class="imgContainer" style="background-image: url('../uploadedfiles/<?php echo "$image"; ?>');"></div>
          <form method="POST" action='admin_delete.php'>
                  <input type="submit" value="—" class="plusBtn" name="minus">
                  <input type="hidden" value="<?php echo "$eventID"; ?>" name="eventID">
              </form>
          <!---------event information & expand btn-->
          <div class="infoContainer">
              <div class="eventTitle">
                 <?php
                    echo "<h4>$title</h4> <p><strong>Date:</strong> $startdate</p> <p><strong>Time: </strong> $time</p> <p><strong>Location: </strong> $location</p>";
                  ?>
              </div>
              <a href="#" class="expanderBtn">
                  <i class="fa fa-angle-down" aria-hidden="true"></i>
              </a>
              <p class="eventDescription">
                <?php echo "$description $host";?>
              </p>
            </div>
       </div>

       <?php  }


    ?>




    </div>


<?php
include("../footer.php");

?>
