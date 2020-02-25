<html>
<body>
<?php
//array of card values
$values = [
    '3D' => 1, '3C' => 2, '3H' => 3, '3S' => 4, '4D' => 5, '4C' => 6, '4H' => 7, '4S' => 8, '5D' => 9, '5C' => 10, '5H' => 11, '5S' => 12, '6D' => 13, '6C' => 14, '6H' => 15, '6S' => 16, '7D' => 17, '7C' => 18, '7H' => 19, '7S' => 20, '8D' => 21, '8C' => 22, '8H' => 23, '8S' => 24, '9D' => 25, '9C' => 26, '9H' => 27, '9S' => 28, '0D' => 29, '0C' => 30, '0H' => 31, '0S' => 32, 'JD' => 33, 'JC' => 34, 'JH' => 35, 'JS' => 36, 'QD' => 37, 'QC' => 38, 'QH' => 39, 'QS' => 40, 'KD' => 41, 'KC' => 42, 'KH' => 43, 'KS' => 44, 'AD' => 45, 'AC' => 46, 'AH' => 47, 'AS' => 48, '2D' => 49, '2C' => 50, '2H' => 51, '2S' => 52
];

//start game
session_start();
//creates consistent player hand
if ( ! isset($_SESSION['players_hand']) ) {
    $_SESSION['players_hand'] = array_rand($values, 13);
}

echo "<H3>Your hand: ";
foreach ($_SESSION['players_hand'] as $card) {
  echo " $card ,";
}
echo "</H3>";
?>
<form method="post" action="">
<H3> what is the card that was just played? </H3>
<input type="text" name="Played_card">
<br> <H3> what is the card you want to play? </H3>
<input type="text" name="User_card">
<input type="submit">
</form>
<br>
<?php
//check if user has filled in feilds
if ( ! empty($_POST['Played_card'])) {
	if ( ! empty($_POST['User_card'])) {
		$played_card = $_POST['Played_card'];
		$user_card = $_POST['User_card'];
	} else {$played_card = "3D" and $user_card = "3D";}
} else {$played_card = "3D" and $user_card = "3D";}



//check if card that the player wants to play is in there hand.
if (in_array($user_card, $_SESSION['players_hand'])) {
	if (in_array($played_card, $_SESSION['players_hand'])){
		echo "<h2 style='color: red;'> That card, that was just played is in your hand! </h2>";
	} else { //check if card is greater then played card
		if ($values[$played_card] < $values[$user_card]) {
		echo "<h2 style='color: green;'>Good play! The $user_card's beats the $played_card's</h2>";
		} else {
			echo "<h2 style='color: red;'>Bad play... The $user_card's Dosen't beat the $played_card's, sorry</h2>";
		}
	}
} else {
	echo "<h2 style='color: red;'>Sorry you dont have that card</h2>";
}
?>
<?php
if(isset($_POST['reshuffle'])){
        echo "<br>reshuffle is commencing";
		session_destroy();
		header("Refresh:0");
    }
?>
<form  method="post">
    <input type="submit" name="reshuffle" value="reshuffle">
</form>
</body>
</html>