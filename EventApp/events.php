<?php
include("header.php");
include("menu.php");

//display menu according to who is logged in
//session_start();
//echo $_SESSION['type'];
$type = $_SESSION['type'];

//display the menu according to user type logged in


?>


<div class="allEvents">

   <?php
    //connect database
    @ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

    //check for connection error
    if ($db->connect_error) {
            echo "could not connect: " . $db->connect_error;
            exit();
    }

//-----PLUS BTN-----------------------------------------------------------------------------------------------
        if (isset($_POST['plus'])){

             //set variables, which get data
             $eventid = $_POST['eventID'];
             $userid = $_SESSION['userID'];

             //get the eventID from the input and the userID from the session and insert it into the database
             $insertQuery = "INSERT INTO Attend (eventID, userID) VALUES ($eventid, $userid)";

             $insert_stmt = $db->prepare($insertQuery);
             $insert_stmt->execute();
             header("Location: events.php");

         }

//-----MINUS BTN----------------------------------------------------------------------------------------------
    if (isset($_POST['minus'])){

            $userid = $_SESSION['userID'];
            $eventid = $_POST['eventID'];

            //get the eventID from the input and the userID from the session and insert it into the database
            $deleteQuery = "DELETE FROM Attend WHERE eventID = '{$eventid}' AND userID = $userid ";
            //$stmt->bind_param('i', $eventID);
            $delete_stmt = $db->prepare($deleteQuery);
            $delete_stmt->execute();
            header("Location: events.php");
         }


//-----DELETE PASSED EVENTS-----------------------------------------------------------------------------------
    //save the current date to delete events when date has passed
    $current_time = date("Y/m/d");

    //delete all events from the database that has passed
    $stmt = $db->prepare("DELETE FROM Events WHERE startdate < '$current_time'");
    $stmt->execute();

//-----GET EVENTS FROM ATTEND---------------------------------------------------------------------------------

    //get the user ID
    $userid = $_SESSION['userID'];
    $attendQuery = "SELECT eventID FROM Attend WHERE userID = '{$userid}' ";
    $attend_stmt = $db->prepare($attendQuery);
    $attend_stmt->execute();
    $attend_stmt->bind_result($eventID);

    $array = array();

    while($attend_stmt->fetch()){
        $array[] = array($eventID);
    }
    $attend_stmt->close();

    $array2 = array();
    $array2[] = array(44);

    print_r ($array2);

    //print_r ($array);


//-----GET EVENTS & DRAW CARDS--------------------------------------------------------------------------------

    //get all event data from the database
    $query = "SELECT eventID, title, description, startdate, enddate, time, price, location, image, link, host FROM Events ORDER by startdate, time";
    $stmt = $db->prepare($query);

    $stmt->execute();
    $stmt->bind_result($eventID, $title, $description, $startdate, $enddate, $time, $price, $location, $image, $link, $host);

    while($stmt->fetch()){
        if ((in_array ($eventID, $array))){
              echo $eventID; ?>
            <div class="eventContainerOne">
                <!----------------------------------------event img-->
                <div class="imgContainer" style="background-image: url('uploadedfiles/<?php echo "$image"; ?>');"></div>
                <!---------------------------------------attend Bnt-->
                <form method="POST" action='events.php'>
                      <input type="submit" value="-" class="plusBtn" name="minus">
                      <input type="hidden" value="<?php echo "$eventID"; ?>" name="eventID">
                  </form>
                <!--------------------------------event information-->
                <div class="infoContainer">
                  <p class="eventTitle">
                     <?php
                        echo "<h4>$title</h4> <p><strong>Date:</strong> $startdate</p> <p><strong>Time: </strong> $time</p>";
                      ?>

                  </p>
                  <!---------------------------------expander btn-->
                  <a href="#" class="expanderBtn">
                      <i class="fa fa-angle-down" aria-hidden="true"></i>
                  </a>
                  <!----------------------------event description-->
                  <p class="eventDescription">
                    <?php echo "$description";?>
                  </p>
                </div>
                </div>

        <?php

        }else {
             //print_r ($array);
    ?>

            <div class="eventContainerOne">
                <!----------------------------------------event img-->
                <div class="imgContainer" style="background-image: url('uploadedfiles/<?php echo "$image"; ?>');"></div>
                <!---------------------------------------attend Bnt-->
                <form method="POST" action='events.php'>
                      <input type="submit" value="+" class="plusBtn" name="plus">
                      <input type="hidden" value="<?php echo "$eventID"; ?>" name="eventID">
                  </form>
                <!--------------------------------event information-->
                <div class="infoContainer">
                  <p class="eventTitle">
                     <?php
                        echo "<h4>$title</h4> <p><strong>Date:</strong> $startdate</p> <p><strong>Time: </strong> $time</p>";
                      ?>

                  </p>
                  <!---------------------------------expander btn-->
                  <a href="#" class="expanderBtn">
                      <i class="fa fa-angle-down" aria-hidden="true"></i>
                  </a>
                  <!----------------------------event description-->
                  <p class="eventDescription">
                    <?php echo "$description";?>
                  </p>
                </div>
                </div>

        <?php
        }
   }


    ?>



<?php
include("footer.php");


?>
