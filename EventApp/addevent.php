<?php

session_start();
    if (!isset($_SESSION['userID'])) {
        header("Location:index.php");
    }

include("header.php");
include("menuOrg.php");
include("userinfo.php");


@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
if ($db->connect_error) {
    echo "could not connect: " . $db->connect_error;
    exit();
}
?>

<!-- FORM BEGINNING ------------------------------------------------------------------------------------------------->

    <div class="addEvent">
    <?php
        $host = $_SESSION['organisation'];
        $school = $_SESSION['school'];
        // echo $host;
        // echo $school;

      //-- GET DATA FROM INPUT FIELDS ------------------------------------------------------------------------------
      if (isset($_FILES['upload']) && !empty($_FILES['upload'])){

        $title = trim ($_POST['title']);
        $date = trim ($_POST['date']);
        $time = trim ($_POST['time']);
        $location = trim ($_POST['location']);
        $description = trim ($_POST['description']);
        $country = trim ($_POST['country']);


        //-- XSS -----------------------------------------------------------------------------------------------------
        $title = htmlentities($title);
        $date = htmlentities($date);
        $time = htmlentities($time);
        $location = htmlentities($location);
        $description = htmlentities($description);
        $country = htmlentities($country);

        $security = mysqli_real_escape_string($db, $title, $date, $time, $location, $description, $country);

        $title = addslashes($title);
        $date = addslashes($date);
        $time = addslashes($time);
        $location = addslashes($location);
        $description = addslashes($description);
        $country = addslashes($country);




        //-- FILE UPLOAD SECURITY/EXTENSIONS --------------------------------------------------------------------------
         $allowedextensions = array('jpg', 'jpeg', 'gif', 'png');
         $extension = strtolower(substr($_FILES['upload']['name'], strrpos($_FILES['upload']['name'], '.') + 1));
         $error = array ();

         //ERROR ONE, WRONG FORMAT
         if(in_array($extension, $allowedextensions) === false){
            #add a new array entry
            $error[] = 'This is not an image, upload is allowed only for images.';
            echo("<p style='margin-top:150px; color:'black';>This is not an image, upload is allowed only for images.</p>");
          }
        //ERROR TWO, SIZE
        if($_FILES['upload']['size'] > 10000000){
          $error[]='The file exceeded the upload limit';
            echo("<p style='margin-top:150px; color:'black';>The file exceeded the upload limit</p>");
        }
        //NO ERROR
        if(empty($error)){
          $image = $_FILES['upload']['name'];
          move_uploaded_file($_FILES['upload']['tmp_name'], "uploadedfiles/" . $image);

          $uploadQuery = ("INSERT INTO `Events`(`title`, `description`, `startdate`, `time`, `location`, `image`, `host`, `school`, `country`) VALUES ('$title', '$description', '$date', '$time','$location', '$image', '$host', '$school', '$country')");
          $stmt = $db->prepare($uploadQuery);
          $stmt->execute();
          //echo "<h3>Event has been added!</h3>";
          header("location:organisation.php");
        }
      }
   ?>

<!-- FORM CONTINUE --------------------------------------------------------------------------------------------------->
    <form class="addeventForm" enctype="multipart/form-data" action="addevent.php" method="POST">
        <input type='text' name='title' placeholder='Event Title' class=''>
        <input type='date' name='date' placeholder='Event Date' class=''>
        <input type='time' name='time' placeholder='Event Time' class=''>
        <input type='text' name='location' placeholder='Event Location' class=''>
        <input type='textarea' rows="5" name='description' placeholder='Event Description' class=''>
        <select id='selectCountry' name="country">
          <option value = "" disable selected>Select Country</option>
          <?php
            //-- COUNTRY DROPDOWN -----------------------------------------------------------------------------------------------
            $countryDropQuery = "SELECT code, name FROM country";
            $stmt = $db->prepare($countryDropQuery);
            $stmt->execute();
            $stmt -> bind_result($code, $country);
            $array = array();

            while ($stmt-> fetch()){?>
                <option value="<?php echo $code;?>"><?php echo $country; ?></option>
            <?php } ?>
        </select>

<?php
    //-- CITY DROPDOWN -----------------------------------------------------------------------------------------------
    if (isset($_POST['country'])){?>
      <select id='selectCity' name="city">
        <option value = "" disable selected>Select City</option>
        <?php
          $cityDropQuery = "SELECT ID, Name FROM city WHERE CountryCode = '{$code}' ";
          $stmt = $db->prepare($cityDropQuery);
          $stmt->execute();
          $stmt -> bind_result($cityID, $city);
          $array = array();

          while ($stmt-> fetch()){?>
              <option value="<?php echo $cityID;?>"><?php echo $city; ?></option>
            <?php } ?>
        </select>
      <?php  }?>
      <!-- FORM REST -------------------------------------------------------------------------------------------------->
      <h4>Picture upload</h4>
      <input type="file" name="upload"><br>
      <div class="bContainer">
         <div class="gap">
           <input class="submitEvent" type="submit" value="Add Event"/>
        </div>
        <div class="gap">
          <a href="organisation.php" class="backBtn">Go Back</a>
        </div>
      </div>
    </form>
  </div>




<?php
include("footer.php");

?>
