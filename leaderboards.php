<?php session_start();
	
	if(!(isset($_SESSION['wsdl'])))
		header("location: index.php");
?>
<!doctype html>
<html lang="en-ie">

<head>
	<link rel="stylesheet" href="css/style.css">
	<title></title>
</head>

<body>
	<div id="container">
		<div id="content">
			<?php
			if(isset($_POST['leaderboard'])) {
				$client = new SoapClient($_SESSION['wsdl'], array('trace' => $_SESSION['trace'], 'exceptions' => $_SESSION['exceptions']));
				$response = $client->leagueTable();
				$data = $response->return;
				$arr = null;
				switch($data) {
					case "ERROR-NOGAMES":
						echo "No Games have been played yet.";
						break;
					case "ERROR-DB":
						echo "Cannot conenct to db.";
						break;
					default:
						$data = explode("\n", $data);
						foreach($data as $datum) {
							$line = explode(",", $datum);
							$name1 = $line[1];
							$name2 = $line[2];
							$gameState = $line[3];
							if($gameState == 1) {
								if(!isset($arr[$name1]))	$arr[$name1] = 0;
								$arr[$name1] = ($arr[$name1] + 1);
							} else if($gameState == 2) {
								if(!isset($arr[$name2]))	$arr[$name2] = 0;
								$arr[$name2] = ($arr[$name2] + 1);
							}
						}
						?>
						<table>
							<tr><td>name</td><td>Wins</td></tr>
							<?php foreach($arr as $key => $value)  {
								echo "<tr>
									<td>$key</td>
									<td>$value</td>
									</tr>";
							}
							?>

							
						</table>
						<?php
				}
			}
				?>
		</div>

		<div id="footer">
			<form action="menu.php" method="POST">
				<table>
					<tr>
						<td><input type="submit" value="Back" id="back" name="back"></td>
					</tr>
				</table>
		</div>

	</div>
</body>