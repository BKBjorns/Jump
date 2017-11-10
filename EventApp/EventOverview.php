<?php
//-- PAGE SETUP ----------------------------------------------------------------
session_start();
//-- INCLUDE
include("header.php");

//-- DATABASE CONNECTION
@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
if ($db->connect_error) {
   echo "could not connect: " . $db->connect_error;
   header("Location: index.php");
   exit();
}
?>

<!-- HEADER AREA -------------------------------------------------------------->
<div id="top" class="logo">
    <a href="index.php"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
</div>

<!-- EVENT CARDS -------------------------------------------------------------->
<div class="allEvents">
<?php
    //-- DELETE PASSED EVENTS --------------------------------------------------
    $current_time = date("Y/m/d");

    $stmt = $db->prepare("DELETE FROM Events WHERE startdate < '$current_time'");
    $stmt->execute();


    //-- GET EVENT DATA --------------------------------------------------------
    $query = "SELECT eventID, title, description, startdate, enddate, time, price, location, image, link, host FROM Events ORDER by startdate, time";
    $stmt = $db->prepare($query);
    $stmt->bind_result($eventID, $title, $description, $startdate, $enddate, $time, $price, $location, $image, $link, $host);
    $stmt->execute();

    //- draw cards
    while($stmt->fetch()){ ?>

       <div class="eventContainerOne">
          <div class="imgContainer" style="background-image: url('uploadedfiles/<?php echo "$image"; ?>');"></div>
          <form method="POST" action='events.php'>
                  <input type="hidden" value="<?php echo "$eventID"; ?>" name="eventID">
          </form>
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
              <p class="descriptionHost">
                <?php
                echo "<strong>$host</strong>" ?>
              </p>
          </div>
       </div>
   <?php  } ?>
</div>



<?php
include("footer.php");

?>
