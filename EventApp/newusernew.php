<?php

//-- PAGE SETUP ----------------------------------------------------------------

//-- INCLUDE
include("header.php");

//-- DATABASE CONNECTION
@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
if ($db->connect_error) {
   echo "could not connect: " . $db->connect_error;
   header("Location: index.php");
   exit();
}



?>



<div class='wrapper'>
    <div id="back">
        <a href="index.php"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
    </div>
    <div class="logoLogin">
      <div class="container">
        <!-- SELECT USER TYPE FORM  ------------------------------------------->
        <div id='selectUserType'>
            <ul>
                <li id="radio">
                    <input type="radio" name="newsturadio" value="student" id='studentRadio' onclick='showUserForm()'>
                    <label for="student" onclick='showUserForm()'>Student</label>
                </li>
                <li id="radio">
                  <input type="radio" name="neworgradio" value="org" onclick='showUserForm()'>
                  <label for="org" onclick='showUserForm()'>Organization</label>

</li>
                </li>
            </ul>
          </div>
        <!-- NEW STUDENT FORM ------------------------------------------------->
        <form method="POST" class='newUserForm' enctype="multipart/form-data">
            <div id='newStudentForm'>
                <input type='text' name='nuFirstname' placeholder='First name' class='inputField'>
                <br>
                <input type='text' name='nuLastname' placeholder='Last name' class='inputField'>
                <br>
                <input type='email' name='nuEmail' placeholder='Email' class='inputField'>
                <br>
                <input type='password' name='nuPass' placeholder='Password' class='inputField'>
                <br>
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
                <br>
                <input type='submit' value='Submit!' class='submitBtn'>
         </div>
        </form>

        <!-- ADD NEW ORGANISATION ----------------------------------------------------->
        <form method="POST" class='newUserForm' enctype="multipart/form-data">
            <div id='newOrgForm'>
              <input type='text' name='orgname' placeholder='Organization' class='inputField'>
              <br>
              <input type='email' name='nuEmail' placeholder='Email' class='inputField'>
              <br>
              <input type='password' name='nuPass' placeholder='Password' class='inputField'>
              <br>
              <!--<input type='password' name='nuPassConf' value='Password' class='inputField'>-->
              <br>
              <select id='selectSchool' name="school">
                  <option value="School" disabled selected>Select School</option>
                  <!-- GET DROPDOWN SCHOOL VALUES ---------------------------------------------------------------------->
                   <?php
                      $schoolDropQuery = "SELECT schoolID, schoolname FROM Schools";
                      $stmt = $db->prepare($schoolDropQuery);
                      $stmt->execute();
                      $stmt -> bind_result($schoolID, $school);
                      $array = array();

                      while ($stmt-> fetch()){
                          ?>
                          <option value="<?php echo $schoolID;?>"><?php echo $school; ?></option>
                  <?php
                      }?>
                  </select>
                  <!-- FILE UPLOAD ----------------------------------------------------------------------------------------->
                  <h4>Picture upload</h4>
                  <input type="file" name="upload">
                  <br>
                  <input type='submit' value='Submit!' class='submitBtn'>
            </div>
        </form>
    </div>
  </div>
</div>

<script src="js/newuser.js"></script>
<?php

//-- ADD NEW STUDENT -----------------------------------------------------------
if (isset($_POST['nuFirstname'])){

    //-- GET DATA FROM INPUT FIELDS --------------------------------------------
    //-- gets the input and gets rid of spaces (trim)
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
    //-- $passConf= htmlentities ($passConf);

    $security = mysqli_real_escape_string($db, $firstname);
    $security = mysqli_real_escape_string($db, $lastname);
    $security = mysqli_real_escape_string($db, $email);
    $security = mysqli_real_escape_string($db, $pass);

    $firstname = addslashes ($firstname);
    $lastname = addslashes ($lastname);
    $email = addslashes ($email);
    $pass= addslashes ($pass);
    //-- $passConf= addslashes ($passConf);


    //-- hashing password
    $userpass= sha1($pass);


    //-- CHECK IF MAIL ALREADY EXISTS ------------------------------------------
    //-- get all emails from db
    $mailQuery = "SELECT * FROM Users WHERE email = '{$email}'";
    //echo $mailQuery;
    $stmt = $db->prepare($mailQuery);
    $result=mysqli_query($db, $mailQuery);
    $email_nrRows = mysqli_num_rows($result);

    //echo $email_nrRows;

    //-- CHECK IF ALL FIELDS ARE FILLED OUT ------------------------------------
    if (!$firstname || !$lastname || !$email || !$pass || !$school) {
        echo("<p style='margin-top:150px;'>You must fill out all forms</p>");
    }else if($email_nrRows != 0){
        echo("<p style='margin-top:150px;'>The email is already taken</p>");
    }
    else{
        //-- check file format
        if (isset($_FILES['upload']) && !empty($_FILES['upload'])){
             $allowedextensions = array('jpg', 'jpeg', 'gif', 'png');
             $extension = strtolower(substr($_FILES['upload']['name'], strrpos($_FILES['upload']['name'], '.') + 1));
             $error = array ();
            //ERROR FILE FORMAT
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
                move_uploaded_file($_FILES['upload']['tmp_name'], "uploadedfiles/" . $image);

                $stmt = $db->prepare("INSERT INTO Users values ('', 'student', ?, ?, ?, ?, ?, ?, '')");
                $stmt->bind_param('ssssss', $userpass, $email, $image, $school, $firstname, $lastname);
                $stmt->execute();
                echo "<script>window.location.href='login.php'</script>";
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

    //-- CHECK IF ALL FIELDS ARE FILLED OUT ------------------------------------
    if (!$orgname || !$email || !$pass || !$school) {
        echo("<p style='margin-top:150px;'>You must fill out all forms</p>");
    }
    else if($email_nrRows != 0){
        echo("<p style='margin-top:150px;'>The email is already taken</p>");
    }
    else if($host_nrRows != 0){
        echo("<p style='margin-top:150px;'>There is already an account for this organisation</p>");
    }
    else{
      //-- add organisation to db
      $stmt = $db->prepare("INSERT INTO Users values ('', 'organisation', ?, ?, '', ?, '', '', ?)");
      $stmt->bind_param('ssss', $userpass, $email, $school, $orgname);
      $stmt->execute();
      echo "<script>window.location.href='login.php'</script>";
      exit();
    }
}



include("footer.php");
?>
