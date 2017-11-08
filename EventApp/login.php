<?php
//-- SESSION HIJACKING PART ONE-------------------------------------------------
ini_set('session.cookie_httponly', true);

ob_start();
// session_start();
// if (!isset($_SESSION['userID'])) {
//     header("Location:../index.php");
// }

//-- INCLUDE
include("header.php");

//-- DATABASE CONNECTION
@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
if ($db->connect_error) {
   echo "could not connect: " . $db->connect_error;
   header("Location: index.php");
   exit();
}

//-- SESSION HIJACKING PART TWO-------------------------------------------------

//-- if there is no session with the userip saved, create a session that stores the user's IP address
if(isset($_SESSION['userip']) === false){
  $_SESSION['userip'] = $_SERVER['REMOTE_ADDR'];
}

//-- if the userip in the session is not the same as the ip address used, destroy session
if($_SESSION['userip'] !== $_SERVER['REMOTE_ADDR']){
  session_unset();
  session_destroy();
}



//-- IF SESSION ALREADY EXISTS,CHECK USER TYPE AND REDIRECT TO ACCORDING PAGE---

if (isset($_SESSION['userID'])) {

    if ($_SESSION['type'] == 'organisation') {
      header("location:organisation.php");
     exit();
    }
    else if($_SESSION['type'] == 'student'){
      header("location:user.php");
      exit();
    }
    else{
      header("location:admin/admin.php");
      exit();
    }
}


//-- IF NO SESSION: CHECK USER TYPE AND REDIRECT TO ACCORDING PAGE--------------

if(isset($_POST['email'], $_POST['password'])){

    //-- SQL INJECTION ---------------------------------------------------------
    $usermail = mysqli_real_escape_string($db, $_POST['email']);

   	$usermail =  stripslashes($_POST['email']);
    $password =  stripslashes($_POST['password']);

    $stmt = $db->prepare("SELECT userID, type, firstname, email, userpass, organisation, school FROM Users WHERE email = ?");
    $stmt->bind_param('s', $usermail);
	  $stmt->execute();
    $stmt->bind_result($userID, $type, $firstname, $usermail, $userpass, $organisation, $school);

    while ($stmt->fetch()) {
        //-- check if the hashed password is the same as the password in the database
        if (sha1($password) == $userpass){
            //-- create sessions
            $_SESSION['username'] = $firstname;
            $_SESSION['userID'] = $userID;
            $_SESSION['usermail'] = $usermail;
            $_SESSION['organisation'] = $organisation;
            $_SESSION['type'] = $type;
            $_SESSION['school'] = $school;

            //-- check user type to redirect to according page
            if($type == 'organisation'){
             header("location:organisation.php");
             exit();
            }
            else if($type == 'student'){
             header("location:user.php");
             exit();
            }
            else if($type == 'admin'){
             header("location:admin/admin.php");
             exit();
            }
    //-- if password is not correct, error message
		} else {echo'<p>Wrong Password</p>';}
  }
}
?>

<!-- LOGIN FORM --------------------------------------------------------------->
<div class='wrapper'>
    <div id="back">
        <a href="index.php"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
    </div>
    <div class="logoLogin"></div>
    <div class="container">
        <form action='' method="POST" class='loginForm'>
                <input type='text' name='email' placeholder='Email' class='inputField'>
                <br>
                <input type='password' name='password' placeholder='Password' class='inputField'>
                <br>
                <input type='submit' value='Log in' class='submitBtn'>
        </form>
    </div>
</div>



<?php
include("footer.php");
?>
