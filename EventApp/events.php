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

$type = $_SESSION['type'];
?>

<!-- DRAW EVENT CARDS --------------------------------------------------------->
<div class="allEvents" style="margin-top:130px;">
   <?php
   //-----ATTEND EVENT----------------------------------------------------------
        if (isset($_POST['plus'])){

             //get eventID (hidden input) and userID (session)
             $eventid = $_POST['eventID'];
             $userid = $_SESSION['userID'];

             $insertQuery = "INSERT INTO Attend (eventID, userID) VALUES ($eventid, $userid)";
             $insert_stmt = $db->prepare($insertQuery);
             $insert_stmt->execute();
             header("Location: events.php");

         }

        //-----UNATTEND EVENT---------------------------------------------------
        if (isset($_POST['minus'])){

            $userid = $_SESSION['userID'];
            $eventid = $_POST['eventID'];

            $deleteQuery = "DELETE FROM Attend WHERE eventID = '{$eventid}' AND userID = $userid ";
            $delete_stmt = $db->prepare($deleteQuery);
            $delete_stmt->execute();
            header("Location: events.php");
         }


        //-----DELETE PASSED EVENTS---------------------------------------------
        $current_time = date("Y/m/d");

        $stmt = $db->prepare("DELETE FROM Events WHERE startdate < '$current_time'");
        $stmt->execute();

        //-----GET EVENTS FROM ATTEND-------------------------------------------

        $userid = $_SESSION['userID'];
        $attendQuery = "SELECT eventID FROM Attend WHERE userID = '{$userid}' ";
        $attend_stmt = $db->prepare($attendQuery);
        $attend_stmt->execute();
        $attend_stmt->bind_result($eventID);

        $array = array();
        //-- STORE EVENTID IN ARRAY
        while($attend_stmt->fetch()){
            $array[] = $eventID;
        }
        $attend_stmt->close();
        //print_r ($array);


        //-----GET EVENTS & DRAW CARDS------------------------------------------

        $query = "SELECT eventID, title, description, startdate, enddate, time, price, location, image, link, host FROM Events ORDER by startdate, time";
        $stmt = $db->prepare($query);

        $stmt->execute();
        $stmt->bind_result($eventID, $title, $description, $startdate, $enddate, $time, $price, $location, $image, $link, $host);

        while($stmt->fetch()){
            //-- ALREADY ATTENDED EVENTS ---------------------------------------
            if ((in_array ($eventID, $array))){?>
                <div class="eventContainerOne">
                    <div class="imgContainer" style="background-image: url('uploadedfiles/<?php echo "$image"; ?>');"></div>
                    <form method="POST" action='events.php'>
                          <input type="submit" value="-" class="plusBtn" name="minus">
                          <input type="hidden" value="<?php echo "$eventID"; ?>" name="eventID">
                      </form>
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
            //-- NOT ATTENDED EVENTS -------------------------------------------
            }else {
            //print_r ($array);?>
            <div class="eventContainerOne">
                <div class="imgContainer" style="background-image: url('uploadedfiles/<?php echo "$image"; ?>');"></div>
                <form method="POST" action='events.php'>
                      <input type="submit" value="+" class="plusBtn" name="plus">
                      <input type="hidden" value="<?php echo "$eventID"; ?>" name="eventID">
                  </form>
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
