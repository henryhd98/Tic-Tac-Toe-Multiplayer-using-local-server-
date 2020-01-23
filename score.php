<?php session_start(); 
	$wsdl = $_SESSION['wsdl'];
	$trace = $_SESSION['trace'];
	$exceptions = $_SESSION['exceptions'];
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
			$client = new SoapClient($_SESSION['wsdl'], array('trace' => $_SESSION['trace'], 'exceptions' => $_SESSION['exceptions']));
			$response = $client->leagueTable();
			$data = $response->return;
			$wins = 0;
			$losses = 0;
			$draws = 0;
			switch($data) {
				case 'ERROR-NOGAMES': echo $data; break;
				case 'ERROR-DB': echo $data; break;
				default:
				$data = explode("\n", $data);
				foreach($data as $datum) {
					$line = explode(",", $datum);
					if($line[1] == $_SESSION['username'] && $line[3] == '1')
						$wins++;
					else if($line[2] == $_SESSION['username'] && $line[3] == '2') 
						$wins++;
					else if($line[3] == 3) 
						$draws++;
					else if($line[1] == $_SESSION['username'] && $line[3] == '2') 
						$losses++;
					
					else if($line[2] == $_SESSION['username'] && $line[3] == '1')
						$losses++;
				}
				echo "<h4>The current stats for " . $_SESSION['username'] . "</h4>";
				echo "<table>";
				echo "<tr>";
					echo"<td>Wins</td>";
					echo "<td> $wins</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td>Losses</td>";
					echo "<td> $losses</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td>Draws</td>";
					echo "<td> $draws</td>";
				echo "</tr>";
				echo "</table>";
				$userid = $_SESSION['username'];
			}
			?>
		</div>
	</div>
</body>