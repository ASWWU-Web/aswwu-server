<?php

if (isset($_GET["issue"], $_GET["volume"])) {
	$issue = $_GET["issue"];
	$volume = $_GET["volume"];
	$folder = array_filter(glob("../collegian_archives/web/*v".$volume."*i".$issue."*"), is_dir)[0];
} else {
	$folder = array_filter(glob("../collegian_archives/web/*v".$volume."*i".$issue."*"), is_dir)[0];
}
$folder = array_reverse(explode("/",$folder))[0];

header("Location: https://aswwu.com/c_archives/".$folder);
exit;

?>
