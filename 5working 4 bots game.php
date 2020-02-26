<html>
<head>
<?php 
//this hole section is for setting up the start, end and wellbeing of the game.
//this is a array defining the value of each card. I will use this to compare card values.
$values = [
    '3D' => 1, '3C' => 2, '3H' => 3, '3S' => 4, '4D' => 5, '4C' => 6, '4H' => 7, '4S' => 8, '5D' => 9, '5C' => 10, '5H' => 11, '5S' => 12, '6D' => 13, '6C' => 14, '6H' => 15, '6S' => 16, '7D' => 17, '7C' => 18, '7H' => 19, '7S' => 20, '8D' => 21, '8C' => 22, '8H' => 23, '8S' => 24, '9D' => 25, '9C' => 26, '9H' => 27, '9S' => 28, '0D' => 29, '0C' => 30, '0H' => 31, '0S' => 32, 'JD' => 33, 'JC' => 34, 'JH' => 35, 'JS' => 36, 'QD' => 37, 'QC' => 38, 'QH' => 39, 'QS' => 40, 'KD' => 41, 'KC' => 42, 'KH' => 43, 'KS' => 44, 'AD' => 45, 'AC' => 46, 'AH' => 47, 'AS' => 48, '2D' => 49, '2C' => 50, '2H' => 51, '2S' => 52
];

//this is a array of just cards not valued. I will use this to create the player hands and such
$cards = [
1 => '3D', 2 => '3C', 3 => '3H', 4 => '3S', 5 => '4D', 6 => '4C', 7 => '4H', 8 => '4S', 9 => '5D', 10 => '5C', 11 => '5H', 12 => '5S', 13 => '6D', 14 => '6C', 15 => '6H', 16 => '6S', 17 => '7D', 18 => '7C', 19 => '7H', 20 => '7S', 21 => '8D', 22 => '8C', 23 => '8H', 24 => '8S', 25 => '9D', 26 => '9C', 27 => '9H', 28 => '9S', 29 => '0D', 30 => '0C', 31 => '0H', 32 => '0S', 33 => 'JD', 34 => 'JC', 35 => 'JH', 36 => 'JS', 37 => 'QD', 38 => 'QC', 39 => 'QH', 40 => 'QS', 41 => 'KD', 42 => 'KC', 43 => 'KH', 44 => 'KS', 45 => 'AD', 46 => 'AC', 47 => 'AH', 48 => 'AS', 49 => '2D', 50 => '2C', 51 => '2H', 52 => '2S',
];

//function to covert the values in a array back to cards.
function convert_into_cards($card_values) {
	
	global $cards;//adds cards to the use of the function
	
	$actual_cards = [];//emptey array we will be appending to
	
    foreach ($card_values as $value) {//refrence each card individualy
		array_push($actual_cards, $cards[$value]);//add card to the array.
	}
	return $actual_cards;//return converted array.
}

//start game (this will start the session, that I will use to create unchanging array with random.)
session_start();



//creating consistent player hands.
//player 1
if ( ! isset($_SESSION['player1_hand']) ) { // if the first players hand dose not exist
	$card_values = array_rand($cards, 13); // give player 13 random cards from the card valuess array and convert it into a consistent array
	$_SESSION['player1_hand'] = convert_into_cards($card_values);//now there cards.
}


//player 2
if ( ! isset($_SESSION['player2_hand']) ) { // if player 2 dose not have a hand

	$remaining_cards = array_diff ($cards, $_SESSION['player1_hand']); //check player1's hand and create array of card that can be used
	
	$card_values = array_rand($remaining_cards, 13);//this gives me keys 
	
	$_SESSION['player2_hand'] = convert_into_cards($card_values);//so I have to convert them using previously defined function.
}

//player 3
if ( ! isset($_SESSION['player3_hand']) ) { // if player 3 dose not have a hand

	$taken_cards = array_merge($_SESSION['player1_hand'], $_SESSION['player2_hand']); //array of both player1 and player2's hands.
	
	$remaining_cards = array_diff ($cards, $taken_cards); //check players hands and create array of cards that are not in it
	
	$card_values = array_rand($remaining_cards, 13);//this gives me keys 
	
	$_SESSION['player3_hand'] = convert_into_cards($card_values);//so I have to convert them using previously defined function.
}

//player 4
if ( ! isset($_SESSION['player4_hand']) ) { // if player 4 dose not have a hand

	$taken_cards = array_merge($_SESSION['player1_hand'], $_SESSION['player2_hand'], $_SESSION['player3_hand']); //array of both player1, player2 and player3's hands.
	
	$remaining_cards = array_diff ($cards, $taken_cards); //cards not taken turned into a array
	
	$_SESSION['player4_hand'] = array_values($remaining_cards);//only have 13 cards left so this becomes the array.
}

//this is just to see everyones cards, for the test build.
print_r($_SESSION['player1_hand']);echo"<br>";print_r($_SESSION['player2_hand']);echo"<br>";print_r($_SESSION['player3_hand']);echo"<br>";print_r($_SESSION['player4_hand']);echo"<br>";

//convert session arrays into normal variables for easier use.
$player1_hand = $_SESSION['player1_hand'];$player2_hand = $_SESSION['player2_hand'];$player3_hand = $_SESSION['player3_hand'];$player4_hand = $_SESSION['player4_hand'];

//if their is a winner display the game over script for that player.
if (empty($player1_hand)) {
	echo "<H1 style='color: blue;'>GAME OVER <br>Player 1 has won the game<br> Click reshuffle to restart</H1>";
}elseif (empty($player2_hand)) {
	echo "<H1 style='color: blue;'>GAME OVER <br>Player 2 has won the game<br> Click reshuffle to restart</H1>";
}elseif (empty($player3_hand)) {
	echo "<H1 style='color: blue;'>GAME OVER <br>Player 3 has won the game<br> Click reshuffle to restart</H1>";
}elseif (empty($player4_hand)) {
	echo "<H1 style='color: blue;'>GAME OVER <br>Player 4 has won the game<br> Click reshuffle to restart</H1>";
}


//check who has the 3D and then force them to play it. then create play order based on it
//player1
if (reset($player1_hand) == "3D") {
	echo "<H1 style='color: blue;'>Player 1 Starts the game with the 3D's</H1>"; // tell the user who played
	header("Refresh:2");//leave text on the screen for 2 seconds
	unset($_SESSION['player1_hand'][0]); //remove the card from there hand
	$_SESSION['played_card'] = [0 => "3D", 1 => "player4"];//set the sessions played_card
	$_SESSION['play_order'] = ['player2', 'player3', 'player4', 'player1']; //set the sessions play order.
	
} elseif (reset($player2_hand) == "3D") {
	echo "<H1 style='color: blue;'>Player 2 Starts the game with the 3D's</H1>"; // tell the user who played
	header("Refresh:2");//leave text on the screen for 2 seconds
	unset($_SESSION['player2_hand'][0]); //remove the card from there hand
	$_SESSION['played_card'] = [0 => "3D", 1 => "player4"];//set the sessions played_card
	$_SESSION['play_order'] = ['player3', 'player4', 'player1', 'player2']; //set the sessions play order.
	
} elseif (reset($player3_hand) == "3D") {
	echo "<H1 style='color: blue;'>Player 3 Starts the game with the 3D's</H1>"; // tell the user who played
	header("Refresh:2");//leave text on the screen for 2 seconds
	unset($_SESSION['player3_hand'][0]); //remove the card from there hand
	$_SESSION['played_card'] = [0 => "3D", 1 => "player4"];//set the sessions played_card
	$_SESSION['play_order'] = ['player4', 'player1', 'player2', 'player3']; //set the sessions play order.
	
} elseif (reset($player4_hand) == "3D") {
	echo "<H1 style='color: blue;'>Player 4 Starts the game with the 3D's</H1>"; // tell the user who played
	header("Refresh:2");//leave text on the screen for 2 seconds
	unset($_SESSION['player4_hand'][0]); //remove the card from there hand
	$_SESSION['played_card'] = [0 => "3D", 1 => "player4"];//set the sessions played_card
	$_SESSION['play_order'] = ['player1', 'player2', 'player3', 'player4']; //set the sessions play order.
}

//this area is for showing the specific player there cards once the game is ready to be moved to that stage.

?>


<?php
//this area is devoted to ticks and actual game.

//this is just here for future use.
//if(isset($_POST['Pass'])){
//	$_SESSION['player1_hand'] = " ";
//}

//for checking whos turn it is
$who_turn = reset($_SESSION['play_order']);

//this section is bot framework and will be modified when players are re introduced.
if ($_SESSION['played_card'][1] == $who_turn) {//if noone can beat the played card
	$_SESSION['played_card'][0] = "3D";
}

$pc_d = False;
foreach ($_SESSION[$who_turn."_hand"] as $card) {
	if (!$pc_d) {
		if ($values[$card] > $values[$_SESSION['played_card'][0]]) {
			$pc_d = True;
			$_SESSION['played_card'] = [0 => $card, 1 => $who_turn];
		}
	}
}
$player_key = array_search ($who_turn, $_SESSION['play_order']);
if ($pc_d) {//the player has played
	$played_card = $_SESSION['played_card'][0];//so that i can refrence it in a comment
	echo "$who_turn played the $played_card's ";
	//remove card from there hand
	$key = array_search ($_SESSION['played_card'][0], $_SESSION[$who_turn."_hand"]);
	unset($_SESSION[$who_turn."_hand"][$key]);
	//update play order
	unset($_SESSION['play_order'][$player_key]);
	array_push($_SESSION['play_order'], $who_turn);
	header("Refresh:1");//and display new game positioning.

} else {
	echo "$who_turn Passed";
	unset($_SESSION['play_order'][$player_key]);
	array_push($_SESSION['play_order'], $who_turn);
	header("Refresh:1");
	}



?>
<?php
//if the player clicks reshuffle
if(isset($_POST['reshuffle'])){
	
        echo "<br>reshuffle is commencing"; //tell the player what is doing for 1 second
		
		session_destroy(); //destroy the session/ restart the game
		
		header("Refresh:0"); //re display the new game info
    }
?>

<form  method="post">
    <input type="submit" name="reshuffle" value="reshuffle">
</form>
</body>
</html>