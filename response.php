<?php session_start(); 
	$wsdl = $_SESSION['wsdl'];
	$trace = $_SESSION['trace'];
	$exceptions = $_SESSION['exceptions'];
	$client = new SoapClient($_SESSION['wsdl'], array('trace' => $_SESSION['trace'], 'exceptions' => $_SESSION['exceptions']));
	$response = $client->showOpenGames();
	$data = $response->return;
	$jt = array();
	$jt['Result']  = "OK";
	$jt['Record'] = $data;
	print json_encode($jt);
?>