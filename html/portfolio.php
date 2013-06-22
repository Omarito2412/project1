<?php
session_start();
include '../php/helpers.php';
renderTop();
if(isset($_SESSION['auth'])) //Is user logged in?
{
    //Show him his profile
}
else if(!isset($_POST["username"])) //He isn't logged in nor trying to log in, Show him login form.
{
    echo'<form method="POST" action="portfolio.php"><ul>
    <li>USERNAME<input type="text" name="username"/></li>
    <li>PASSWORD<input type="password" name="password"/></li>
    <li> <input type="submit" value="Login"/> </li>
    </ul> </form>
    <p>No Account, <a href="register.php"> Register? </a></p>';
}
else // He is trying to login.
{ 
    //Connect to database and check for provided credentials.
    include("../php/config.php");
    $dbh = new PDO("mysql:host=$hostname;dbname=$database",$dbuser,$dbpass);
    $user = $_POST['username']; $pass = $_POST['password']; 
    $sql = 'SELECT * FROM users where 
    username = '.'"'.$user.'"'.' AND
    password = '.'"'.$pass.'"';
    $query = $dbh->query($sql);
    foreach($query as $row)
    {
   
       if(isset($row['username'])) // The database query returned his account, Thus it exists.
       {
            $_SESSION['auth'] = 1; $_SESSION['username'] = $row['username']; $_SESSION['balance'] = $row['balance']; //Remember he is logged in using Session
            header("Location: portfolio.php");
       }
    }
    if(!isset($_SESSION['auth'])) // Couldn't find a better condition, He wasn't authenticated in earlier move, Thus invalid credentials
    echo '<form method="POST" action="portfolio.php"><ul>
    <li>USERNAME <input type="text" name="username" value="'.$_POST['username'].'"/></li>
    <li>PASSWORD <input type="password" name="password"/></li>
    <li> <input type="submit" value="Login"/> </li><h4 color="red">Invalid Username/Password, Please enter correct information</h4>  </ul> </form>';
 
}
$dbh = NULL;
renderBottom();
?>
