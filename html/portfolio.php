<?php
session_start();
include '../php/helpers.php';
renderTop();
if(isset($_SESSION['auth'])) //Is user logged in?
{
    //Show him his profile
    include("../php/config.php"); 
    echo "<p><h2> Welcome, ".$_SESSION['username']."</h2>";
    echo "<h3> You currently have $".$_SESSION['balance']." in your account </h3>";
    $sql = "SELECT * FROM `stocks` where id = ".$_SESSION['id'];
    $sql2 = "SELECT COUNT(*) from `stocks` where id = ".$_SESSION['id'];
    $dbh = new PDO("mysql:host=$hostname;dbname=$database",$dbuser,$dbpass);
    foreach($dbh->query($sql2) as $row)
    $count = $row;
    if($count[0] != 0)
    { 
        echo "<table border=1><tr><td><strong>Stock Symbol</strong></td><td><strong>Quantity</strong></td><td><strong>Current Value</strong></td> </tr>";
        foreach($dbh->query($sql) as $row)
        {
            $csv = getQuote($row['symbol']);
            $value = $csv[1] * $row['quantity'];
            echo"<tr><td>".$row['symbol']."</td><td>".$row['quantity']."</td><td>".$value." </td></tr>";
        }
        echo '</table>';
    }
    else if($count[0] == 0)
    {
        echo '<p>You have no stocks, Proceed to <a href="trade.php"> Trade </a> to start buying stocks!</p>';
    }
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
    $user = $_POST['username']; $pass = 'md5("'.$_POST['password'].'")'; 
    $sql = 'SELECT * FROM users where 
    username = '.'"'.$user.'"'.' AND
    password = '.$pass;
    $query = $dbh->query($sql);
    foreach($query as $row)
    {
   
       if(isset($row['username'])) // The database query returned his account, Thus it exists.
       {
            $_SESSION['auth'] = 1; $_SESSION['username'] = $row['username']; $_SESSION['balance'] = $row['balance']; $_SESSION['id'] = $row['id']; //Remember he is logged in using Session
            header("Location: portfolio.php");
       }
    }
    if(!isset($_SESSION['auth'])) // Couldn't find a better condition, He wasn't authenticated in earlier move, Thus invalid credentials
    echo '<form method="POST" action="portfolio.php"><ul>
    <li>USERNAME <input type="text" name="username" value="'.$_POST['username'].'"/></li>
    <li>PASSWORD <input type="password" name="password"/></li>
    <li> <input type="submit" value="Login"/> </li><div id="notification"><h4>Invalid Username/Password, Please enter correct information</h4></div></ul> </form>';
 
}
$dbh = NULL;
renderBottom();
?>
