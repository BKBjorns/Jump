<?php
//-- PAGE SETUP ----------------------------------------------------------------


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
?>

<!-- FORM BEGINNING ----------------------------------------------------------->

<div class="addEvent">
<?php

//-- GET EVENTID FROM EVENT CLICKED --------------------------------------------
$eventid = trim($_GET['eventID']);

//--Get event data
$getQuery = "SELECT eventID, title, description, startdate, enddate, time, price, location, image, link, host FROM Events WHERE eventID = {$eventid}";
$stmt = $db->prepare($getQuery);

$stmt->bind_result($eventID, $title, $description, $startdate, $enddate, $time, $price, $location, $imageE, $link, $host);
$stmt->execute();

while ($stmt->fetch()) {
  //echo $eventID, $title, $description, $startdate, $enddate, $time, $price, $location, $imageE, $link, $host;
}
?>

<!-- EDIT FORM ---------------------------------------------------------------->
  <form class="addeventForm" enctype="multipart/form-data" action="" method="POST">
      <input type='text' name='title' value='<?php echo "$title" ?>' class=''>
      <input type='date' name='date' value='<?php echo $startdate ?>' class=''>
      <input type='time' name='time' value='<?php echo $time ?>' class=''>
      <input type='text' name='location' value='<?php echo $location ?>' class=''>
      <input type='textarea' rows="5" name='description' value='<?php echo $description ?>' class=''>
      <h4>Picture upload</h4>
      <input type="file" name="upload" value="<?php $imageE ?>"><br>
      <div class="bContainer">
         <div class="gap">
              <input class="submitEvent" type="submit" value="Save Changes">
          </div>
          <div class="gap">
              <a href="organisation_events.php" class="backBtn">Go Back</a>
          </div>
       </div>
  </form>
</div>

<?php
//-----UPDATE EVENT  -----------------------------------------------------------
if (isset($_FILES['upload']) && !empty($_FILES['upload'])){

    $title = trim ($_POST['title']);
    $date = trim ($_POST['date']);
    $time = trim ($_POST['time']);
    $location = trim ($_POST['location']);
    $description = trim ($_POST['description']);



    //-- XSS -----------------------------------------------------------------------------------------------------
    $title = htmlentities($title);
    $date = htmlentities($date);
    $time = htmlentities($time);
    $location = htmlentities($location);
    $description = htmlentities($description);


    $security = mysqli_real_escape_string($db, $title);
    $security = mysqli_real_escape_string($db, $date);
    $security = mysqli_real_escape_string($db, $location);
    $security = mysqli_real_escape_string($db, $description);

    $title = addslashes($title);
    $date = addslashes($date);
    $time = addslashes($time);
    $location = addslashes($location);
    $description = addslashes($description);

     //-- FILE UPLOAD SECURITY/EXTENSIONS --------------------------------------
     $allowedextensions = array('jpg', 'jpeg', 'gif', 'png');
     $extension = strtolower(substr($_FILES['upload']['name'], strrpos($_FILES['upload']['name'], '.') + 1));
     $error = array ();

     if(in_array($extension, $allowedextensions) === false){
        $error[] = 'Please upload an image with allowed extension. Images only.';
         echo("<p style='color:'black';>This is not an image, upload is allowed only for images.</p>");
    }
    if($_FILES['upload']['size'] > 1000000){
      $error[]='The file exceeded the upload limit';
        echo("<p style='margin-top:150px; color:'black';>The file exceeded the upload limit</p>");
    }
    if(empty($error)){
      $image = $_FILES['upload']['name'];
      move_uploaded_file($_FILES['upload']['tmp_name'], "uploadedfiles/" . $image);

      $updateQuery = ("UPDATE Events SET title= '{$title}', description='{$description}', startdate='{$date}', time='{$time}',price='{$price}',location='{$location}', image='{$image}' WHERE eventID = '{$eventid}'");
      $stmt = $db->prepare($updateQuery);
      $stmt->execute();
      //echo "success!";
      header("location:organisation_events.php");
    }

  }
?>


<?php
include("footer.php");

?>
