<?php 
session_start();
    if (!isset($_SESSION['userID'])) {
        header("Location:index.php");
    }

include("header.php");
include("menu.php");
include ("userinfo.php");
?>
    <div class="addEvent">
        <a href="addevent.php"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
    </div>
    <div style="margin-top: 100px;" class="allEvents">
      <?php 
        //session_start();
        $userID = $_SESSION['userID'];
        $organisation = $_SESSION['organisation'];
        
        
        //Delete event when date is over
        $current_time = date("Y/m/d");

        $stmt = $db->prepare("DELETE FROM Events WHERE startdate < '$current_time'");
        $stmt->execute();
        
        
        
        //This deletes the event from the DB.
        if (isset($_POST['minus'])){

            //@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

            $eventid = $_POST['eventID'];

            //get the eventID from the input and the userID from the session and insert it into the database
            $deleteQuery = "DELETE FROM Events WHERE eventID = '{$eventid}'";
            //$stmt->bind_param('i', $eventID);
            $stmt = $db->prepare($deleteQuery);
            $stmt->execute();             

        }
        //echo $userID;
        
        @ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        
        if ($db->connect_error) {
            echo "could not connect: " . $db->connect_error;
            exit();
        }
        
        // To delete events if the date is past.
        $current_time = date("Y/m/d");

        $stmt = $db->prepare("DELETE FROM Events WHERE startdate < '$current_time'");
        $stmt->execute();
        
        
        
//        $query = "SELECT Events.eventID, Events.title, Events.description, Events.startdate, Events.enddate, Events.time, Events.price, Events.location, Events.image, Events.link, Events.host FROM Events 
//        JOIN Attend on Events.eventID = Attend.eventID
//        JOIN Users on Users.userID = Attend.userID
//        WHERE Users.userID = '{$userID}'";
        
        
        $query = "SELECT eventID, title, description, startdate, time, location, image, host FROM Events WHERE host = '{$organisation}'";
        
        
        $stmt = $db->prepare($query);
        //$stmt->bind_param('i', $userID);
        $stmt->bind_result($eventID, $title, $description, $startdate, $time, $location, $image, $host);
        $stmt->execute();
        while($stmt->fetch()){ ?>
            
       <!---------------------------------EVENT ONE-->
       <div class="eventContainerOne">
          <!-----------event img & attend event btn-->
          <div class="imgContainer" style="background-image: url('uploadedfiles/<?php echo "$image"; ?>');"></div>
          <form method="POST" action=''>
                  <input type="submit" value="—" class="plusBtn" name="minus">
                  <input type="hidden" value="<?php echo "$eventID"; ?>" name="eventID">
              </form>
          <!---------event information & expand btn-->
          <div class="infoContainer">
              <div class="eventTitle">
                 <?php 
                    echo "<h4>$title</h4> <p><strong>Date:</strong> $startdate</p> <p><strong>Time: </strong> $time</p> <p><strong>Location: </strong> $location</p>";
                  ?>
              </div> 
              <a href="#" class="expanderBtn">
                  <i class="fa fa-angle-down" aria-hidden="true"></i>
              </a>
              <p class="eventDescription">
                <?php echo "$description $host";?>
              </p>  
            </div>
       </div>
       
       <?php  } ?>

       </div>
       

       
  




<?php 


if (isset($_POST['minus'])){
    
    //@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
    
    $eventid = $_POST['eventID'];
    //$userid = $_SESSION['userID'];
             
    //get the eventID from the input and the userID from the session and insert it into the database
    $deleteQuery = "DELETE FROM Attend WHERE userID = '{$userID}' AND eventID = '{$eventid}' ";
    $stmt = $db->prepare($deleteQuery);
    $stmt->execute();
             
//    //if the event hasnt been clicked/attended before, there will no rows in the db, which means that the fetch will be empty
//    if (!$stmt->fetch()){
//        // then the userID and eventID will be added in the attend db
//        $stmt = $db->prepare($insertQuery);
//        $stmt->execute();
//        //header("location:events.php");
//    }
}




include("footer.php");

?>