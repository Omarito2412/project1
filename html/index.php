<?php
session_start();
include("../php/helpers.php");
include("../php/config.php");
renderTop();
echo "<p> Welcome to $name, Your gate to the stock market, At $name you will get real time quotes about stocks you desire </p>";
renderBottom();
?>
