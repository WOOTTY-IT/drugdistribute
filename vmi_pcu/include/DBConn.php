<?php
// In this page, we open the connection to the Database
// Our MySQL database (blueprintdb) for the Blueprint Application
// Function to connect to the DB
function connectToDB() {
    // These four parameters must be changed dependent on your MySQL settings
    $hostdb = '10.0.1.250'; // MySQl host
    $userdb = 'mhhos';  // MySQL username
    $passdb = 'mhhos10967';  // MySQL password
    $namedb = 'hos'; // MySQL database name

// Please uncomment the appropriate statement
    $link = mysql_connect ("10.0.1.250:3306", "mhhos", "mhhos10967");
    //$link = mysql_connect ($hostdb, $userdb, $passdb);
    //$link = mysql_connect ();

    if (!$link) {
        // we should have connected, but if any of the above parameters
        // are incorrect or we can't access the DB for some reason,
        // then we will stop execution here
        die('Could not connect: ' . mysql_error());
    }

    $db_selected = mysql_select_db($namedb);
    if (!$db_selected) {
        die ('Can\'t use database : ' . mysql_error());
    }
    return $link;
}
?>