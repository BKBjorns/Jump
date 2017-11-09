<?php
    include("config.php");
    include("header.php");

    session_start();
    //-- CHECK IF USER IS LOGGED IN

        if (!isset($_SESSION['userID'])) {
            header("Location:index.php");
        }
    $type = $_SESSION['type'];
    //echo $type;

//-- CHECK USER TYPE -----------------------------------------------------------
//--STUDENT MENU
if ( $type == 'student'){?>
    <header>
            <div id="logo" class="logo"></div>
            <div id="hamburger">
                <div class="line one"></div>
                <div class="line two"></div>
                <div class="line three"></div>
            </div>
            <ul id="dropDown">
                <li><a class="dropDownLink <?php echo($current_page == 'events.php') ? 'active' : NULL?>" href="events.php">Event Overview</a></li>
                <li><a class="dropDownLink <?php echo($current_page == 'user.php'|| $current_page == "" ) ? 'active' : NULL?>" href="user.php">My Events</a></li>
                <li><a class="dropDownLink <?php echo($current_page == 'search.php'|| $current_page == "" ) ? 'active' : NULL?>" href="search.php">Search</a></li>
                <li><a class="dropDownLink" href="logout.php">Log Out</a></li>
            </ul>
    </header>
 <?php
 //-- ORGANISATION MENU
} else if($type == 'organisation'){?>
    <header>
        <div id="logo" class="logo"></div>
        <div id="hamburger">
            <div class="line one"></div>
            <div class="line two"></div>
            <div class="line three"></div>
        </div>
        <ul id="dropDown">
            <li><a class="dropDownLink <?php echo($current_page == 'organisation_events.php') ? 'active' : NULL?>" href="organisation_events.php">Event Overview</a></li>
            <li><a class="dropDownLink <?php echo($current_page == 'organisation.php'|| $current_page == "" ) ? 'active' : NULL?>" href="organisation.php">My Events</a></li>
            <li><a class="dropDownLink <?php echo($current_page == 'search_org.php'|| $current_page == "" ) ? 'active' : NULL?>" href="search_org.php">Search</a></li>
            <li><a class="dropDownLink" href="logout.php">Log Out</a></li>
        </ul>
</header>

<?php }
 else if($type == 'admin'){?>
  <header>
          <div id="logo" class="logo"></div>
          <div id="hamburger">
              <div class="line one"></div>
              <div class="line two"></div>
              <div class="line three"></div>
          </div>
          <ul id="dropDown">
             <li><a class="dropDownLink <?php echo($current_page == 'admin.php'|| $current_page == "" ) ? 'active' : NULL?>" href="admin.php">Admin Panel</a></li>
              <li><a class="dropDownLink <?php echo($current_page == 'admin_events.php') ? 'active' : NULL?>" href="admin_events.php">Events</a></li>
              <li><a class="dropDownLink <?php echo($current_page == 'admin_deleteuser.php') ? 'active' : NULL?>" href="admin_deleteuser.php">Users</a></li>
              <li><a class="dropDownLink <?php echo($current_page == 'logout.php') ? 'active' : NULL?>" href="../logout.php">Log Out</a></li>
          </ul>
  </header>


<?php } ?>
