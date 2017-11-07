<?php

session_start();
if (!isset($_SESSION['userID'])) {
    header("Location:index.php");
}

include("header.php");
include("menu.php");
include("userinfo.php");

?>
    <div class="addEvent">

    <?php

        @ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

          if ($db->connect_error) {
              echo "could not connect: " . $db->connect_error;
              exit();
          }

        //-----GET EVENTID FROM EVENT CLICKED ---------------------------------------------------------------------------------

        $eventid = trim($_GET['eventID']);
//        $eventid = 39;
        //echo "$eventid";

        $getQuery = "SELECT eventID, title, description, startdate, enddate, time, price, location, image, link FROM Events WHERE eventID = {$eventid}";
        echo $getQuery;
        $stmt = $db->prepare($getQuery);
        $stmt->bind_result($eventID, $title, $description, $startdate, $enddate, $time, $price, $location, $image, $link);
        $stmt->execute();


        echo $title, $description, $startdate, $enddate, $time, $price, $location, $image, $link;
        ?>

    <form class="addeventForm" enctype="multipart/form-data" action="admin_add.php" method="POST">
        <input type='text' name='title' value='<?php echo "$title" ?>' class=''>
        <input type='date' name='date' value='<?php echo $startdate ?>' class=''>
        <input type='time' name='time' value='<?php echo $time ?>' class=''>
        <input type='text' name='location' value='<?php echo $image ?>' class=''>
        <input type='textarea' rows="5" name='description' value='<?php echo $description ?>' class=''>
        <h4>Picture upload</h4>
        <input type="file" name="upload" value="<?php echo $image ?>"><br>


        <div class="bContainer">
            <input class="submitEvent" type="submit" value="Save Changes">
            <a href="admin_events.php" class="backBtn">Go Back</a>
         </div>
    </form>


    </div>
<?php

    //-----UPDATE EVENT  ------------------------------------------------------------------------------------------------
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
          echo "success!";
          header("location:organisation_events.php");
        }

      }
   ?>


<?php
include("footer.php");

?>
