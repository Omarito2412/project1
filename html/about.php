<?php
session_start();
include('../php/config.php');
include('../php/helpers.php');
renderTop();
echo $about;
renderBottom();
?>
