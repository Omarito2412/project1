<?php
session_start();
include ('../php/helpers.php');
renderTop();
if(isset($_SESSION['auth'])) // Logged in already!
{
    echo '<div id="notification"> <p> You are already logged in.</p></div>';
}
if(!isset($_POST['username']) && !isset($_SESSION['auth'])) // Didn't post the form and not logged in
{
    echo '<form method="POST" action="register.php"><ul><li>USERNAME <input type="text" name="username"></li><li>PASSWORD <input type="password" name="password"></li><li><input type="submit" value="Register"></li></ul></form>';
}
if(isset($_POST['username'])) // Did post the form
{
$_POST['username'] = htmlspecialchars($_POST['username']);
$_POST['password'] = htmlspecialchars($_POST['password']);
        // start a database connection
        include('../php/config.php');
        $dbh = new PDO("mysql:host=$hostname;dbname=$database;", $dbuser,$dbpass); //Connect to Database        
        $sql = 'SELECT * FROM users where username ="'.$_POST['username'].'"'; //SQL to check if username already exists
        foreach($dbh->query($sql) as $row) 
        {
            echo '<form method="POST" action="register.php"><ul><li>USERNAME <input type="text" name="username"></li><li>PASSWORD <input type="password" name="password"></li><li><input type="submit" value="Register"></li></ul></form><div id="notification"><h4> Sorry, Username already taken </h4></div>'; // Username exists, Notify user
        renderBottom();
        exit();
        }
        $nsql = 'INSERT INTO `users` (`username`,`password`) 
                 VALUES ("'.$_POST['username'].'", md5("'.$_POST['password'].'")'.')';
        $dbh->exec($nsql); //Username available, Add to Database
        $idsql = 'SELECT id FROM `users` where username = "'.$_POST['username'].'"';
        foreach($dbh->query($idsql) as $row)
        {$_SESSION['id'] = $row['id'];}
        $_SESSION['auth'] = 1; $_SESSION['username'] = $_POST['username']; $_SESSION['balance'] = 10000; //Log him in
        echo "<p>Thanks for signing up at $name! Now start <a href='trade.php'> trading </a>stocks!</p>";
        $dbh = NULL;
}
    
renderBottom();
?>
