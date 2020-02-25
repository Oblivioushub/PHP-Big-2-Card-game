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
//this is old code representing the creation of a bot holding all cards not taken by player.
//creating CONSISTENT NPC cards
//if ( ! isset($_SESSION['npcs_hand']) ) {
//	$_SESSION['npcs_hand'] = array_diff ($cards, $_SESSION['players_hand']); }

/// creation of 3 seprate bot hands.
if ( ! isset($_SESSION['bot1_hand']) ) {
	$remaining_cards = array_diff ($values, $_SESSION['players_hand']); 
	$_SESSION['bot1_hand'] = array_rand($remaining_cards, 13);
}

if ( ! isset($_SESSION['bot2_hand']) ) {
	$taken_cards = array_intersect($_SESSION['players_hand'], $_SESSION['bot1_hand']);
	$remaining_cards = array_diff ($values, $taken_cards);
	$_SESSION['bot2_hand'] = array_rand($remaining_cards, 13);
}
if ( ! isset($_SESSION['bot3_hand']) ) {
	$taken_cards = array_intersect($_SESSION['players_hand'], $_SESSION['bot1_hand'], $_SESSION['bot2_hand']);
	print_r ($taken_cards);
	$remaining_cards = array_diff ($values, $taken_cards); 
	$_SESSION['bot3_hand'] = array_rand($remaining_cards, 13);
}


echo "<H3>Your hand: ";
if (empty($_SESSION['players_hand'])) {
	echo "IS EMPTY <H1 style='color: green;'>CONGRATS YOU HAVE WON THE GAME!!!! <br> Click reshuffle to restart</H1>";
	} else {
	foreach ($_SESSION['players_hand'] as $card) {
	  echo " $card ,";
	}
}

if (empty($_SESSION['bot1_hand'])) {
	echo "<H1 style='color: red;'>Sadly You Lost :( <br>Bot 1 has won the game<br> Click reshuffle to restart</H1>";
}elseif (empty($_SESSION['bot2_hand'])) {
	echo "<H1 style='color: red;'>Sadly You Lost :( <br>Bot 2 has won the game<br> Click reshuffle to restart</H1>";
}elseif (empty($_SESSION['bot3_hand'])) {
	echo "<H1 style='color: red;'>Sadly You Lost :( <br>Bot 3 has won the game<br> Click reshuffle to restart</H1>";
}
echo "<br>";
//check who has the 3D and then force them to play it. then create play order based on it
if (count($_SESSION['players_hand']) == 13 and count($_SESSION['bot1_hand']) == 13 and count($_SESSION['bot2_hand']) == 13 and count($_SESSION['bot3_hand']) == 13){
	if (reset($_SESSION['players_hand']) == "3D") {
		echo "You start the game with the 3D";
		unset($_SESSION['players_hand'][0]); 
		$_SESSION['played_card'] = "3D";
		$_SESSION['play_order'] = ['bot1_hand', 'bot2_hand', 'bot3_hand', 'players_hand'];
	//check bot's hand's
	} elseif (reset($_SESSION['bot1_hand']) == "3D") {
		echo "Bot 1 starts the game with the 3D";
		unset($_SESSION['bot1_hand']["3D"]); 
		$_SESSION['played_card'] = "3D";
		$_SESSION['play_order'] = ['bot2_hand', 'bot3_hand', 'players_hand', 'bot1_hand'];
	}elseif (reset($_SESSION['bot2_hand']) == "3D") {
		echo "Bot 2 starts the game with the 3D";
		unset($_SESSION['bot2_hand']["3D"]); 
		$_SESSION['played_card'] = "3D";
		$_SESSION['play_order'] = ['bot3_hand', 'players_hand', 'bot1_hand', 'bot2_hand'];
	}elseif (reset($_SESSION['bot3_hand']) == "3D") {
		echo "Bot 3 starts the game with the 3D";
		unset($_SESSION['bot3_hand']["3D"]); 
		$_SESSION['played_card'] = "3D";
		$_SESSION['play_order'] = ['players_hand', 'bot1_hand', 'bot2_hand', 'bot3_hand'];
	}
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

//this section is for the programing of bot plays against on another and setting the played_card value
$bot_names = ['bot1_hand', 'bot2_hand', 'bot3_hand'];
$who_played = reset($_SESSION['play_order']);



if (in_array($who_played, $bot_names)) {
	
	//reseting the play order
	$key = array_search ($who_played, $_SESSION['play_order']);
	unset($_SESSION['play_order'][$key]);
	print_r ($_SESSION['play_order']);
	array_push($_SESSION['play_order'], $who_played);
	
	//creating the new played card based on the current.
	$pc_d = True;
	foreach ($_SESSION[$who_played] as $card) {
		if ($pc_d) {
			if ($values[$card] > $values[$played_card]) {
				$pc_d = False;
				$_SESSION['played_card'] = $card;
			}
		}
	}
	//bot couldn't beat
	if ($pc_d) {
		$_SESSION['played_card'] = $_SESSION['played_card']; //remains the same XD
		header("Refresh:1");
	} else { //the bot is playing and now we have to remove the card from his hand
		$key = array_search ($_SESSION['played_card'], $_SESSION[$who_played]);
		unset($_SESSION[$who_played][$key]);
		echo "$who_played played the ";
		echo $_SESSION['played_card'];
		echo "'s";
		header("Refresh:1");
	}
	
	

} else { //its the players turn
	//reseting the play order
	$key = array_search ('players_hand', $_SESSION['play_order']);
	unset($_SESSION['play_order'][$key]);
	array_push($_SESSION['play_order'], "players_hand");
	
	if ($user_card == "not_played") {
		//tell the user its there turn to play.
		echo "<h2 style='color: blue;'>Input what card you want to play</h2>";
	} else {
		//if user has passed
		if ($user_card == " ") {
			echo "<h2 style='color: blue;'>User has passed</h2>";
			
			//its the next bots turn and the played_card remains the same
			$_SESSION['played_card'] = $_SESSION['played_card'];
			
			//refresh code to check which bots turn it is.
			header("Refresh:1");
			
			} else {
				
			//check if card that the player wants to play is in there hand.
			if (in_array($user_card, $_SESSION['players_hand'])) {
			
			//check if user is starting tick
			if ($_SESSION['played_card'] == " ") {
				
				//tell the user that they started the play with $user_card
				echo "<h2 style='color: green;'>Good play! You started the tick with the $user_card's";
				
				//remove card from the users hand
				$key = array_search ($user_card, $_SESSION['players_hand']);
				unset($_SESSION['players_hand'][$key]);
				
				//set the new card to beat as the users card
				$_SESSION['played_card'] = $user_card;
				
				//show player new hand and restart played_card formula
				header("Refresh:1");
				
			//check if card is greater then played card
			} elseif ($values[$played_card] < $values[$user_card]) {
			
				//tells the user that they succeded at playing the game
				echo "<h2 style='color: green;'>Good play! The $user_card's beats the $played_card's</h2>";
				
				//this is the removeing of the card from the players hand.
				$key = array_search ($user_card, $_SESSION['players_hand']);
				unset($_SESSION['players_hand'][$key]);
				
				//set the new card to beat as the users card
				$_SESSION['played_card'] = $user_card;
				
				//show player new hand and refresh code to figure out new card to beat.
				header("Refresh:1");
				
				
			} else {
				echo "<h2 style='color: red;'>Bad play... The $user_card's Dosen't beat the $played_card's, sorry</h2>";
			}
	} else {//user dosent have the card
		echo "<h2 style='color: red;'>Sorry you dont have that card</h2>";
		}
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