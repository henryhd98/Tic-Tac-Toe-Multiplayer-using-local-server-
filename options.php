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

	<?php
		if(isset($_POST['delete'])) {
			$client = new SoapClient($_SESSION['wsdl'], array('trace' => $_SESSION['trace'], 'exceptions' => $_SESSION['exceptions']));
			$params = array('gid' => $_POST['delete'], 'uid' => $_SESSION['uname']);
			$response = $client->deleteGame($params);
			switch($response->return) {
				case 1:
					echo "<h3>Game deleted succesfully!</h3>";
					break;
				default:
				 	echo "<h3> Error deleting game!</h3";
			}
		}
			
		if(isset($_SESSION['wsdl'])) {
			$client = new SoapClient($_SESSION['wsdl'], array('trace' => $_SESSION['trace'], 'exceptions' => $_SESSION['exceptions']));
			$params = array('uid' => $_SESSION['uname']);
			$response = $client->showMyOpenGames($params);
			$data = $response->return;
			$data = explode("\n", $data);
			echo "<h3> My open games </h3> <br>";
			echo "<form action='options.php' method='POST'>";
			echo "<table id=g> <tr>
				<td>GameID</td>
				<td>	Created by</td>
				<td>	Date </td>
				<td> </td</tr>";
				echo "</tr><tr>";
			foreach($data as $datum) {
				echo "<tr>";
				$line = explode(",", $datum);
				for($i = 0;$i < count($line);$i++) {
					echo "<td>" . $line[$i] . "</td>";
				}
				echo "<td><input type=submit value='$line[0]' name=delete id=delete></td>";
				echo "</tr>";
				
			} echo "</table></form>";
		} else
			header("location: index.php");
	?>

	<div id="container">
		<div id="content">
			<form action="menu.php" method="POST"> 
				<table>
					<tr>
						<td><input type="submit" value="back" id="back"></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</body>
</html>
