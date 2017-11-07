<?php

session_start();
    if (!isset($_SESSION['userID'])) {
        header("Location:index.php");
    }

include("header.php");
include("menuOrg.php");
include("userinfo.php");

?>

    <div class="addEvent">

    <?php
         $host = $_SESSION['organisation'];
        $school = $_SESSION['school'];
        // echo $host;
        // echo $school;

      if (isset($_FILES['upload']) && !empty($_FILES['upload'])){

        $title = $_POST['title'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $location = $_POST['location'];
        $description = $_POST['description'];

        // echo "$title, $location, $description";
        // exit();

         $allowedextensions = array('jpg', 'jpeg', 'gif', 'png');

         $extension = strtolower(substr($_FILES['upload']['name'], strrpos($_FILES['upload']['name'], '.') + 1));

         $error = array ();
         if(in_array($extension, $allowedextensions) === false){

            #add a new array entry
            $error[] = 'This is not an image, upload is allowed only for images.';
             echo("<p style='margin-top:150px; color:'black';>This is not an image, upload is allowed only for images.</p>");
        }

        if($_FILES['upload']['size'] > 10000000){
          $error[]='The file exceeded the upload limit';
            echo("<p style='margin-top:150px; color:'black';>The file exceeded the upload limit</p>");
        }

        if(empty($error)){

          @ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

          if ($db->connect_error) {
              echo "could not connect: " . $db->connect_error;
              exit();
          }

          $image = $_FILES['upload']['name'];

          move_uploaded_file($_FILES['upload']['tmp_name'], "uploadedfiles/" . $image);

         $uploadQuery = ("INSERT INTO `Events`(`title`, `description`, `startdate`, `time`, `location`, `image`, `host`, `school`) VALUES ('$title', '$description', '$date', '$time','$location', '$image', '$host', '$school')");

          $stmt = $db->prepare($uploadQuery);
          $stmt->execute();
          echo "<h3>Event has been added!</h3>";
          header("location:organisation.php");
        }

      }
   ?>

    <form class="addeventForm" enctype="multipart/form-data" action="addevent.php" method="POST">
        <input type='text' name='title' placeholder='Event Title' class=''>
        <input type='date' name='date' placeholder='Event Date' class=''>
        <input type='time' name='time' placeholder='Event Time' class=''>
        <input type='text' name='location' placeholder='Event Location' class=''>
        <input type='textarea' rows="5" name='description' placeholder='Event Description' class=''>
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
