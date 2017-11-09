<?php
session_start();
    if (!isset($_SESSION['userID'])) {
        header("Location:index.php");
    }

    include("config.php");
    include("menu.php");


    //connecting to the database.
    @ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

    //If it can't connect to the database we have an error.
    if ($db->connect_error) {
        echo "could not connect: " . $db->connect_error;
        printf("<br><a href=index.php>Return to home page </a>");
        exit();
    }


//-----DELETE PASSED EVENTS-----------------------------------------------------------------------------------
        //save the current date to delete events when date has passed
        $current_time = date("Y/m/d");

        //delete all events from the database that has passed
        $stmt = $db->prepare("DELETE FROM Events WHERE startdate < '$current_time'");
        $stmt->execute();
?>


-----  SEARCH FORM  -------------------
<div class="search" action="">
  <form class="searchForm" action="search_org.php" method="POST">
    <input type="text" name="shTitle" placeholder="Keyword"><br>
    <input type="date" name="shDate" class="shDate"><br>

    <select id='selectSchool' name="shSchool">

    <option value = "" disable selected>Select School</option>

        <?php
        $schoolDropQuery = "SELECT schoolID, schoolname FROM Schools";
        $stmt = $db->prepare($schoolDropQuery);
        $stmt->execute();
        $stmt -> bind_result($schoolID, $school);
        $array = array();


                while ($stmt-> fetch()){
                    ?>
                <option value="<?php echo $schoolID;?>"><?php echo $school; ?></option>
        <?php } ?>
    </select><br>


    <input type="submit" name="submit" value="Search!" class='submitBtn'>
  </form>
</div>



<div class="allEvents">



 <!-- --------  SEARCH ---------- -->

<?php
// Define the variable
  $shTitle = "";
  $shDate = "";
  $shSchool = "";

// Get the info from the search fields
  if (isset($_POST) && !empty($_POST)) {
  # Get data from form
      $shTitle = trim($_POST['shTitle']);
      $shDate = $_POST['shDate'];
      $shSchool = $_POST['shSchool'];

  }

// XSS
//Makes unable to write html code into the search fields
  $shTitle = htmlentities($shTitle);

  $shTitle = addslashes($shTitle);

  $shquery = "";

  if ($shTitle && !$shDate && !$shSchool) {
      $shquery = "SELECT * FROM Events WHERE title LIKE '%" . $shTitle . "%' OR description like '%" . $shTitle . "%' OR host like '%" . $shTitle . "%'";
    }

  if(!$shTitle && $shDate && !$shSchool){
      $shquery = "SELECT * FROM Events WHERE startdate LIKE '%" . $shDate . "%'";

  }

  if(!$shTitle && !$shDate && $shSchool){
      $shquery = "SELECT * FROM Events WHERE school LIKE '%" . $shSchool . "%'";

  }

  if ($shTitle && $shDate && !$shSchool) {
      $shquery = "SELECT * FROM Events WHERE title LIKE '%" . $shTitle . "%' OR description like '%" . $shTitle . "%' OR host like '%" . $shTitle . "%'AND startdate LIKE '%" . $shDate . "%'";
  }

  if ($shTitle && !$shDate && $shSchool) {
      $shquery = "SELECT * FROM Events WHERE title LIKE '%" . $shTitle . "%' OR description like '%" . $shTitle . "%' OR host like '%" . $shTitle . "%'AND school LIKE '%" . $shSchool . "%'";
  }

  if(!$shTitle && $shDate && $shSchool){
      $shquery = "SELECT * FROM Events WHERE startdate LIKE '%" . $shDate . "%' AND school LIKE '%" . $shSchool . "%'";
      //echo $_POST['shDate'];
  }

  if ($shTitle && $shDate && $shSchool) {
      $shquery = "SELECT * FROM Events WHERE title LIKE '%" . $shTitle . "%' OR description like '%" . $shTitle . "%' OR host like '%" . $shTitle . "%' AND startdate LIKE '%" . $shDate . "%' AND school LIKE '%" . $shSchool . "%'";
  }



    $stmt = $db->prepare($shquery);
    $stmt->bind_result($eventID, $title, $description, $startdate, $enddate, $time, $price, $location, $image, $link, $host, $country);
    $stmt->execute();

// When there is no search result
    $stmt->store_result();
    $result = $stmt->num_rows();

    if($result === 0){
      echo "Your search did not return any results";
    }

    if($result != 0){




//-----GET EVENTS FROM ORGANISATION (TABLE EVENTS)---------------------------------------------------------------------------------

        //get the user ID
        $userid = $_SESSION['userID'];
        $organisation = $_SESSION['organisation'];
        //echo $organisation;
        $eventQuery = "SELECT eventID FROM Events WHERE host = '{$organisation}' ";
        $event_stmt = $db->prepare($eventQuery);
        $event_stmt->execute();
        $event_stmt->bind_result($eventID);

        $array = array();

        while($event_stmt->fetch()){
            $array[] = $eventID;
        }
        $event_stmt->close();


//-----GET EVENTS & DRAW CARDS--------------------------------------------------------------------------------

            //get all event data from the database
            // $query = "SELECT eventID, title, description, startdate, enddate, time, price, location, image, link, host FROM Events ORDER by startdate, time";
            // $stmt = $db->prepare($query);
            //
            // $stmt->execute();
            // $stmt->bind_result($eventID, $title, $description, $startdate, $enddate, $time, $price, $location, $image, $link, $host);

            while($stmt->fetch()){
                if ((in_array ($eventID, $array))){
                      //echo $eventID; ?>
                    <div class="eventContainerOne">
                        <!----------------------------------------event img-->
                        <div class="imgContainer" style="background-image: url('uploadedfiles/<?php echo "$image"; ?>');"></div>
                        <!---------------------------------------edit Bnt-->
                        <?php
                          echo '<a class="editBtn" href="organisation_edit.php?eventID=' . urlencode($eventID) . '">
                               <i class="fa fa-pencil" aria-hidden="true"></i></a>';
                          ?>

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
                            <?php
                               echo "$description <br> $host";
                             ?>
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
                        <!-- <form method="POST" action='events.php'>
                              <input type="submit" value="+" class="plusBtn" name="plus">
                              <input type="hidden" value="<?php echo "$eventID"; ?>" name="eventID">
                          </form> -->
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
                            <?php
                               echo "$description <br> $host";
                             ?>
                          </p>
                        </div>
                        </div>

                <?php
                }
           }



    //-----GET EVENTS ------------------------------------------------------------------------------------------------

    //this selects all event information from the Events db
    // $query = "SELECT eventID, title, description, startdate, enddate, time, price, location, image, link, host FROM Events ORDER by startdate, time";
    // $stmt = $db->prepare($query);
    // //binds the db information to the variables
    // $stmt->bind_result($eventID, $title, $description, $startdate, $enddate, $time, $price, $location, $image, $link, $host);
    // $stmt->execute();

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
                 echo "<h4>$title</h4> <p><strong>Date:</strong> $startdate</p> <p><strong>Time: </strong> $time</p> <p><strong>Location: </strong> $location</p>";
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

  <?php  }

  }?>

</div>

<?php
    include("footer.php");
?>
