<?php
//-- PAGE SETUP ----------------------------------------------------------------

//-- CHECK IF USER IS LOGGED IN
session_start();
    if (!isset($_SESSION['userID'])) {
        header("Location:index.php");
    }

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
  $query = "";

    if ($shTitle && !$shDate && !$shSchool) {
        $query = "SELECT * FROM Events WHERE title LIKE '%" . $shTitle . "%' OR description like '%" . $shTitle . "%' OR host like '%" . $shTitle . "%' ORDER by startdate, time";
      }

    if(!$shTitle && $shDate && !$shSchool){
        $query = "SELECT * FROM Events WHERE startdate LIKE '%" . $shDate . "%' ORDER by startdate, time";
        //echo $_POST['shDate'];
    }

    if(!$shTitle && !$shDate && $shSchool){
        $query = "SELECT * FROM Events WHERE school LIKE '%" . $shSchool . "%' ORDER by startdate, time";
        //echo $_POST['shSchool'];
    }

    if ($shTitle && $shDate && !$shSchool) {
        $query = "SELECT * FROM Events WHERE title LIKE '%" . $shTitle . "%' OR description like '%" . $shTitle . "%' OR host like '%" . $shTitle . "%'AND startdate LIKE '%" . $shDate . "%' ORDER by startdate, time";
    }

    if ($shTitle && !$shDate && $shSchool) {
        $query = "SELECT * FROM Events WHERE title LIKE '%" . $shTitle . "%' OR description like '%" . $shTitle . "%' OR host like '%" . $shTitle . "%'AND school LIKE '%" . $shSchool . "%' ORDER by startdate, time";
    }

    if(!$shTitle && $shDate && $shSchool){
        $query = "SELECT * FROM Events WHERE startdate LIKE '%" . $shDate . "%' AND school LIKE '%" . $shSchool . "%' ORDER by startdate, time";
        //echo $_POST['shDate'];
    }

    if ($shTitle && $shDate && $shSchool) {
        $query = "SELECT * FROM Events WHERE title LIKE '%" . $shTitle . "%' OR description like '%" . $shTitle . "%' OR host like '%" . $shTitle . "%' AND startdate LIKE '%" . $shDate . "%' AND school LIKE '%" . $shSchool . "%' ORDER by startdate, time";
    }



      $stmt = $db->prepare($query);
      $stmt->bind_result($eventID, $title, $description, $startdate, $enddate, $time, $price, $location, $image, $link, $host, $school, $country);
      $stmt->execute();


  // When there is no search result
      $stmt->store_result();
      $noresult = $stmt->num_rows();

      if($noresult === 0){
        echo "Your search did not return any results";
      }else{
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
            //-- ALREADY ATTENDED EVENTS ---------------------------------------
            if ((in_array ($eventID, $array))){?>
                <div class="eventContainerOne">
                    <div class="imgContainer" style="background-image: url('uploadedfiles/<?php echo "$image"; ?>');"></div>
                    <form method="POST" action='events.php'>
                          <input type="submit" value="-" class="plusBtn" name="minus">
                          <input type="hidden" value="<?php echo "$eventID"; ?>" name="eventID">
                      </form>
                    <div class="infoContainer">
                      <p class="eventTitle">
                         <?php
                            echo "<h4>$title</h4> <p><strong>Date:</strong> $startdate</p> <p><strong>Time: </strong> $time</p>";
                          ?>
                      </p>
                      <a href="#" class="expanderBtn">
                          <i class="fa fa-angle-down" aria-hidden="true"></i>
                      </a>
                      <p class="eventDescription">
                        <?php echo "$description";?>
                      </p>
                  </div>
                </div>
            <?php
            //-- NOT ATTENDED EVENTS -------------------------------------------
          }else if ((in_array ($eventID, $array))){
            //print_r ($array);?>
            <div class="eventContainerOne">
                <div class="imgContainer" style="background-image: url('uploadedfiles/<?php echo "$image"; ?>');"></div>
                <form method="POST" action='events.php'>
                      <input type="submit" value="+" class="plusBtn" name="plus">
                      <input type="hidden" value="<?php echo "$eventID"; ?>" name="eventID">
                  </form>
                <div class="infoContainer">
                  <p class="eventTitle">
                     <?php
                        echo "<h4>$title</h4> <p><strong>Date:</strong> $startdate</p> <p><strong>Time: </strong> $time</p>";
                      ?>
                  </p>
                  <a href="#" class="expanderBtn">
                      <i class="fa fa-angle-down" aria-hidden="true"></i>
                  </a>
                  <p class="eventDescription">
                    <?php echo "$description";?>
                  </p>
                </div>
              </div>
        <?php
          }
        }

      } ?>

  </div>

<?php } ?>



<?php
    include("footer.php");
?>
