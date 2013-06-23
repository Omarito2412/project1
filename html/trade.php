<?php
session_start();
include("../php/helpers.php");
renderTop();
if(isset($_GET['s']))
{
    $_GET['s'] = htmlspecialchars($_GET['s']);
    $source = "http://download.finance.yahoo.com/d/quotes.csv?s={$_GET['s']}&f=sl1d1t1c1ohgv&e=.csv";
    $handle = fopen($source,'r');
    if($handle === NULL)
    echo'<p>Unknown symbol! </p>';
    else
    {
    $csv = fgetcsv($handle,',');
    echo "<p> 1 Share of $csv[0] = $csv[1] as of $csv[2] at $csv[3]</p>";
    }
}
else
{
echo '<p><form action="trade.php" method="GET">
<p>To get an instant live quote, Please input the desired stock symbol.</p></br>
<input type="text" name="s"/>
<input type="submit" value="Get Quote"/>
</form></p>';
}
if(isset($_SESSION['auth']))
{
    echo '<h2> SELL </h2>';
    echo '<form method="POST" action="trade.php">
          <input type="text" name="sell">
          <input type="submit">
          </form>';
    echo '<h2> BUY </h2>';      
    echo '<form method="POST" action="trade.php">
          <input type="text name="buy">
          <input type="submit"></form>';
}
if(isset($_POST['sell']))
{
    //Start sell transaction
}
if(isset($_POST['buy']))
{
    //Start buy transaction
}
renderBottom();
?>
