<?php

	if (isset($user) && $user->verify()) {
		$volunteer_info = $db["people"]->select("volunteers","*","wwuid=".$user->wwuid);
		if ($volunteer_info[0])
			$volunteer_info = $volunteer_info[0];
		else {
			$db["people"]->insert("volunteers",[
				"id" => uniqid(),
				"user_id" => $user->id,
				"wwuid" => $user->wwuid
			]);
			$volunteer_info = $db["people"]->select("volunteers","*","wwuid=".$user->wwuid)[0];
		}
		print_r($volunteer_info); die;
		if (isset($_POST["volunteer_data"])) {
			
		}
	}

?>
