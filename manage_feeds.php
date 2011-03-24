<?php
/* 
 * Add and delete feeds
 */

include('user_auth.php');
include('config.php');


// Add new feed
if (isset($_POST['submit'])) {
    $new_feed = htmlentities($_POST['url']);
    $query = "INSERT INTO feeds (feedsurl) VALUES ('" . $new_feed . "')";
    mysql_query($query);
}

// remove feed
if (isset($_POST['remove_feed'])) {
    $feedid = htmlentities($_POST['feedid']);
    echo 'id is: ' . $feedid;
    $query = 'DELETE FROM feeds WHERE idfeeds="' . $feedid . '"';
    mysql_query($query);
}

// remove the user
if (isset($_POST['remove_user'])) {
    $userid = htmlentities($_POST['userid']);
    echo 'id is: ' . $userid;
    $query = 'DELETE FROM logins WHERE id="' . $userid . '"';
    mysql_query($query);
}

// add a user
if (isset($_POST['add_user'])) {
    $username = htmlentities($_POST['username']);
    $password = htmlentities($_POST['password']);
    $query = "INSERT INTO logins (username, pwd) VALUES ('" . $username . "',MD5('" . $password . "') )";
    mysql_query($query);
}


// get current feeds

$query = "SELECT * FROM feeds";

$result = mysql_query($query);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <!-- make the rss icon avalaible -->
    <link rel="alternate" type="application/rss+xml" href="<?php $siteUrl ?>feed.php" title="Your title">

    <link rel="stylesheet" href="style.css" type="text/css" media="screen" charset="utf-8"/>

    <style type="text/css">
        h4.title {
        /* We're going to add some space next to the titles so we can fit the 16x16 favicon image. */
            background-color: transparent;
            background-repeat: no-repeat;
            background-position: 0 1px;
            padding-left: 20px;
        }
    </style>
</head>
<body>

<div id="background">
    <div id="wrapper">
        <div id="site">
<?php
echo '<h2>Current feeds</h2>';

    echo '<table>';
    while ($row = mysql_fetch_assoc($result)) {
        echo '<tr><td>';
        echo 'ID: ' . $row["idfeeds"] . ' Feed url: ' . $row["feedsurl"];
        echo '</td><td>';
        echo '<form method="post" action="manage_feeds.php" name="remove_feed">';
        echo '<input type="hidden" id="feedid" name="feedid" value="' . $row["idfeeds"] . '"></input>';
        echo '<input type="submit" id="remove_feed" name="remove_feed" value="Remove feed" />';
        echo '</form>';
        echo '</td></tr>';

    } ?>
    </table>



    <form method="post" action="manage_feeds.php" name="new_feed">
    <input name="url">
   <input type="submit" id="submit" name="submit" value="Add feed"/>
  </form>


<a href="<?php echo $siteUrl ?>">Back to the planet</a>


        </div>
        <div id="feeds">
            <h2>Manage users</h2>

<?php
         echo '<form method="post" action="manage_feeds.php" name="users">';
            $query = "SELECT * FROM logins";
            $result = mysql_query($query);

            while ($row = mysql_fetch_assoc($result)) {
                echo $row['username'];
                echo '<input type="hidden" id="userid" name="userid" value="' . $row["id"] . '"></input>';
                echo '<input type="submit" id="remove_user" name="remove_user" value="Remove user"/>';
                echo '</p>';
            }
            echo '</form>';
            ?>
            <h3>Add a new user</h3>

            <form method="post" action="manage_feeds.php" name="addusers">
                <label for="username">Username</label>
                <input name="username" label="user name" id="username">
                </p>
                <label for="password">Password</label>
                <input name="password" label="password" id="password">
                </p>
                <input type="submit" id="add_user" name="add_user" value="Add user"/>
            </form>


        </div>
    </div>
</div>
</div>
<div id="footer">
    <?php include('footer.php'); ?>
</div>
</body>
</html>