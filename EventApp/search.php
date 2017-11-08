<?php
session_start();
    if (!isset($_SESSION['userID'])) {
        header("Location:index.php");
    }

    include("config.php");
    include("menu.php");


    //connecting to the database.
    @ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

    //If it can't connect to the database we have an error.
    if ($db->connect_error) {
        echo "could not connect: " . $db->connect_error;
        printf("<br><a href=index.php>Return to home page </a>");
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

  }

  // if (isset($_POST['shSchool']) && $_POST['shSchool'] != ""){
  //   $shSchool = $_POST['shSchool'];
  // }


// XSS
//Makes unable to write html code into the search fields
  $shTitle = htmlentities($shTitle);

  $shTitle = addslashes($shTitle);

$query = "";

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
    $stmt->bind_result($eventID, $title, $description, $startdate, $enddate, $time, $price, $location, $image, $link, $host, $country);
    $stmt->execute();


// When there is no search result
    $stmt->store_result();
    $noresult = $stmt->num_rows();

    if($noresult === 0){
      echo "Your search did not return any results";
    }{




    }



    while($stmt->fetch()){ ?>

    <div class="eventContainerOne">
       <!-----------event img & attend event btn-->
       <div class="imgContainer" style="background-image: url('uploadedfiles/<?php echo "$image"; ?>');"></div>

       <!---------event information & expand btn-->
       <div class="infoContainer">
           <div class="eventTitle">
              <?php
                 echo "<h4>$title</h4> <p><strong>Date:</strong> $startdate</p> <p><strong>Time: </strong> $time</p> <p><strong>Location: </strong> $location</p>";
               ?>

           </div>

           <button href="#" class="expanderBtn">
               <i class="fa fa-angle-down" aria-hidden="true"></i>
           </button>
           <p class="eventDescription">
             <?php
                echo "$description <br> $host";
              ?>
           </p>
      </div>
    </div>

  <?php  } ?>

</div>

<?php
    include("footer.php");
?>
