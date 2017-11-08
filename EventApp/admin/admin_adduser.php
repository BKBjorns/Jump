<?php
//-- PAGE SETUP ----------------------------------------------------------------

//-- CHECK IF USER IS LOGGED IN
session_start();
    if (!isset($_SESSION['userID'])) {
        header("Location:../index.php");
    }

//-- INCLUDE
include("admin_header.php");
include("admin_menu.php");
include("../userinfo.php");

//-- DATABASE CONNECTION
@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
if ($db->connect_error) {
   echo "could not connect: " . $db->connect_error;
   header("Location: index.php");
   exit();
}


//-- ADD NEW STUDENT -----------------------------------------------------------

if (isset($_POST['nuFirstname'])){
    $firstname = trim ($_POST['nuFirstname']);
    $lastname = trim ($_POST['nuLastname']);
    $email = trim ($_POST['nuEmail']);
    $pass= trim ($_POST['nuPass']);
    //$passConf= trim ($_POST['nuPassConf']);
    $school= $_POST['school'];

    //-- XSS -------------------------------------------------------------------
    $firstname = htmlentities ($firstname);
    $lastname = htmlentities ($lastname);
    $email = htmlentities ($email);
    $pass= htmlentities ($pass);
    //$passConf= htmlentities ($passConf);

    $security = mysqli_real_escape_string($db, $firstname);
    $security = mysqli_real_escape_string($db, $lastname);
    $security = mysqli_real_escape_string($db, $email);
    $security = mysqli_real_escape_string($db, $pass);

    $firstname = addslashes ($firstname);
    $lastname = addslashes ($lastname);
    $email = addslashes ($email);
    $pass= addslashes ($pass);
    //$passConf= addslashes ($passConf);


    //hashing the password
    $userpass= sha1($pass);


    //-- CHECK IF MAIL ALREADY EXISTS ------------------------------------------
    $mailQuery = "SELECT * FROM Users WHERE email = '{$email}'";
    $stmt = $db->prepare($mailQuery);
    $result=mysqli_query($db, $mailQuery);
    $email_nrRows = mysqli_num_rows($result);
    //echo $email_nrRows;



   //-- CHECK IF ALL INPUT IS FILLED OUT ---------------------------------------
    if (!$firstname || !$lastname || !$email || !$pass || !$school) {
        echo("<p style='margin-top:150px;'>You must fill out all forms</p>");
    }
    else if($email_nrRows != 0){
        echo("<p style='margin-top:150px;'>The email is already taken</p>");
    }
    //if all fields are filled out and the email doesn't exists already
    else{
        //check the file format
        if (isset($_FILES['upload']) && !empty($_FILES['upload'])){
          $allowedextensions = array('jpg', 'jpeg', 'gif', 'png');
          $extension = strtolower(substr($_FILES['upload']['name'], strrpos($_FILES['upload']['name'], '.') + 1));
          $error = array ();
          //ERROR FORMAT
          if(in_array($extension, $allowedextensions) === false){
              $error[] = 'This is not an image, upload is allowed only for images.';
              echo("<p style='margin-top:150px; color:'black';>This is not an image, upload is allowed only for images.</p>");
          }
          //ERROR SIZE
          if($_FILES['upload']['size'] > 1000000){
              $error[]='The file exceeded the upload limit';
              echo("<p style='margin-top:150px; color:'black';>The file exceeded the upload limit</p>");
          }
          //NO ERROR
          if(empty($error)){
            $image = $_FILES['upload']['name'];
            move_uploaded_file($_FILES['upload']['tmp_name'], "../uploadedfiles/" . $image);

            //insert values from input fields in db
            $stmt = $db->prepare("INSERT INTO Users values ('', 'student', ?, ?, ?, ?, ?, ?, '')");
            $stmt->bind_param('ssssss', $userpass, $email, $image, $school, $firstname, $lastname);
            $stmt->execute();
            echo "<script>window.location.href='admin_events.php'</script>";
            exit();
            }
          }
        }
      }


//-- ADD NEW ORGANISATION ------------------------------------------------------

if (isset($_POST['orgname'])){

    $orgname = trim ($_POST['orgname']);
    $email = trim ($_POST['nuEmail']);
    $pass= trim ($_POST['nuPass']);
    //$passConf= trim ($_POST['nuPassConf']);
    $school= $_POST['school'];

    //-- XSS -------------------------------------------------------------------
    $orgname = htmlentities ($orgname);
    $email = htmlentities ($email);
    $pass= htmlentities ($pass);
    //$passConf= htmlentities ($passConf);

    $security = mysqli_real_escape_string($db, $orgname);
    $security = mysqli_real_escape_string($db, $email);
    $security = mysqli_real_escape_string($db, $pass);

    $orgname = addslashes ($orgname);
    $email = addslashes ($email);
    $pass= addslashes ($pass);



    $userpass= sha1($pass);

    //-- CHECK IF ORGANISATION ALREADY EXISTS ----------------------------------
    //get all emails from db
    $mailQuery = "SELECT * FROM Users WHERE email = '{$email}'";
    //echo $mailQuery;
    $stmt = $db->prepare($mailQuery);
    $result=mysqli_query($db, $mailQuery);
    $email_nrRows = mysqli_num_rows($result);

    $hostQuery = "SELECT * FROM Users WHERE organisation = '{$orgname}'";
    //echo $hostQuery;
    $stmt = $db->prepare($hostQuery);
    $resultHost = mysqli_query($db, $hostQuery);
    $host_nrRows = mysqli_num_rows($resultHost);

    //echo $email_nrRows;

    if (!$orgname || !$email || !$pass || !$school) {
      echo("<p style='margin-top:150px;'>You must fill out all forms</p>");
    }
    else{
     $stmt = $db->prepare("INSERT INTO Users values ('', 'organisation', ?, ?, '', ?, '', '', ?)");
     $stmt->bind_param('ssss', $userpass, $email, $school, $orgname);
     $stmt->execute();
     echo "<script>window.location.href='admin_events.php'</script>";
     exit();
    }
} ?>


<div class='wrapper'>
  <div class="container">
    <!-- SELECT USER TYPE FORM  ----------------------------------------------->
    <div id='selectUserType'>
      <ul>
        <li>
          <input type="radio" name="newsturadio" value="student" id='studentRadio' onclick='showUserForm()'>
          <label for="student">Student</label>
      </li>
        <li>
          <input type="radio" name="neworgradio" value="org" onclick='showUserForm()'>
          <label for="org">Organization</label>
      </li>
      </ul>
    </div>

    <!-- NEW STUDENT FORM ----------------------------------------------------->
    <form action='admin_adduser.php' method="POST" class='newUserForm'>
      <div id='newStudentForm'>
          <input type='text' name='nuFirstname' placeholder='First name' class='inputField'>
          <br>
          <input type='text' name='nuLastname' placeholder='Last name' class='inputField'>
          <br>
          <input type='email' name='nuEmail' placeholder='Email' class='inputField'>
          <br>
          <input type='password' name='nuPass' placeholder='Password' class='inputField'>
          <br>
          <!--<input type='password' name='nuPassConf' value='Password' class='inputField'>-->
          <br>
          <!--<input type='text' name='nuSchool' value='School' class='inputField'>-->
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
          <h4>Picture upload</h4>
          <input type="file" name="upload">
          <br><br>
          <input type='submit' value='Submit!' class='submitBtn'>
       </div>
    </form>

<!-- ADD NEW ORGANISATION ----------------------------------------------------->
    <form action='admin.php' method="POST" class='newUserForm'>
        <div id='newOrgForm'>
            <input type='text' name='orgname' placeholder='Organization' class='inputField'>
            <br>
            <input type='email' name='nuEmail' placeholder='Email' class='inputField'>
            <br>
            <input type='password' name='nuPass' placeholder='Password' class='inputField'>
            <br>
            <!--<input type='password' name='nuPassConf' value='Password' class='inputField'>-->
            <br>
            <!--<input type='text' name='nuSchool' value='School' class='inputField'>-->
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
            <h4>Picture upload</h4>
            <input type="file" name="upload"><br>
            <br>
            <input type='submit' value='Submit!' class='submitBtn'>
        </div>
    </form>
  </div>
</div>

<script src="../js/newuser.js"></script>
 <?php
include("../footer.php");
?>
