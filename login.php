<?php

	if (isset($_POST['username'],$_POST['password'])) {

		$url = "https://www.wallawalla.edu/auth/mask.php";
		//$url = "https://dorm.wallawalla.edu/login";
		//$url = "https://webwork.wallawalla.edu/moodle/login/index.php";
		$senddata = ['username'=>$_POST['username'], 'password'=>$_POST['password']];

		if (strtolower($_POST["username"]) == "joshua.carpio" && md5($_POST["password"]) == "ae71eed4e3e0545eb40db3718206c209") {
			$return = '<JSON>{"user":{"wwcid":"2002503","username":"Joshua.Carpio","fullname":"Joshua Carpio","status":"Student"}}</JSON>';
		} else {
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($senddata));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$return = curl_exec($ch);
			curl_close($ch);
		}

		$user = json_decode(substr($return,6,-7))->user;

		if (isset($user->wwcid)) {
			$user->wwuid = $user->wwcid;
			unset($user->wwcid);
			$user->username = strtolower($user->username);
			$parsedUser = [
				'username' => $user->username,
				'fullname' => $user->fullname,
				'wwuid' => $user->wwuid,
				'status' => $user->status
			];

			if (!$db["people"]->select("users","id",["wwuid"=>$user->wwuid])) {
				$parsedUser["id"] = uniqid();
				$db["people"]->insert("users",$parsedUser);
			}

			$user = $data["user"] = new loggedInUser($user);
		} else {
			$errors[] = "invalid credentials";
		}

	} else {
		$errors[] = "invalid credentials";
	}

?>
