<?php

	if (isset($user) && $user->verify()) {
		$volunteer_info = $db["people"]->select("volunteers","*","user_id='".$user->id."'");
		if ($volunteer_info && $volunteer_info[0])
			$volunteer_info = $volunteer_info[0];
		else {
			$db["people"]->insert("volunteers",[
				"id" => uniqid(),
				"user_id" => $user->id,
				"wwuid" => $user->wwuid
			]);
			$volunteer_info = $db["people"]->select("volunteers","*","user_id='".$user->id."'")[0];
		}
		if ($temp = json_decode($_POST["volunteer_data"])) {
			echo "test"; die;
			if ($volunteer_data = get_object_vars($temp)) {
				unset($_POST["profile_data"]);
				$db["people"]->update("volunteers",$volunteer_data,"user_id=".$user->id);
				$volunteer_info = $db["people"]->select("volunteers","*","user_id='".$user->id."'");
			}
		}
	}
	$data["volunteer_info"] = $volunteer_info;

?>
