<html>
<body>
<?php
//array of card values
$values = [
    '3D' => 1, '3C' => 2, '3H' => 3, '3S' => 4, '4D' => 5, '4C' => 6, '4H' => 7, '4S' => 8, '5D' => 9, '5C' => 10, '5H' => 11, '5S' => 12, '6D' => 13, '6C' => 14, '6H' => 15, '6S' => 16, '7D' => 17, '7C' => 18, '7H' => 19, '7S' => 20, '8D' => 21, '8C' => 22, '8H' => 23, '8S' => 24, '9D' => 25, '9C' => 26, '9H' => 27, '9S' => 28, '0D' => 29, '0C' => 30, '0H' => 31, '0S' => 32, 'JD' => 33, 'JC' => 34, 'JH' => 35, 'JS' => 36, 'QD' => 37, 'QC' => 38, 'QH' => 39, 'QS' => 40, 'KD' => 41, 'KC' => 42, 'KH' => 43, 'KS' => 44, 'AD' => 45, 'AC' => 46, 'AH' => 47, 'AS' => 48, '2D' => 49, '2C' => 50, '2H' => 51, '2S' => 52
];
$cards = ['3D', '3C', '3H', '3S', '4D', '4C', '4H', '4S', '5D', '5C', '5H', '5S', '6D', '6C', '6H', '6S', '7D', '7C', '7H', '7S', '8D', '8C', '8H', '8S', '9D', '9C', '9H', '9S', '0D', '0C', '0H', '0S', 'JD', 'JC', 'JH', 'JS', 'QD', 'QC', 'QH', 'QS', 'KD', 'KC', 'KH', 'KS', 'AD', 'AC', 'AH', 'AS', '2D', '2C', '2H', '2S'];

//start game
session_start();
//creates consistent player hand
if ( ! isset($_SESSION['players_hand']) ) {
    $_SESSION['players_hand'] = array_rand($values, 13);
}
//creating CONSISTENT NPC cards
if ( ! isset($_SESSION['npcs_hand']) ) {
	$_SESSION['npcs_hand'] = array_diff ($cards, $_SESSION['players_hand']); }
	


echo "<H3>Your hand: ";
if (empty($_SESSION['players_hand'])) {
	echo "IS EMPTY <H1 style='color: green;'>CONGRATS YOU HAVE WON THE GAME!!!! <br> Click reshuffle to restart</H1>";
	} else {
	foreach ($_SESSION['players_hand'] as $card) {
	  echo " $card ,";
	}
}

if (empty($_SESSION['npcs_hand'])) {
	echo "<H1 style='color: red;'>Sadly You Lost :( <br>I dont know how... because this varient of the game is so easy XD<br> Click reshuffle to restart</H1>";
}

//check and see if user has the 3D's and then play it instantly/remove else make bot play 3D's
if (count($_SESSION['players_hand']) == 13) {
	if (reset($_SESSION['players_hand']) == "3D") {
		$_SESSION['played_card'] = reset($_SESSION['npcs_hand']);
		if (($key = array_search(reset($_SESSION['players_hand']), $_SESSION['players_hand'])) !== false) {
			unset($_SESSION['players_hand'][$key]); }
	} else {
		$_SESSION['played_card'] = reset($_SESSION['npcs_hand']);
		$played_card = $_SESSION['played_card']; }
}

//check if user has filled in feilds
if ( ! empty($_POST['User_card'])) {
	$_SESSION['user_card'] = $_POST['User_card'];
	$user_card = $_SESSION['user_card'];
	} else {
	$_SESSION['user_card'] = "not_played";
	$user_card = $_SESSION['user_card'];
	}
echo "</H3>";
if ($_SESSION['played_card'] == "lost") {
echo "<H2 style='color: green;'>You won the tick! </H2> <H3>What card do you want to start with?</H3>"; 
} else {
	$played_card = $_SESSION['played_card'];
	echo "<H2>The current card to beat is $played_card</H2> <H3> what is the card you want to play? </H3>";
}

?>
<form method="post" action="">
<input type="text" name="User_card">
<input type="submit">
</form>
<form  method="post">
    <input type="submit" name="Pass" value="Pass">
</form>
<br>
<?php
$user_card = $_SESSION['user_card'];

if(isset($_POST['Pass'])){
	$user_card = " ";
    }

if ($_SESSION['played_card'] == null) {
echo "you won"; }

if ($user_card == "not_played") {
	echo "<h2 style='color: blue;'>Input what card you want to play</h2>";
	} else {
	if ($user_card == " ") {
		echo "<h2 style='color: blue;'>User has passed</h2>";
		$_SESSION['played_card'] = reset($_SESSION['npcs_hand']);
		$played_card = $_SESSION['played_card'];
		if (($key = array_search($_SESSION['played_card'], $_SESSION['npcs_hand'])) !== false) {
				unset($_SESSION['npcs_hand'][$key]); 
				header("Refresh:1");
			}
	}	else {
		//check if card that the player wants to play is in there hand.
		if (in_array($user_card, $_SESSION['players_hand'])) {
		//check if user is starting tick
		if ($_SESSION['played_card'] == "lost") {
			echo "<h2 style='color: green;'>Good play! You started the tick with the $user_card's";
			if (($key = array_search($user_card, $_SESSION['players_hand'])) !== false) {
				unset($_SESSION['players_hand'][$key]); }
			$pc_d = True;
			foreach ($_SESSION['npcs_hand'] as $card) {
				if ($pc_d) {
					if ($values[$card] > $values[$user_card]) {
						$pc_d = False;
						$_SESSION['played_card'] = $card;
					}
				}
			}
			//bot couldn't bead
			if ($pc_d) {
				$_SESSION['played_card'] = "lost";
			}
			//show player new hand and bot card.
			header("Refresh:1");
				
		//check if card is greater then played card
		} elseif ($values[$played_card] < $values[$user_card]) { 
				echo "<h2 style='color: green;'>Good play! The $user_card's beats the $played_card's</h2>";
				//this is the removeing of the card from the players hand.
				if (($key = array_search($user_card, $_SESSION['players_hand'])) !== false) {
				unset($_SESSION['players_hand'][$key]); }
				//this is removeing card form npcs hand
				unset($_SESSION['npcs_hand'][$played_card]);
				//checks if card has been decided on.
				$pc_d = True;
				foreach ($_SESSION['npcs_hand'] as $card) {
					if ($pc_d) {
						if ($values[$card] > $values[$user_card]) {
							$pc_d = False;
							$_SESSION['played_card'] = $card;
						}
					}
				}
				//bot couldn't bead
				if ($pc_d) {
					$_SESSION['played_card'] = "lost";
				}
				//show player new hand and bot card.
				header("Refresh:1");
				
				
				} else {
					echo "<h2 style='color: red;'>Bad play... The $user_card's Dosen't beat the $played_card's, sorry</h2>";
				}
			} else {//user dosent have the card
			echo "<h2 style='color: red;'>Sorry you dont have that card</h2>";
		}
	}
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