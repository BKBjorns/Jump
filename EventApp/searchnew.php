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


<!-- SEARCH FORM -------------------------------------------------------------->
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
              while ($stmt-> fetch()){?>
                  <option value="<?php echo $schoolID;?>"><?php echo $school; ?></option>
          <?php } ?>
      </select><br>
    <input type="submit" name="submit" value="Search!" class='submitBtn'>
  </form>
</div>

<div class="allEvents">
  <?php
  //-- SEARCH ------------------------------------------------------------------


  if(isset($_POST['submit'])){
    //-- get input data
    $shTitle = trim($_POST['shTitle']);
    $shDate = $_POST['shDate'];
    $shSchool = $_POST['shSchool'];

    //-- XSS -----------------------------------------------------------------------------------------------------
    $title = htmlentities($shTitle);
    $security = mysqli_real_escape_string($db, $shTitle);

    $title = addslashes($shTitle);

    //-- get event data
    $userid = $_SESSION['userID'];
    $query = "SELECT eventID FROM Events";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $stmt->bind_result($eventID);

    $array = array();
    //-- STORE EVENTID IN ARRAY
    while($stmt->fetch()){
        $array[] = $eventID;
    }
    $stmt->close();
    // print_r ($array);

    if ($shTitle && !$shDate && !$shSchool) {
        $query = "SELECT * FROM Events WHERE title LIKE '%" . $shTitle . "%' OR description like '%" . $shTitle . "%' OR host like '%" . $shTitle . "%'";
      }

    if(!$shTitle && $shDate && !$shSchool){
        $query = "SELECT * FROM Events WHERE startdate LIKE '%" . $shDate . "%'";
        //echo $_POST['shDate'];
    }

    if(!$shTitle && !$shDate && $shSchool){
        $query = "SELECT * FROM Events WHERE school LIKE '%" . $shSchool . "%'";
        //echo $_POST['shSchool'];
    }

    if ($shTitle && $shDate && !$shSchool) {
        $query = "SELECT * FROM Events WHERE title LIKE '%" . $shTitle . "%' OR description like '%" . $shTitle . "%' OR host like '%" . $shTitle . "%'AND startdate LIKE '%" . $shDate . "%'";
    }

    if ($shTitle && !$shDate && $shSchool) {
        $query = "SELECT * FROM Events WHERE title LIKE '%" . $shTitle . "%' OR description like '%" . $shTitle . "%' OR host like '%" . $shTitle . "%'AND school LIKE '%" . $shSchool . "%'";
    }

    if(!$shTitle && $shDate && $shSchool){
        $query = "SELECT * FROM Events WHERE startdate LIKE '%" . $shDate . "%' AND school LIKE '%" . $shSchool . "%'";
        //echo $_POST['shDate'];
    }

    if ($shTitle && $shDate && $shSchool) {
        $query = "SELECT * FROM Events WHERE title LIKE '%" . $shTitle . "%' OR description like '%" . $shTitle . "%' OR host like '%" . $shTitle . "%' AND startdate LIKE '%" . $shDate . "%' AND school LIKE '%" . $shSchool . "%'";
    }



      $stmt = $db->prepare($query);
      $stmt->bind_result($eventID, $title, $description, $startdate, $enddate, $time, $price, $location, $image, $link, $host, $school, $country);
      $stmt->execute();

  }
  ?>
</div>
