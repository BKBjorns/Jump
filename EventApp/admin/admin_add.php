<?php
session_start();
    if (!isset($_SESSION['userID'])) {
        header("Location:../index.php");
    }

include("admin_header.php");
include("admin_menu.php");
include("../userinfo.php");

?>
<?php
//    session_start();
//    $type =  $_SESSION['type'];
//    if($type == 'organisation'){
//        header("location:../login.php");
//        exit();
//
//    } else if($type == 'student'){
//        header("location:../login.php");
//        exit();
//    }
?>
    <div class="addEvent">

    <?php
      if (isset($_FILES['upload']) && !empty($_FILES['upload'])){

        $title = $_POST['title'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $location = $_POST['location'];
        $host = $_POST['host'];
        $description = $_POST['description'];
        $school = $_POST['school'];

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

          @ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

          if ($db->connect_error) {
              echo "could not connect: " . $db->connect_error;
              exit();
          }

          $image = $_FILES['upload']['name'];

          move_uploaded_file($_FILES['upload']['tmp_name'], "../uploadedfiles/" . $image);

        $uploadQuery = ("INSERT INTO `Events`(`title`, `description`, `startdate`, `time`, `image`, `location`, `host`, school) VALUES ('$title', '$description', '$date', '$time', '$image', '$location', '$host', '$school')");

          $stmt = $db->prepare($uploadQuery);
          $stmt->execute();
          echo "<h3>Event has been added!</h3>";
          header("location:admin_events.php");
        }

      }
   ?>

    <form class="addeventForm" enctype="multipart/form-data" action="admin_add.php" method="POST">
        <input type='text' name='title' placeholder='Event Title' class=''>
        <input type='date' name='date' placeholder='Event Date' class=''>
        <input type='time' name='time' placeholder='Event Time' class=''>
        <input type='text' name='location' placeholder='Event Location' class=''>
        <select name="host" value="host" style="padding-top = 25px;">

           <?php
            $hostDropQuery = "SELECT organisation FROM Users WHERE `type` = 'organisation'";
            $stmt = $db->prepare($hostDropQuery);
            $stmt->execute();
            $stmt -> bind_result($organisation);
            $array = array();
            $result1 = mysqli_query($db, $hostDropQuery);
            ?>
            <option value = "" disable selected>Select Host</option>

            <?php
            while ($stmt-> fetch()){
                ?>
            <option value="<?php echo $organisation;?>"><?php echo $organisation; ?></option>
    <?php }
    print_r ($array);?>

  </select>
  <select id='selectSchool' name="school">

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
  </select>

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
