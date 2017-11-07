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
