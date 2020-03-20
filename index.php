<form  method="post">
  	<input type="text" id="name" name="name"><br>
</form>
<?php
//identify the user
$user_id = $_POST['name'];
//start session to host variables of the hole game.
session_start();

$_SESSION['players'] = [];

$count = 1;

do {
    $player=["player".$count, False, $user_id];
	$count++;
	array_push($_SESSION['players'], $player);
} while ($user_id==null);

print_r($_SESSION['players']);

if(isset($_POST['Ready'])){
	$location = array_search ($player, $_SESSION['players']);
	$player[1] = True;
	$_SESSION['players'][$location] == $player;
	echo "you are ready";
}
if(isset($_POST['reset'])){
	session_destroy();
}

?>
<form  method="post">
    <input type="submit" name="Ready" value="Ready">
	<input type="submit" name="reset" value="reset">
</form>