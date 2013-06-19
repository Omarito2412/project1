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
To get an instant live quote, Please input the desired stock symbol.</br>
<input type="text" name="s"/>
<input type="submit" value="Get Quote"/>
</form></p>';
}
renderBottom();
?>
