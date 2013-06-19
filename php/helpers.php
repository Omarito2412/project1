<?php
function renderTop() {
include("../php/config.php");
echo '<html>
<head>
<title>';
echo $title;
echo '</title>
<link href="../css/stylesheet.css" type="text/css" media="screen" rel="stylesheet"/>
</head>
<body>

<div id="container">

<div id="header">';

if(isset($_SESSION['auth']))
{
    echo '<div id="welcome"><p>Welcome '.$_SESSION['username'].'</p> </div>';
}
else
{
    echo '<div id="welcome"><p>Hello visitor, You are not logged in, Please <a href="portfolio.php"> Login </a> or  <a href="register.php">Register</a> for a free account </p></div>';
}
echo '<div id="logo">

<img src="../images/logo.png" width="120" height="208"/><img class="logo2" src="../images/logo2.png" height="115" /></div>
';
echo '</div><div id="topnav">

<ul>

<li> <a href="index.php"> HOME </a> </li>

<li><a href="portfolio.php"> PROFILE </a></li>

<li><a href="trade.php"> TRADE </a></li>

<li><a href="about.php"> ABOUT </a></li>

<li><a href="contact.php"> CONTACT </a></li>

</ul>

</div><div id="content">';
}
function renderBottom() {
include("../php/config.php");
echo '</div>

<div id="footer">

<p> Copyrights '; 
echo $name;
echo '</p>
</div>

</div>

</body>

</html>';
}

?>
