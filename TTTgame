<?php session_start(); 
	if(!(isset($_SESSION['wsdl']) && isset($_SESSION['gameid'])))
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


		<table id="table"></table>
		<form id="controls"></form>
		<?php
			$wsdl = $_SESSION['wsdl'];
			$userid = $_SESSION['uname'];
			$gameOver = false;
			$client = new SoapClient($_SESSION['wsdl'], array('trace' => $_SESSION['trace'], 'exceptions' => $_SESSION['exceptions']));
			/*
			main game loop, user inputs x and y value, it sends it to the webservice, and then drawBoard is called which redraws the board. If unsuccesful in inserting something into the table then it is also still redrawn but an error message is outputted
			*/
			if(isset($_POST["submit"])) {
					$params = array('gid' => $_SESSION['gameid']);
				if(isset($_POST['col']) && isset($_POST['row'])) {
					$params = array('x' => $_POST['col'], 'y' => $_POST['row'], 'gid' => $_SESSION['gameid']);
					$response = $client->checkSquare($params);
					switch($response->return) {
						case 0:
						$params = array('gid' => $_SESSION['gameid']);
						$response = $client->getBoard($params);
						$data = $response->return;
						//if(isMyTurn($data)) {
						//Sends x and y to webservice 
						$params = array('x' => $_POST['col'], 'y' => $_POST['row'], 'gid' => $_SESSION['gameid']);
							$params['pid'] = $_SESSION['uname']; 
							$client->takeSquare($params)->return;
							$params = array('gid' => $_SESSION['gameid']);
							$response = $client->getBoard($params);
							$client = $response->return;
							$_SESSION['lastD'] = $client;
							drawBoard($_SESSION['lastD']);
							showControls();
							$response = $client->checkWin($params);
							//handling win conditions
							switch($response->return) {
								case 1: echo "Player 1 has won"; $params = array('gid' => $_SESSION['gameid'], 'gstate' => 1); $response = $client->setGameState($params); break;
								case 2: echo "Player 2 has won"; $params = array('gid' => $_SESSION['gameid'], 'gstate' => 2); $response = $client->setGameState($params);break;
								case 3: echo "DRAW!"; $params = array('gid' => $_SESSION['gameid'], 'gstate' => 3); $response = $client->setGameState($params);break;
								default: 
							//}
							}
							drawBoard($_SESSION['lastD']);
							showControls();
						break;
						//Only called if tile has been taken
						case 1:
							echo "<h3>Error, That tile has been taken.</h3>"; 
									$params = array('gid' => $_SESSION['gameid']);
									$response = $client->getBoard($params);
									$_SESSION['lastD'] = $response->return;
									drawBoard($_SESSION['lastD']);
									showControls();
							break;
						default: echo "<h1>FAILURE CONNECTING TO DBMS</h1>";
					}
				}
				//Instead of fetching data everytime I saved the last backup of data from getboard to be redrawn.
				else {
					drawBoard($_SESSION['lastD']);
					showControls();
					echo "<h2>Invalid column and row supplied.</h2>";
				}
				
			} else {
				$params = array('gid' => $_SESSION['gameid']);
					$response = $client->getBoard($params);
					$data = $response->return;
					drawBoard($data);
					showControls();
			}
			function runGame() {
			}
			function isMyTurn($data) {
					$data = explode("\n", $data);
					$line = $data[count($data) - 1];
					$line = explode(",", $line);
					if(!$line[0] == $_SESSION['uname']) 
						return true;
					return false;
			}
			function drawBoard($data) {
				$_SESSION['lastD'] = $data;
				?><script>$("#table").remove() ;</script><?php
				echo"Showing board for game ". $_SESSION['gameid'] . "<br>";
				$lines = explode("\n", $data);
				echo "<table id='table'>";
				$arr;
				//This is the 3x3 grid, initialised to NA as a placeholder (supposed to be blank);
				for($i = 0;$i < 3;$i++) {
					for($j = 0; $j < 3;$j++) {
						$arr[$i][$j] = "NA";
					}
				}
				foreach($lines as $line) {
					$tokens = explode("," ,$line);
					if(count($tokens) == 3) {
						$x = $tokens[1];
						$y = $tokens[2];
						if($tokens[0] == $_SESSION['uname']) {
							$arr[$x][$y] = "x";
						}
						else 
							$arr[$x][$y] = "y";
					}
				}
				for($i = 0;$i < count($arr);$i++) {
					echo "<tr>";
					for($j = 0;$j < count($arr);$j++) {
						echo "<td> " . $arr[$i][$j] . "</td>";
					}
					echo "</tr>";
				}
				echo "</table>";
			}
			function showControls() {
				?><script>$("#controls").remove() ;</script>
				<form id="controls" action="game.php" method="POST">
					<table>
						<tr>
							<td><input type="text" placeholder="Col" id="col" name="col"></td>
							<td><input type="text" placeholder="Row" id="row" name="row"></td>
							<td><input type="submit" value="submit" name="submit" id="submit"></td>
						</tr>
					</table>
					<?php
			}
		?>

		<table id="gameTable">
			<tr>
			</tr>
		</table>

		</div>
	</div>
</body>
</html>
