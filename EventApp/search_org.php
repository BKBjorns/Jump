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


         //-----DELETE EVENT---------------------------------------------------
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
                        <p class="eventTitle">
                           <?php
                              echo "<h4>$title</h4> <p><strong>Date:</strong> $startdate</p> <p><strong>Time: </strong> $time</p>";
                            ?>
                        </p>
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
                      <div class="infoContainer">
                        <p class="eventTitle">
                           <?php
                              echo "<h4>$title</h4> <p><strong>Date:</strong> $startdate</p> <p><strong>Time: </strong> $time</p>";
                            ?>
                        </p>
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

<?php } ?>



<?php
    include("footer.php");
?>
