<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dan
 * Date: 23/03/11
 * Time: 20:27
 * To change this template use File | Settings | File Templates.
 */

include('config.php');

function authenticate_user() {
    header('WWW-Authenticate: Basic realm="SimplePlanet"');
    header("HTTP/1.0 401 Unauthorized");
    exit;
}

if (! isset($_SERVER['PHP_AUTH_USER'])) {
    authenticate_user();
} else {
    mysql_pconnect($dbserver,$dbusername,$dbpassword)
            or die("Can't connect to DB server");

    mysql_select_db($db)
            or die("Can't select database");

    $query = "SELECT username, pwd FROM logins
              WHERE username='$_SERVER[PHP_AUTH_USER]' AND
              pwd=MD5('$_SERVER[PHP_AUTH_PW]')";

      $result = mysql_query($query);
    if (mysql_num_rows($result) == 0) {
    authenticate_user();
    } else {
    echo "Welcome " . $_SERVER["PHP_AUTH_USER"];
    }

}
?>


