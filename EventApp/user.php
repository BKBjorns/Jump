<?php 
include("header.php");
include("menu.php");
include("userinfo.php");

?>
    <div style="margin-top: 150px;" class="allEvents">
      <?php 
        //session_start();
        $userID = $_SESSION['userID'];
        //echo $userID;
        
        @ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        
        if ($db->connect_error) {
            echo "could not connect: " . $db->connect_error;
            exit();
        }
        
        
        if (isset($_POST['minus'])){
    
    //@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
    
    $eventid = $_POST['eventID'];
    //$userid = $_SESSION['userID'];
             
    //get the eventID from the input and the userID from the session and insert it into the database
    $deleteQuery = "DELETE FROM Attend WHERE userID = '{$userID}' AND eventID = '{$eventid}' ";
    $stmt = $db->prepare($deleteQuery);
    $stmt->execute();
             

}
        
        $query = "SELECT Events.eventID, Events.title, Events.description, Events.startdate, Events.enddate, Events.time, Events.price, Events.location, Events.image, Events.link, Events.host FROM Events 
        JOIN Attend on Events.eventID = Attend.eventID
        JOIN Users on Users.userID = Attend.userID
        WHERE Users.userID = '{$userID}'";
        

       
        
        $stmt = $db->prepare($query);
        //$stmt->bind_param('i', $userID);
        $stmt->bind_result($eventID, $title, $description, $startdate, $enddate, $time, $price, $location, $image, $link, $host);
        $stmt->execute();
        while($stmt->fetch()){ ?>
            
       <!---------------------------------EVENT ONE-->
       <div class="eventContainerOne">
                <!----------------------------------------event img-->
                <div class="imgContainer" style="background-image: url('uploadedfiles/<?php echo "$image"; ?>');"></div>
                <!---------------------------------------attend Bnt-->
                <form method="POST" action='user.php'>
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
       
       <?php  } ?>

       </div>
       

       
  




<?php 







include("footer.php");
?>