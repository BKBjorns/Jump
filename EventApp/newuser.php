<?php
include("header.php");


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


<!-- Select user type , Student or Organization window  -->

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


 <!--     Form for new student      -->
        <form action='newuser.php' method="POST" class='newUserForm' enctype="multipart/form-data">
            <div id='newStudentForm'>


                    <input type='text' name='nuFirstname' value='First name' class='inputField'>
                    <br>
                    <input type='text' name='nuLastname' value='Last name' class='inputField'>

                    <br>
                    <input type='email' name='nuEmail' value='Email' class='inputField'>

                    <br>
                    <input type='password' name='nuPass' value='Password' class='inputField'>
                    <br>
                    <!--<input type='password' name='nuPassConf' value='Password' class='inputField'>-->
                    <br>
                    <input type='file' name='upload' >
                    <br>
                    <br>
                    <!--                <input type='text' name='nuSchool' value='School' class='inputField'>-->
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
                    <input type='submit' value='Submit!' class='submitBtn'>


             </div>
        </form>

 <!--    Form for new organization      -->
        <form action='newuser.php' method="POST" class='newUserForm'>
            <div id='newOrgForm'>


                    <input type='text' name='orgname' value='Organization' class='inputField'>


                    <br>
                    <input type='email' name='nuEmail' value='Email' class='inputField'>

                    <br>
                    <input type='password' name='nuPass' value='Password' class='inputField'>
                    <br>
                    <!--<input type='password' name='nuPassConf' value='Password' class='inputField'>-->

                    <br>
                    <!--                <input type='text' name='nuSchool' value='School' class='inputField'>-->
                    <select id='selectSchool' name="school">
                        <option value="School" disabled selected>Select School</option>
                        <option value="1">School of Engineering</option>
                        <option value="2">Jönköping International Business School</option>
                        <option value="3">School of Education and Communication</option>
                        <option value="4">School of Health and Welfare</option>
                        <option value="5">Other</option>
                    </select>

                    <br>
                    <input type='submit' value='Submit!' class='submitBtn'>



            </div>
        </form>
    </div>
</div>

<script src="js/newuser.js"></script>

<?php

 //creates a new connection to the database - connection specified in config.php
 @ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

//if it cannot connect to the database it will send the user back to the index page
if ($db->connect_error) {
    //returns why db cannot connect
    echo "could not connect: " . $db->connect_error;
    //takes the user back to the index page
    header("Location: index.php");
    exit();
}


        // Add new student
        //checks if all input fields are filled out
        if (isset($_POST['nuFirstname'])){

            //gets the input and gets rid of spaces (trim)
            $firstname = trim ($_POST['nuFirstname']);
            $lastname = trim ($_POST['nuLastname']);
            $email = trim ($_POST['nuEmail']);
            $pass= trim ($_POST['nuPass']);
            //$passConf= trim ($_POST['nuPassConf']);
            $school= trim ($_POST['school']);


            //returns input as string with backslashes in front of predefined characters
            $firstname = addslashes ($firstname);
            $lastname = addslashes ($lastname);
            $email = addslashes ($email);
            //$school= addslashes ($school);
            $pass= addslashes ($pass);
            //$passConf= addslashes ($passConf);
            $school= addslashes ($school);

            //takes the password and hashes it
            $userpass= sha1($pass);



            //-- CHECK IF MAIL ALREADY EXISTS ------------------------------------------
            //-- get all emails from db
            $mailQuery = "SELECT * FROM Users WHERE email = '{$email}'";
            //echo $mailQuery;
            $stmt = $db->prepare($mailQuery);
            $result=mysqli_query($db, $mailQuery);
            $email_nrRows = mysqli_num_rows($result);

            //echo $email_nrRows;

            //check if all fields are filled out
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

                      //Takes the inputed values, which are the ones with a ? and inserts them to the Users table in the db
                       $stmt = $db->prepare("INSERT INTO Users values ('', 'student', ?, ?, ?, ?, ?, ?, '')");

                      //binds the parameter
                       $stmt->bind_param('ssssss', $userpass, $email, $image, $school, $firstname, $lastname);
                       $stmt->execute();
                      //redirects the user to the login page after data is saved in db
                       echo "<script>window.location.href='login.php'</script>";
                       exit;
                  }


            }

        }


}





//if organisation radio button is clicked, runs this statement

        //checks if all input fields are filled out
        if (isset($_POST['orgname'])){

//            //gets the input and gets rid of spaces (trim)
//            $orgname = trim ($_POST['orgname']);
//            $email = trim ($_POST['nuEmail']);
//            //$school= trim ($_POST['']);
//            $pass= trim ($_POST['nuPass']);
//            //$passConf= trim ($_POST['nuPassConf']);
//            $school= trim ($_POST['oschool']);
//
//
//            //returns input as string with backslashes in front of predefined characters
//            $orgname = addslashes ($orgname);
//            $email = addslashes ($email);
//            $pass= addslashes ($pass);
//            //$passConf= addslashes ($passConf);
//            $school= addslashes ($school);
//
//            //takes the password and hashes it
//            $userpass= sha1($pass);
//
//
//            //Takes the inputed values, which are the ones with a ? and inserts them to the Users table in the db
//             $stmt = $db->prepare("INSERT INTO Users values ('', 'organisation', ?, ?, '', ?, '', '', ?)");
//
//            //binds the parameter
//             $stmt->bind_param('ssss', $userpass, $email, $school, $organisation);
//             $stmt->execute();
//            //redirects the user to the login page after data is saved in db
//             echo "<script>window.location.href='login.php'</script>";
//             exit;






            //gets the input and gets rid of spaces (trim)
            $orgname = trim ($_POST['orgname']);
            $email = trim ($_POST['nuEmail']);
            $pass= trim ($_POST['nuPass']);
            //$passConf= trim ($_POST['nuPassConf']);
            $school= trim ($_POST['school']);


            //returns input as string with backslashes in front of predefined characters
            $orgname = addslashes ($orgname);
            $email = addslashes ($email);
            $pass= addslashes ($pass);
            //$passConf= addslashes ($passConf);
            $school= addslashes ($school);

            //takes the password and hashes it
            $userpass= sha1($pass);


            //check if all fields are filled out
            if (!$orgname || !$email || !$pass || !$school) {
                echo("<p style='margin-top:150px;'>You must fill out all forms</p>");
            }
            else{

            //Takes the inputed values, which are the ones with a ? and inserts them to the Users table in the db
             $stmt = $db->prepare("INSERT INTO Users values ('', 'organisation', ?, ?, '', ?, '', '', ?)");

            //binds the parameter
             $stmt->bind_param('ssss', $userpass, $email, $school, $organisation);
             $stmt->execute();
            //redirects the user to the login page after data is saved in db
             //echo "<script>window.location.href='login.php'</script>";
             exit;
            }

        }



include("footer.php");
?>
