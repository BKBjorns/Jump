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
include("userinfo.php");

//-- DATABASE CONNECTION
@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
if ($db->connect_error) {
   echo "could not connect: " . $db->connect_error;
   header("Location: index.php");
   exit();
}
$userID = $_SESSION['userID'];
?>

<!-- EVENT CARDS -------------------------------------------------------------->
<div style="margin-top: 150px;" class="allEvents">
<?php
  //-- DELETE EVENT ------------------------------------------------------------
  if (isset($_POST['minus'])){
    $eventid = $_POST['eventID'];

    $deleteQuery = "DELETE FROM Attend WHERE userID = '{$userID}' AND eventID = '{$eventid}' ";
    $stmt = $db->prepare($deleteQuery);
    $stmt->execute();
  }

  //-- JOIN TO GET EVENTS USER ATTENDED ----------------------------------------
  $query = "SELECT Events.eventID, Events.title, Events.description, Events.startdate, Events.enddate, Events.time, Events.price, Events.location, Events.image, Events.link, Events.host FROM Events
  JOIN Attend on Events.eventID = Attend.eventID
  JOIN Users on Users.userID = Attend.userID
  WHERE Users.userID = '{$userID}'";

  $stmt = $db->prepare($query);
  $stmt->bind_result($eventID, $title, $description, $startdate, $enddate, $time, $price, $location, $image, $link, $host);
  $stmt->execute();
  while($stmt->fetch()){ ?>

 <div class="eventContainerOne">
      <div class="imgContainer" style="background-image: url('uploadedfiles/<?php echo "$image"; ?>');"></div>
      <form method="POST" action='user.php'>
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
 <?php  } ?>
 </div>


<?php 
include("footer.php");
?>
