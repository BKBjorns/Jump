<?php
session_start();
    if (!isset($_SESSION['userID'])) {
        header("Location:index.php");
    }

    include("config.php");
    include("menu.php");
?>

<div class="search" action="">
  <!-- <i class="fa fa-search" aria-hidden="true"></i> -->
  <form class="searchForm" action="search.php" method="POST">
    <input type="text" name="shTitle" placeholder="Keyword"><br>
    <input type="date" name="shDate" class="shDate"><br>

    <select class='shSchool' name="shSchool">
          <option value="School" disabled selected>Select School</option>
          <option value="JTH">School of Engineering</option>
          <option value="JIBS">Jönköping International Business School</option>
          <option value="HLK">School of Education and Communication</option>
          <option value="HALSO">School of Health and Welfare</option>
          <option value="otherSchool">Other</option>
    </select><br>

    <!-- <select class='shOrg' name="shOrg">
        <option value="organisation" disabled selected>Select organisation</option>
        <option value="SU">Student Union</option>
        <option value="HITECH">HI Tech</option>
        <option value="HINT">HINT</option>
        <option value="HIKE">HIKE</option>
        <option value="HILIFE">HI Life</option>
        <option value="SUSHI">S.U.S.H.I.</option>
        <option value="LOK">L.O.K.</option>
        <option value="Kosmo">Kosmo</option>
        <option value="JSA">J.S.A.</option>
        <option value="JUSA">JUSA.</option>
        <option value="JURA">JURA.</option>
        <option value="JISC">JISC</option>
        <option value="Qult">Qultmästeriet</option>
        <option value="spectra">Spectra</option>
        <option value="WCN">WestCoast Nation</option>
    </select><br> -->

    <input type="submit" name="submit" value="Search!" class='submitBtn'>
  </form>
</div>

<div class="allEvents">
  <?php

  //connecting to the database.
  @ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

  //If it can't connect to the database we have an error.
  if ($db->connect_error) {
      echo "could not connect: " . $db->connect_error;
      printf("<br><a href=index.php>Return to home page </a>");
      exit();
  }


  $shTitle = "";
  $shDate = "";
  $shSchool = "";

  if (isset($_POST) && !empty($_POST)) {
  # Get data from form
      $shTitle = trim($_POST['shTitle']);
      $shDate = $_POST['shDate'];
      $shSchool = $_POST['shSchool'];

  }

  // if (isset($_POST['shSchool']) && $_POST['shSchool'] != ""){
  //   $shSchool = $_POST['shSchool'];
  // }


// XSS
//Makes unable to write html code into the search fields
  $shTitle = htmlentities($shTitle);


  $shTitle = addslashes($shTitle);



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
    $stmt->bind_result($eventID, $title, $description, $startdate, $enddate, $time, $price, $location, $image, $link, $host);
    $stmt->execute();

// When there is no search result
    $stmt->store_result();
    $noresult = $stmt->num_rows();

    if($noresult === 0){
      echo "Your search did not return any results";
    }



    while($stmt->fetch()){ ?>

    <div class="eventContainerOne">
       <!-----------event img & attend event btn-->
       <div class="imgContainer" style="background-image: url('uploadedfiles/<?php echo "$image"; ?>');"></div>
       <form method="POST" action=''>
               <input type="submit" value="+" class="plusBtn" name="plus">
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
    include("footer.php");
?>
