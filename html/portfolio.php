<?php
session_start();
include '../php/helpers.php';
renderTop();
if(isset($_SESSION['auth']))
{
    //Show him his profile
}
else if(!isset($_POST["username"]))
{
    echo'<form method="POST" action="portfolio.php"><ul>
    <li>USERNAME <input type="text" name="username"/></li>
    <li>PASSWORD <input type="password" name="password"/></li>
    <li> <input type="submit" value="Login"/> </li>
    </ul> </form>';
}
else
{
    try{
    include("../php/config.php");
    $dbh = new PDO("mysql:host=$hostname;dbname=$database",$dbuser,$dbpass);
    $user = $_POST['username']; $pass = $_POST['password']; 
    $sql = 'SELECT * FROM users where 
    username = '.'"'.$user.'"'.' AND
    password = '.'"'.$pass.'"';
    $query = $dbh->query($sql);
    foreach($query as $row)
    {
       //print_r($row);
       if(isset($row['username']))
       {
            $_SESSION['auth'] = 1; $_SESSION['username'] = $row['username'];
            echo 'welcome';
       }
    }
    if(!isset($_SESSION['auth']))
    echo "Invalid Username/Password";
    }
    catch(PDOException $e)
        {
            echo $e->getMessage();
        }

}
renderBottom();
?>
