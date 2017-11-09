<?php
//-- PAGE SETUP ----------------------------------------------------------------

//-- CHECK IF USER IS LOGGED IN
session_start();
//if there is no user session saved, it will redirect to the start page
if (!isset($_SESSION['userID'])) {
        header("Location:../index.php");
    }

//-- INCLUDE
include("admin_header.php");
include("../menu.php");
include("../userinfo.php");

//-- DATABASE CONNECTION
//creates db connection ($db defined in config.php)
@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
//checks if there is a db connection existing
if ($db->connect_error) {
    //returns why db cannot connect
    echo "could not connect: " . $db->connect_error;
    exit();
}
?>


<!-- FORM BEGINNING ----------------------------------------------------------->

<div class="addEvent">

    <?php
      if (isset($_FILES['upload']) && !empty($_FILES['upload'])){
        //-- GET DATA FROM INPUT
        //trim: strip whitespace (or other characters) from the beginning and end of a string, src:php.net
        $title = trim ($_POST['title']);
        $date = trim ($_POST['date']);
        $time = trim ($_POST['time']);
        $location = trim ($_POST['location']);
        $description = trim ($_POST['description']);


        //-- XSS ---------------------------------------------------------------
        //htmlentities: convert all applicable characters to HTML entities, src:php.net
        $title = htmlentities($title);
        $date = htmlentities($date);
        $time = htmlentities($time);
        $location = htmlentities($location);
        $description = htmlentities($description);

        //mysqli_real_escape_string: escapes special characters in a string, src:php.net
        $security = mysqli_real_escape_string($db, $date);
        $security = mysqli_real_escape_string($db, $time);
        $security = mysqli_real_escape_string($db, $location);
        $security = mysqli_real_escape_string($db, $description);

        //addslashes: Returns a string with backslashes before characters that need to be escaped, src:php.net
        $title = addslashes($title);
        $date = addslashes($date);
        $time = addslashes($time);
        $location = addslashes($location);
        $description = addslashes($description);

        //-- FILE UPLOAD SECURITY/EXTENSIONS -----------------------------------
        //define format extensions allowed
        $allowedextensions = array('jpg', 'jpeg', 'gif', 'png');
        //strtolower: makes lowercase
        //substr: returns part of string, , src:php.net
        //Find the position of the last occurrence of a substring in a string, src:php.net
        $extension = strtolower(substr($_FILES['upload']['name'], strrpos($_FILES['upload']['name'], '.') + 1));
        //create array to push in possible errors
        $error = array ();

        //ERROR ONE, WRONG FORMAT
        if(in_array($extension, $allowedextensions) === false){
          #add a new array entry
          $error[] = 'This is not an image, upload is allowed only for images.';
          //display error
          echo("<p style='margin-top:150px; color:'black';>This is not an image, upload is allowed only for images.</p>");
        }
        //ERROR TWO, SIZE
        if($_FILES['upload']['size'] > 1000000){
          $error[]='The file exceeded the upload limit';
          echo("<p style='margin-top:150px; color:'black';>The file exceeded the upload limit</p>");
        }
        //NO ERROR
        if(empty($error)){
          //get image name
          $image = $_FILES['upload']['name'];
          //move image to upload folder
          move_uploaded_file($_FILES['upload']['tmp_name'], "../uploadedfiles/" . $image);

          $uploadQuery = ("INSERT INTO `Events`(`title`, `description`, `startdate`, `time`, `image`, `location`, `host`, school) VALUES ('$title', '$description', '$date', '$time', '$image', '$location', '$host', '$school')");
          $stmt = $db->prepare($uploadQuery);
          $stmt->execute();
          //echo "<h3>Event has been added!</h3>";
          header("location:admin_events.php");
        }
      }
   ?>
   <!-- FORM CONTINUE --------------------------------------------------------->
    <form class="addeventForm" enctype="multipart/form-data" action="admin_add.php" method="POST">
        <input type='text' name='title' placeholder='Event Title' class=''>
        <input type='date' name='date' placeholder='Event Date' class=''>
        <input type='time' name='time' placeholder='Event Time' class=''>
        <input type='text' name='location' placeholder='Event Location' class=''>
        <!-- HOST DROPDOWN ---------------------------------------------------->
        <select name="host" value="host" style="padding-top = 25px;">
          <option value = "" disable selected>Select Host</option>
           <?php
            $hostDropQuery = "SELECT organisation FROM Users WHERE `type` = 'organisation'";
            $stmt = $db->prepare($hostDropQuery);
            $stmt->execute();
            $stmt -> bind_result($organisation);
            $array = array();
            $result1 = mysqli_query($db, $hostDropQuery);

            while ($stmt-> fetch()){?>
              <option value="<?php echo $organisation;?>"><?php echo $organisation; ?></option>
            <?php }
            print_r ($array);?>
          </select>
          <!-- SCHOOL DROPDOWN ------------------------------------------------>
          <select id='selectSchool' name="school">
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
          </select>
          <!-- FORM REST ------------------------------------------------------>
          <input type='textarea' rows="5" name='description' placeholder='Event Description' class=''>
          <h4>Picture upload</h4>
          <input type="file" name="upload"><br>
          <div class="bContainer">
            <div class="gap">
                <input class="submitEvent" type="submit" value="Add Event"/>
              </div>
              <div class="gap">
                <a href="admin.php" class="backBtn">Go Back</a>
              </div>
          </div>
        </form>
    </div>




<?php
include("../footer.php");

?>
