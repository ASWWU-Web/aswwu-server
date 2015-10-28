<?php

	header("Access-Control-Allow-Origin: *");
	error_reporting(E_ERROR);

  $root = "../databases/";
  ini_set("date.timezone","America/Los_Angeles");

	require_once("classes.php");
	require_once("search.php");

	$db = [
		"people" => new DataBase($root."people.db"),
		"archives" => new DataBase($root."archives.db")
	];

	$current_year = 1516;

  if (isset($_GET["token"]) && $_GET["token"] != "") {
		$wwuid = substr($_GET["token"],0,7);
		$token = substr($_GET["token"],7);
  	$user = new loggedInUser(json_decode(json_encode(["wwuid"=>$wwuid,"token"=>$token])));
  	if (!$user->verify())
			$errors[] = "invalid login";
		if (isset($_GET["verify"])) {
			echo (!isset($errors) ? json_encode($user) : "{}");
			die;
		}
  }

  if (isset($_GET["q"])) {
		if (isset($_GET["limits"])) $limits = explode(",",$_GET["limits"]);
		else $limits = [];
    $s = new Search($_GET["q"],$limits);
    $data["results"] = $s->fetch();
    unset($s);
  } else if (isset($_GET['cmd']) && !isset($errors))
		include_once($_GET['cmd'].".php");
	else if (!isset($data) && count($_GET) == 0)
		$errors[] = "no command specified";

	foreach ($db as $d)
		$d->close();

	if (!isset($data) && !isset($errors)) $errors[] = "no valid response";
	if (isset($errors)) $data["errors"] = $errors;

	header('Content-Type: application/json');
	echo json_encode($data);

?>
