<?php
//-- PAGE SETUP ----------------------------------------------------------------


//-- INCLUDE
include("admin_header.php");
include("../menu.php");
include("../userinfo.php");

//--ADMIN SECURITY
$type = $_SESSION['type'];

if ( $type == 'student'){
  header("location:../user.php");
  exit();
}else if ($type == 'organisation'){
  header("location:../organisation.php");
}


//-- DATABASE CONNECTION
@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
if ($db->connect_error) {
   echo "could not connect: " . $db->connect_error;
   header("Location: index.php");
   exit();
}
?>

<!-- DRAW ALL EVENTS ---------------------------------------------------------->
<div class="allEvents" style="margin-top: 130px;">
<?php
  //-- DELETE EVENT WHEN PASSED ------------------------------------------------
  $current_time = date("Y/m/d");

  $stmt = $db->prepare("DELETE FROM Events WHERE startdate < '$current_time'");
  $stmt->execute();


  //-- GET ALL EVENTS FROM DB
  $query = "SELECT eventID, title, description, startdate, enddate, time, price, location, image, link, host FROM Events ORDER by startdate, time";
  $stmt = $db->prepare($query);
  $stmt->bind_result($eventID, $title, $description, $startdate, $enddate, $time, $price, $location, $image, $link, $host);
  $stmt->execute();

  while($stmt->fetch()){ ?>

      <div class="eventContainerOne">
        <div class="imgContainer" style="background-image: url('../uploadedfiles/<?php echo "$image"; ?>');"></div>
        <?php
          echo '<a class="editBtn" href="admin_edit.php?eventID=' . urlencode($eventID) . '">
               <i class="fa fa-pencil" aria-hidden="true"></i></a>';
          ?>
          <div class="infoContainer">
            <div class="eventTitle">
             <?php
             echo "<h4>$title</h4> <p><strong>Date:</strong> $startdate</p> <p><strong>Time: </strong> $time</p> <p><strong>Location: </strong> $location</p><p class='descriptionHost'><strong>$host</strong>
             </p>";
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
            
          </div>
      </div>
 <?php  }
  ?>
</div>



<?php
include("../footer.php");
?>
