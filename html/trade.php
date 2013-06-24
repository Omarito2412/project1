<?php
session_start();
include("../php/helpers.php");
include("../php/config.php");
renderTop();
if(isset($_GET['s']))
{
  $csv = getQuote($_GET['s']);
  echo "<p> 1 Share of $csv[0] = $csv[1] as of $csv[2] at $csv[3]</p>";
}
else
{
echo '<p><form action="trade.php" method="GET">
<p>To get an instant live quote, Please input the desired stock symbol.</p></br>
<input type="text" name="s" placeholder="Symbol"/>
<input type="submit" value="Get Quote"/>
</form></p>';
}
if(isset($_SESSION['auth']))
{
    echo '<p><h2> SELL </h2>';
    echo '<form method="POST" action="trade.php">
          <input type="text" name="sell" placeholder="Symbol">
          <input type="text" name="qtys" placeholder="Quantity">
          <input type="submit" value="Sell">
          </form>';
    echo '<h2> BUY </h2>';      
    echo '<form method="POST" action="trade.php">
          <input type="text" name="buy" placeholder="Symbol">
          <input type="text" name="qtyb" placeholder="Quantity">
          <input type="submit" value="Buy"></form></p>';
}
if(isset($_POST['sell']))
{
    //Start sell transaction
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;",$dbuser,$dbpass);
    $source = "http://download.finance.yahoo.com/d/quotes.csv?s={$_POST['sell']}&f=sl1d1t1c1ohgv&e=.csv";
    $handle = fopen($source, 'r');
    $csv = fgetcsv($handle,',');
    $price = $csv[1] * $_POST['qtys'];
    $sql1 = "SELECT * FROM `stocks` WHERE id =".$_SESSION['id'];
    if($qtys <= 0 || !is_int($qtys))
    {
        echo 'Invalid input, Quantity must be a positive integer';
        renderBottom;
        exit();
    }
    foreach($dbh->query($sql1) as $row)
    {
       if($row['symbol'] == $_POST['sell'] && $row['quantity'] >= $_POST['qtys'])
        {
            
            $quantity = $row['quantity'] - $_POST['qtys'];
            if($quantity == 0)
            {
                $sql2 = "DELETE from `stocks` WHERE id = ".$_SESSION['id'].' And symbol = "'.$_POST['sell'].'"';
            }
            else
            {
                $sql2 = "UPDATE `stocks` SET quantity=".$quantity.' WHERE symbol ="'.$row['symbol'].'" AND id='.$_SESSION['id'];
            }
            $_SESSION['balance'] = $_SESSION['balance']+ $price; 
            $sql3 = "UPDATE `users` SET balance=".$_SESSION['balance'].' WHERE id ='.$_SESSION['id'];
            $dbh->beginTransaction();
            $dbh->exec($sql2);
            $dbh->exec($sql3);
            $dbh->commit();
            echo "Congratulations, You've just sold ".$_POST['qtys']." shares of ".$_POST['sell'];
        }
        if($row['symbol'] == $_POST['sell'] && $row['quantity'] < $_POST['qtys'])
        echo '<div id="notification">You dont have enough stocks</div>';
    }
}
if(isset($_POST['buy']))
{
    //Start buy transaction   
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;",$dbuser,$dbpass);
    $source = "http://download.finance.yahoo.com/d/quotes.csv?s={$_POST['buy']}&f=sl1d1t1c1ohgv&e=.csv";
    $handle = fopen($source, 'r');
    $csv = fgetcsv($handle,',');
    $price = $csv[1] * $_POST['qtyb'];
    $sql1 = "SELECT * FROM `stocks` where id =".$_SESSION['id'];
    $sql3 = "INSERT INTO `stocks` (`id`, `symbol`,`quantity`) VALUES (".$_SESSION['id'].', "'.$_POST['buy'].'", '.$_POST['qtyb'].")";
    $balance2 = $_SESSION['balance'] - $price;
    $sql4 = "UPDATE `users` SET balance = ".$balance2." WHERE id = ".$_SESSION['id'];
    if($qtyb <= 0 || !is_int($qtyb))
    {
        echo 'Invalid input, Input must be a positive integer';
        renderBottom();
        exit();
    }
    if($_SESSION['balance'] >= $price)
    {
        foreach($dbh->query($sql1) as $row)
        {
            if($row['symbol'] == $_POST['buy'])
            {
                $quantity = $row['quantity'] + $_POST['qtyb'];
                $sql2 = "UPDATE `stocks` SET quantity = ".$quantity.' WHERE symbol = "'.$_POST['buy'].'" AND id = '.$_SESSION['id'];
                $dbh->beginTransaction();
                $dbh->exec($sql2);
                $dbh->exec($sql4);
                $dbh->commit();
                $_SESSION['balance'] = $_SESSION['balance'] - $price;
                renderBottom();
                exit();
            }
        }
        
            $dbh->beginTransaction();
            $dbh->exec($sql3);
            $dbh->exec($sql4);
            $dbh->commit();
            $_SESSION['balance'] = $_SESSION['balance'] - $price;
    }    
    else
    {
        echo "<div id='notification'> You don't have enough balance</div>";
    }
}
renderBottom();
?>
