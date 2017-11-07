<?php
session_start();
ob_start();
include("header.php");

//connecting the a new database, which is still Jump defined in config.php
@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

  //check if there is a connection to the database
if ($db->connect_error) {
  echo "could not connect: " . $db->connect_error;
  exit();
}

//start the session and check if session has been created before if so take user to user page if not create a new session with the user email when user logged in
//session_start();

//check if a session– called as the input given– is already set using an if-statement


// If the session is set it will redirect you to the home page that fits to your user type.
if (isset($_SESSION['userID'])) {
    //if the statement is true = if a session is set, take the user to the user page

    if ($_SESSION['type'] == 'organisation') {
      header("location:organisation.php");
     exit();
    }else if($_SESSION['type'] == 'student'){
      header("location:user.php");
      //echo var_dump($_SESSION);
      exit();
    }else{
      header("location:admin/admin.php");
      exit();
    }
}








// check if sth. is entered in the input field = it is not empty by using the if statement
if(isset($_POST) && !empty($_POST)){

    //stripslashes removes backslashes that mitgh have been added before using the function "addslashes"
   	$usermail =  stripslashes($_POST['email']);
    $password =  stripslashes($_POST['password']);





    //prepare the database and select the username and userpassword typed into the input fields
    $stmt = $db->prepare("SELECT userID, type, firstname, email, userpass, organisation, school FROM Users WHERE email = ?");


	$stmt->bind_param('s', $usermail);
	$stmt->execute();

    $stmt->bind_result($userID, $type, $firstname, $usermail, $userpass, $organisation, $school);


    while ($stmt->fetch()) {
        // check if the hashed password is the same as the password in the database
        if (sha1($password) == $userpass)
		{
            // if it is the same, create a new session with the username, which is the email of the user
            $_SESSION['username'] = $firstname;
            $_SESSION['userID'] = $userID;
            $_SESSION['usermail'] = $usermail;
            $_SESSION['organisation'] = $organisation;
            $_SESSION['type'] = $type;
            $_SESSION['school'] = $school;





			//debugging: check if the session works
            //echo $_SESSION['username'];
            if($type == 'organisation'){
             header("location:organisation.php");
            exit();
            } else if($type == 'student'){
             header("location:user.php");
            exit();
            } else if($type == 'admin'){
             header("location:admin/admin.php");
            exit();
            }

		} else {echo'<p>Wrong Password</p>';}
    }

}

?>
<div class='wrapper'>
    <div id="back">
        <a href="index.php"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
    </div>
    <div class="logoLogin"></div>
    <div class="container">
        <form action='' method="POST" class='loginForm'>

                <input type='email' name='email' placeholder='Email' class='inputField'>
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
