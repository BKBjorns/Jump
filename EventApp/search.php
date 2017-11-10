<?php
//-- PAGE SETUP ----------------------------------------------------------------


//-- INCLUDE
include("header.php");
include("menu.php");

//-- DATABASE CONNECTION
@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
if ($db->connect_error) {
   echo "could not connect: " . $db->connect_error;
   header("Location: index.php");
   exit();
}
?>

<div class="search" action="">
  <form class="searchForm" action="search.php" method="POST">
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
  <?php




  $shTitle = "";
  $shDate = "";
  $shSchool = "";


  if (isset($_POST) && !empty($_POST)) {
  # Get data from form
      $shTitle = trim($_POST['shTitle']);
      $shDate = $_POST['shDate'];
      $shSchool = $_POST['shSchool'];

      //-- XSS -----------------------------------------------------------------------------------------------------
      $title = htmlentities($shTitle);
      $security = mysqli_real_escape_string($db, $shTitle);

      $title = addslashes($shTitle);
  }




if(isset($_POST['submit'])){

  if (!$_POST['shTitle'] && !$_POST['shDate'] && !$_POST['shSchool']){
    echo "<p>You must fill out at least one field.</p>";
  }else{
    $query = "";



      if ($shTitle && !$shDate && !$shSchool) {
          $query = "SELECT * FROM Events WHERE title OR host OR description LIKE '%" . $shTitle . "%' ORDER by startdate, time";
          //echo "1";
        }

      if(!$shTitle && $shDate && !$shSchool){
          $query = "SELECT * FROM Events WHERE startdate LIKE '%" . $shDate . "%' ORDER by startdate, time";
          //echo $_POST['shDate'];
          //echo "2";
      }

      if(!$shTitle && !$shDate && $shSchool){
          $query = "SELECT * FROM Events WHERE school LIKE '%" . $shSchool . "%' ORDER by startdate, time";
          //echo $_POST['shSchool'];
          //echo "3";
      }



      if ($shTitle && $shDate && !$shSchool) {
          $query = "SELECT * FROM Events WHERE title OR host OR description LIKE '%" . $shTitle . "%'AND startdate LIKE '%" . $shDate . "%' ORDER by startdate, time";
          //echo "4";
      }


      if ($shTitle && !$shDate && $shSchool) {
          $query = "SELECT * FROM Events WHERE title OR host OR description LIKE '%" . $shTitle . "%'AND school LIKE '%" . $shSchool . "%' ORDER by startdate, time";
          //echo "5";
      }

      if(!$shTitle && $shDate && $shSchool){
          $query = "SELECT * FROM Events WHERE startdate LIKE '%" . $shDate . "%' AND school LIKE '%" . $shSchool . "%' ORDER by startdate, time";
          //echo $_POST['shDate'];
          //echo "6";
      }

      if ($shTitle && $shDate && $shSchool) {
          $query = "SELECT * FROM Events WHERE title OR host OR description LIKE '%" . $shTitle . "%' AND startdate LIKE '%" . $shDate . "%' AND school LIKE '%" . $shSchool . "%' ORDER by startdate, time";
          //echo "7";
      }



        $stmt = $db->prepare($query);
        //echo $query;
        $stmt->bind_result($eventID, $title, $description, $startdate, $enddate, $time, $price, $location, $image, $link, $host, $school, $country);

        $stmt->execute();
        //echo $eventID;

    // When there is no search result
        $stmt->store_result();
        $noresult = $stmt->num_rows();

        if($noresult === 0){
          echo "Your search did not return any results";
        }else{
          //$userid = $_SESSION['userID'];
          //$eventid = $_POST['eventID'];
          //echo $userid;
          //echo $eventid;
          //-----ATTEND EVENT----------------------------------------------------------
           if (isset($_POST['plus'])){

                //get eventID (hidden input) and userID (session)
                $userid = $_SESSION['userID'];
                $eventid = $_POST['eventid'];

                $insertQuery = "INSERT INTO Attend (eventID, userID) VALUES (?, ?)";
                $insert_stmt = $db->prepare($insertQuery);
                $insert_stmt->bind_param('ii', $eventid, $userid);
                $insert_stmt->execute();
              //  header("Location: events.php");

            }

           //-----UNATTEND EVENT---------------------------------------------------
           if (isset($_POST['minus'])){

               $userid = $_SESSION['userID'];
               $eventid = $_POST['eventid'];

               $deleteQuery = "DELETE FROM Attend WHERE eventID = '{$eventid}' AND userID = $userid ";
               $delete_stmt = $db->prepare($deleteQuery);
               $delete_stmt->execute();
               //header("Location: events.php");
            }

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


            while($stmt->fetch()){
              //echo $eventID,$title, $description, $startdate, $enddate, $time, $price, $location, $image, $link, $host;
              //-- ALREADY ATTENDED EVENTS ---------------------------------------
                            if ((in_array ($eventID, $array))){ ?>
                  <div class="eventContainerOne">
                      <div class="imgContainer" style="background-image: url('uploadedfiles/<?php echo "$image"; ?>');"></div>
                      <form method="POST" action='user.php'>
                          <input type="submit" value="—" class="plusBtn" name="minus">
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
              //-- events not hosted by that organisation
              }else if ((in_array ($eventID, $array)) === false) { ?>
                  <div class="eventContainerOne">
                <div class="imgContainer" style="background-image: url('uploadedfiles/<?php echo "$image"; ?>');"></div>
                <form method="POST" action='user.php'>
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
          }

        } ?>

    </div>

  <?php
  }

 } ?>



<?php
    include("footer.php");
?>
