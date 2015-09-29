<?php

if (isset($_GET["role"])) {
	$role = $_GET["role"];
} else {
	$role = "administrator";
}

if (isset($user) && $user->verify()) {
	if (in_array($role, $user->roles) || in_array("administrator", $user->roles)) {
		if (isset($_POST["cmd"])) {
			$cmd = $_POST["cmd"];
			unset($_POST["cmd"]);
			if ($role == "administrator") {
				if ($cmd == "roles") {
					if (isset($_POST["newRole"],$_POST["username"])) {
						$username = strtolower(str_replace(" ",".",$_POST["username"]));
						$cRoles = $db["people"]->select("users",["roles"],["username"=>$username]);
						if (isset($cRoles[0])) {
							$cRoles = explode(",", $cRoles[0]["roles"]);
							$cRoles[] = $_POST["newRole"];
							$cRoles = array_filter(array_unique($cRoles));
							$cRoles = join(",",$cRoles);
							$db["people"]->update("users",["roles"=>$cRoles],["username"=>$username]);
							$data["response"] = "success";
						} else {
							$errors[] = "Could not find a user with that name";
						}
					} else {
						$errors[] = "Invalid post parameters";
					}
				}
			} else if ($role == "volunteer") {
				if ($cmd == "roles") {
					if (isset($_POST["username"])) {
						$username = strtolower(str_replace(" ",".",$_POST["username"]));
						$cRoles = $db["people"]->select("users",["roles"],["username"=>$username]);
						if (isset($cRoles[0])) {
							$cRoles = explode(",", $cRoles[0]["roles"]);
							$cRoles[] = "volunteer";
							$cRoles = array_filter(array_unique($cRoles));
							$cRoles = join(",",$cRoles);
							$db["people"]->update("users",["roles"=>$cRoles],["username"=>$username]);
							$data["response"] = "success";
						} else {
							$errors[] = "Could not find a user with that name";
						}
					} else {
						$errors[] = "Invalid post parameters";
					}
				} else if ($cmd == "search") {
					$queryString = [];
					foreach ($_POST as $name => $value) {
						if ($value == "on") $queryString[] = "$name = '1'";
						else if ($value != "") $queryString[] = "$name like '%$value%' COLLATE NOCASE";
					}
					$queryString = join(" and ",$queryString);

					$r = $db["people"]->select("volunteers","user_id",$queryString);
					if (!$r) $errors[] = "No results found";
					foreach ($r as $row) {
						$result = $db["people"]->select("profiles","username,fullname,email",["user_id"=>$row["user_id"]])[0];
						if ($result["email"] == "")
							$result["email"] = $result["username"]."@wallawalla.edu";
						$data["results"][] = $result;
					}
				}
			}
		} else {
			$errors[] = "No command specified";
		}
	} else {
		$errors[] = "You do not have the appropriate permissions to view this content";
	}
} else {
	$errors[] = "You must be logged in to access this page";
}

?>
