<?php
/* 
 * Set up the database
 */

// Site settings
// The main url of the site
$siteUrl = 'http://danattwood.homelinux.com/rss/';
// the name of your planet
$planetTitle = 'Planet MKC';
// A description for the site
$description = 'Test combinded rss feed';

// Database settings
$dbserver = 'localhost';
$dbusername = 'root';
$dbpassword = 'nobard6817';
$db = 'planet_rss';

// Planet settings
// The total number of item to display on the first page
$total_items = 10;
// The maximum number of items to display on the more feeds page
$max_items = 50;
// 1 = on, 0= off
$debug = '0';


// Set up the database connections
$link = mysql_connect($dbserver, $dbusername, $dbpassword);

mysql_select_db($db);

if (!$link) {
    die('Could not connect: ' . mysql_error());
}
// echo 'Connected successfully';




?>
