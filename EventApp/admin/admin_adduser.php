<?php
//-- PAGE SETUP ----------------------------------------------------------------


//-- INCLUDE
include("admin_header.php");
include("../menu.php");
include("../userinfo.php");


//--ADMIN SECURITY
$type = $_SESSION['type'];

if ( $type == 'student'){
  header("location:../user.php");
  exit();
}else if ($type == 'organisation'){
  header("location:../organisation.php");
}



//-- DATABASE CONNECTION
@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
if ($db->connect_error) {
   echo "could not connect: " . $db->connect_error;
   header("Location: index.php");
   exit();
}?>



<!-- FORM START --------------------------------------------------------------->
<div class='wrapper_admin'>

    <div class="logoLogin">
    <div class="container">

<!-- SELECT USER TYPE  -------------------------------------------------------->
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


 <!--  STUDENT FORM  ---------------------------------------------------------->
        <form action='admin_adduser.php' method="POST" class='newUserForm' enctype="multipart/form-data">
            <div id='newStudentForm'>
                  <input type='text' name='nuFirstname' placeholder='First name' class='inputField'>
                  <br>
                  <input type='text' name='nuLastname' placeholder='Last name' class='inputField'>
                  <br>
                  <input type='email' name='nuEmail' placeholder='Email' class='inputField'>
                  <br>
                  <input type='password' name='nuPass' placeholder='Password' class='inputField'>
                  <br>
                  <br>
                  <!-- SELECT SCHOOL ------------------------------------------>
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
              <!-- FILE UPLOAD -------------------------------------------->
                  <br>
                  <input type='file' name='upload' >
                  <br>
                  <br>
                  <input type='submit' value='Submit!' class='submitBtn'>
           </div>
        </form>

 <!--  ORGANISATION FORM ------------------------------------------------------>
        <form action='admin_adduser.php' method="POST" class='newUserForm' enctype="multipart/form-data">
            <div id='newOrgForm'>
              <input type='text' name='orgname' placeholder='Organization' class='inputField'>
              <br>
              <input type='email' name='nuEmail' placeholder='Email' class='inputField'>
              <br>
              <input type='password' name='nuPass' placeholder='Password' class='inputField'>
              <!-- SELECT SCHOOL ---------------------------------------------->
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
            <br>
            <!-- FILE UPLOAD -------------------------------------------------->
            <input type='file' name='upload' >
            <br>
            <br>
            <input type='submit' value='Submit!' class='submitBtn'>
          </div>
        </form>
    </div>
</div>
<script src="../js/newuser.js"></script>

<?php
//-- CREATE STUDENT ------------------------------------------------------------
        if (isset($_POST['nuFirstname'])){

            $firstname = trim ($_POST['nuFirstname']);
            $lastname = trim ($_POST['nuLastname']);
            $email = trim ($_POST['nuEmail']);
            $pass= trim ($_POST['nuPass']);


            //-- XSS -----------------------------------------------------------------
            $firstname = htmlentities($firstname);
            $lastname = htmlentities($lastname);
            $email = htmlentities($email);
            $pass = htmlentities($pass);

            $security = mysqli_real_escape_string($db, $firstname);
            $security = mysqli_real_escape_string($db, $lastname);
            $security = mysqli_real_escape_string($db, $email);
            $security = mysqli_real_escape_string($db, $pass);

            $firstname = addslashes ($firstname);
            $lastname = addslashes ($lastname);
            $email = addslashes ($email);
            $pass= addslashes ($pass);

            $userpass= sha1($pass);



            //-- CHECK IF MAIL ALREADY EXISTS ------------------------------------------
            //-- get all emails from db
            $mailQuery = "SELECT * FROM Users WHERE email = '{$email}'";
            //echo $mailQuery;
            $stmt = $db->prepare($mailQuery);
            $result=mysqli_query($db, $mailQuery);
            $email_nrRows = mysqli_num_rows($result);

            //check if all fields are filled out
            if (!$firstname || !$lastname || !$email || !$pass || !$school) {
                echo("<p style='margin-top:0px;'>You must fill out all forms</p>");
            }else if($email_nrRows != 0){
                echo("<p style='margin-top:0px;'>The email is already taken</p>");
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
                      echo("<p style='margin-top:0px; color:'black';>This is not an image, upload is allowed only for images.</p>");
                  }
                  //ERROR SIZE
                  if($_FILES['upload']['size'] > 1000000){
                    $error[]='The file exceeded the upload limit';
                    echo("<p style='margin-top:0px; color:'black';>The file exceeded the upload limit</p>");
                  }
                  //NO ERROR
                  if(empty($error)){
                      $image = $_FILES['upload']['name'];
                      move_uploaded_file($_FILES['upload']['tmp_name'], "../uploadedfiles/" . $image);

                      //Takes the inputed values, which are the ones with a ? and inserts them to the Users table in the db
                       $stmt = $db->prepare("INSERT INTO Users values ('', 'student', ?, ?, ?, ?, ?, ?, '')");

                      //binds the parameter
                       $stmt->bind_param('ssssss', $userpass, $email, $image, $school, $firstname, $lastname);
                       $stmt->execute();
                      //redirects the user to the login page after data is saved in db
					  echo "<script>window.location.href='admin_deleteuser.php'</script>";

                       exit;
                  }
            }
        }
}





//-- CREATE ORGANISATION -------------------------------------------------------

    if (isset($_POST['orgname'])){


        //gets the input and gets rid of spaces (trim)
        $orgname = trim ($_POST['orgname']);
        $email = trim ($_POST['nuEmail']);
        $pass= trim ($_POST['nuPass']);
        //$passConf= trim ($_POST['nuPassConf']);
        $school= trim ($_POST['school']);

        //-- XSS ---------------------------------------------------------------
        $orgname = htmlentities($orgname);
        $email = htmlentities($email);
        $pass = htmlentities($pass);

        $security = mysqli_real_escape_string($db, $orgname);
        $security = mysqli_real_escape_string($db, $email);
        $security = mysqli_real_escape_string($db, $pass);

        $orgname = addslashes ($orgname);
        $email = addslashes ($email);
        $pass= addslashes ($pass);
        $school= addslashes ($school);

        $userpass= sha1($pass);



        //-- CHECK IF MAIL ALREADY EXISTS --------------------------------------
        //-- get all emails from db
        $mailQuery = "SELECT * FROM Users WHERE email = '{$email}'";
        //echo $mailQuery;
        $stmt = $db->prepare($mailQuery);
        $result=mysqli_query($db, $mailQuery);
        $email_nrRows = mysqli_num_rows($result);


        //check if all fields are filled out
        if (!$orgname || !$email || !$pass || !$school) {
            echo("<p style='margin-top:0px;'>You must fill out all forms</p>");
        }else if($email_nrRows != 0){
            echo("<p style='margin-top:0px;'>The email is already taken</p>");
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
                  echo("<p style='margin-top:0px; color:'black';>This is not an image, upload is allowed only for images.</p>");
              }
              //ERROR SIZE
              if($_FILES['upload']['size'] > 1000000){
                $error[]='The file exceeded the upload limit';
                echo("<p style='margin-top:0px; color:'black';>The file exceeded the upload limit</p>");
              }
              //NO ERROR
              if(empty($error)){
                $image = $_FILES['upload']['name'];
                move_uploaded_file($_FILES['upload']['tmp_name'], "../uploadedfiles/" . $image);

                //Takes the inputed values, which are the ones with a ? and inserts them to the Users table in the db
                $stmt = $db->prepare("INSERT INTO Users values ('', 'organisation', ?, ?, ?, ?, '', '', ?)");
                $stmt->bind_param('sssss', $userpass, $email, $image, $school, $orgname);
                $stmt->execute();
                //redirects the user to the login page after data is saved in db
                echo "<script>window.location.href='admin_deleteuser.php'</script>";
            }
        }
    }
}




include("../footer.php");
?>
