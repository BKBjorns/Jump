<?php

session_start();
    if (!isset($_SESSION['userID'])) {
        header("Location:index.php");
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



        //-----DELETE PASSED EVENTS-----------------------------------------------------------------------------------
            //save the current date to delete events when date has passed
            $current_time = date("Y/m/d");

            //delete all events from the database that has passed
            $stmt = $db->prepare("DELETE FROM Events WHERE startdate < '$current_time'");
            $stmt->execute();


        //-----GET EVENTS FROM ORGANISATION (TABLE EVENTS)---------------------------------------------------------------------------------

            //get the user ID
            $userid = $_SESSION['userID'];
            $organisation = $_SESSION['organisation'];
            echo $organisation;
            $eventQuery = "SELECT eventID FROM Events WHERE host = '{$organisation}' ";
            $event_stmt = $db->prepare($eventQuery);
            $event_stmt->execute();
            $event_stmt->bind_result($eventID);

            $array = array();

            while($event_stmt->fetch()){
                $array[] = $eventID;
            }
            $event_stmt->close();

            // $array2 = array();
            // $array2[] = array(44);
            //
            // print_r ($array2);

            print_r ($array);


        //-----GET EVENTS & DRAW CARDS--------------------------------------------------------------------------------

            //get all event data from the database
            $query = "SELECT eventID, title, description, startdate, enddate, time, price, location, image, link, host FROM Events ORDER by startdate, time";
            $stmt = $db->prepare($query);

            $stmt->execute();
            $stmt->bind_result($eventID, $title, $description, $startdate, $enddate, $time, $price, $location, $image, $link, $host);

            while($stmt->fetch()){
                if ((in_array ($eventID, $array))){
                      //echo $eventID; ?>
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
              //echo $eventID;
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
