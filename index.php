<?php
declare(strict_types=1);
require 'code/suit.php';
require 'code/Card.php';
require 'code/Deck.php';
require 'code/Blackjack.php';
require 'code/Player.php';
session_start();

function whatIsHappening()
{
    echo '<h2>$_GET</h2>';
    var_dump($_GET);
    echo '<h2>$_POST</h2>';
    var_dump($_POST);
    echo '<h2>$_COOKIE</h2>';
    var_dump($_COOKIE);
    echo '<h2>$_SESSION</h2>';
    var_dump($_SESSION);
}

function checkWinner()
{
    if ($_SESSION['Blackjack']->getPlayer()->getScore() > $_SESSION['Blackjack']->getDealer()->getScore() &&
        $_SESSION['Blackjack']->getPlayer()->getScore() < $_SESSION['Blackjack']->getPlayer()::WINNING_VALUE)
    {
        $_SESSION['Blackjack']->getDealer()->surrender();
    }
    if($_SESSION['Blackjack']->getDealer()->getScore() > $_SESSION['Blackjack']->getPlayer()->getScore() &&
        $_SESSION['Blackjack']->getDealer()->getScore() < $_SESSION['Blackjack']->getDealer()::WINNING_VALUE)
    {
        $_SESSION['Blackjack']->getPlayer()->surrender();
    }

    if ($_SESSION['Blackjack']->getDealer()->getScore() === $_SESSION['Blackjack']->getDealer()::WINNING_VALUE) {

        echo "You lost &#128538";//unicode for emoticon
        unset($_SESSION['Blackjack']);

    } elseif ($_SESSION['Blackjack']->getPlayer()->getScore() === $_SESSION['Blackjack']->getPlayer()::WINNING_VALUE) {

        echo "You won &#129322";//unicode for emoticon

    } elseif ($_SESSION['Blackjack']->getDealer()->getScore() === $_SESSION['Blackjack']->getPlayer()->getScore()) {

        echo "It's a Push &#129313";//unicode for emoticon
        unset($_SESSION['Blackjack']);

    } elseif ($_SESSION['Blackjack']->getDealer()->hasLost()) {

        echo "You won &#129322";//unicode for emoticon

    } elseif ($_SESSION['Blackjack']->getPlayer()->hasLost()) {

        echo "You lost &#128538";//unicode for emoticon
    }

    if (isset($_SESSION['Blackjack']) && ($_SESSION['Blackjack']->getPlayer()->hasLost())) {
        unset($_SESSION['Blackjack']);
    }
}

if (!isset($_SESSION['Blackjack'])) {
    $_SESSION['Blackjack'] = new Blackjack();
}
//var_dump($_SESSION['Blackjack']);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Megrim&display=swap" rel="stylesheet">
    <link href="//db.onlinewebfonts.com/c/f4d1593471d222ddebd973210265762a?family=Pokemon" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <title>BlackJack PHP</title>
</head>
<body>
<?php

if (!isset($_POST) || isset($_POST['new-round'])) {//start

    echo "<br>";
    echo "your total: " . $_SESSION['Blackjack']->getPlayer()->getScore() . PHP_EOL;
    $_SESSION['Blackjack']->getPlayer()->displayHand();

    if ($_SESSION['Blackjack']->getPlayer()->getScore() > $_SESSION['Blackjack']->getPlayer()::WINNING_VALUE) {
        $_SESSION['Blackjack']->getPlayer()->surrender();
        checkWinner();
    }

} elseif (isset($_POST['hold'])) {
    $_SESSION['Blackjack']->getDealer()->hit($_SESSION['Blackjack']->getDeck());
    echo "Dealer's total: " . $_SESSION['Blackjack']->getDealer()->getScore() . PHP_EOL;
    echo "<br>";
    echo "your total: " . $_SESSION['Blackjack']->getPlayer()->getScore() . PHP_EOL;
    $_SESSION['Blackjack']->getPlayer()->displayHand();
    checkWinner();
} elseif (isset($_POST['hit'])) {
    $_SESSION['Blackjack']->getPlayer()->hit($_SESSION['Blackjack']->getDeck());

    echo "<br>";
    echo "your total: " . $_SESSION['Blackjack']->getPlayer()->getScore() . PHP_EOL;
    $_SESSION['Blackjack']->getPlayer()->displayHand();
    if ($_SESSION['Blackjack']->getPlayer()->getScore() > $_SESSION['Blackjack']->getPlayer()::WINNING_VALUE){
        checkWinner();
    }
}
if (isset($_POST['surrender'])) {
    echo "Dealer's total: " . $_SESSION['Blackjack']->getDealer()->getScore() . PHP_EOL;
    echo "<br>";
    echo "your total: " . $_SESSION['Blackjack']->getPlayer()->getScore() . PHP_EOL;
    $_SESSION['Blackjack']->getPlayer()->displayHand();
    $_SESSION['Blackjack']->getPlayer()->surrender();
    echo "You gave up!";
    checkWinner();
}

?>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <button type="submit" class="btn" name="hit">Hit me!</button>
    <button type="submit" class="btn" name="hold">Hold me!</button>
    <button type="submit" class="btn" name="surrender">I give up!</button>
    <button type="submit" class="btn" name="new-round">New Round!</button>
</form>
</body>
</html>