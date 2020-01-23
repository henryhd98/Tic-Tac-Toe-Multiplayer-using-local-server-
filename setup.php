<?php session_start(); 
	if(!(isset($_SESSION['wsdl'])))
		header("location: index.php");
	$wsdl = $_SESSION['wsdl'];
	$trace = $_SESSION['trace'];
	$exceptions = $_SESSION['exceptions'];
?>
<!doctype html>
<html lang="en-ie">

<head>
		<link rel="stylesheet" href="css/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	<!-- Include one of jTable styles. -->
	<link href="/jtable/themes/metro/blue/jtable.min.css" rel="stylesheet" type="text/css" />
	 
	<!-- Include jTable script file. -->
	<script src="/jtable/jquery.jtable.min.js" type="text/javascript"></script>
	<title></title>
</head>

<body>

	<div id="container">
		<div id="content">

				<?php
		$wsdl = $_SESSION['wsdl'];
		$userid = $_SESSION['uname'];
		$client = new SoapClient($_SESSION['wsdl'], array('trace' => $_SESSION['trace'], 'exceptions' => $_SESSION['exceptions']));
		/*
		if(isset($_POST['retry'])) {
			$params = null;
			$gameid = $_SESSION['gameid'];
			$params['gid'] = $_SESSION['gameid'];
			$response = $client->getGameState($params);
			$gameState = $response->return;
			if($gameState < 0) {
				echo "Waiting for player 2 to join game $gameid. Please click Retry when player 2 has joined..";
				?>
				<form action="menu.php" method="POST">
				<table>
					<tr>
						<td><input type="submit" name="quit" value="quit" id="quit" tabindex="1"></td>
					</tr>
				</table>
				</form>
				<form action="setup.php" method="POST">
							<table>
								<tr>
									<td><input type="submit" name="retry" value="retry" id="retry" tabindex="2"></td>
								</tr>
							</table>
						</form>
					<?php
			} else {
				header("location: game.php");
			}*/
		
		function newGame($userid, $client) {
			$params = null;
			$params['uid'] = $userid;
			$response = $client->newGame($params);
			$gameid = (int) $response->return;
			
			switch($gameid) {
				case $gameid > 0: 
				$params = null;
				$_SESSION['gameid'] = $gameid;
				
				header("location: game.php");
				break;
				default: echo "mistake";
			}
			
		}
		function joinGame($userid, $client) {
			?>
			<form action="setup.php" method="POST">
				<table>
					<tr>
						<td>Enter Game ID: </td>
						<td><input type="text" value="" name="gameID" id="gameID"></td>
						<td><input type="submit" value="join" name="join" id="join"></td>
					</tr>
			</form>
			<?php
			$response = $client->showOpenGames();
			$data = $response->return;
			$data = explode("\n", $data);
			echo "<table> <tr>
				<td>GameID</td>
				<td>	Created by</td>
				<td>	Date </td></tr";
				echo "<tr></tr>";
			foreach($data as $datum) {
				echo "<tr>";
				$line = explode(",", $datum);
				for($i = 0;$i < count($line);$i++) {
					echo "<td>" . $line[$i] . "<td>";
				}
				echo "</tr>";
			} echo "</table";
		}
		
		if(isset($_POST['join']) && isset($_POST['gameID'])) {
			$params = array('uid' => $_SESSION['uname'], 'gid' => $_POST['gameID']);
			$response = $client->joinGame($params);
			switch($response->return) {
				case '1': 	
					$_SESSION['gameid'] = $_POST['gameID']; 
					header("location: game.php");
					break;
				default: echo "Error unable to Join game." ?> <form action="setup.php" method="POST"> <input type="submit" value="back" name="joinGame"></form><?php
			}
		}
		if(isset($_POST['newGame'])) {
			newGame($userid, $client);
		} else if(isset($_POST['joinGame'])) {
			joinGame($userid, $client);
		}
	?>
		</div>
	</div>
</body>
</html>