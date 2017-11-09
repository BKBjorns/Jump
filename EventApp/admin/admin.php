<?php
//-- PAGE SETUP ----------------------------------------------------------------

//-- CHECK IF USER IS LOGGED IN


//-- INCLUDE
include("admin_header.php");
include("../menu.php");
include("../userinfo.php");

//-- DATABASE CONNECTION
@ $db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
if ($db->connect_error) {
   echo "could not connect: " . $db->connect_error;
   header("Location: index.php");
   exit();
}
?>
<!-- ACTION BUTTONS ----------------------------------------------------------->
<div class="btnContainer">
    <div class="btn">
        <a href="admin_add.php">Add Event</a>
    </div>
    <div class="btn">
        <a href="admin_events.php">Edit Event</a>
    </div>
    <div class="btn">
        <a href="admin_delete.php">Delete Event</a>
    </div>
    <div class="btn">
        <a href="admin_adduser.php">Add User</a>
    </div>
    <div class="btn">
        <a href="admin_deleteuser.php">Delete User</a>
    </div>
</div>


<?php
include("../footer.php");

?>
