<?php
    include("config.php");
    include("header.php");
?>

 
<header>
        <div id="logo" class="logo"></div>
        <div id="hamburger">
            <div class="line one"></div>
            <div class="line two"></div>
            <div class="line three"></div>
        </div>
        <ul id="dropDown">
            <li><a class="dropDownLink <?php echo($current_page == 'events.php') ? 'active' : NULL?>" href="events.php">Event Overview</a></li>
            <li><a class="dropDownLink <?php echo($current_page == 'organisation.php'|| $current_page == "" ) ? 'active' : NULL?>" href="organisation.php">My Events</a></li>
            <li><a class="dropDownLink <?php echo($current_page == 'search.php'|| $current_page == "" ) ? 'active' : NULL?>" href="search.php">Search</a></li>
            <li><a class="dropDownLink" href="logout.php">Log Out</a></li>
        </ul>
</header>

  
