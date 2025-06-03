<?php

class ApiKey extends App {


    public static function add($data) {
    	global $database;
    	$lastid = $database->insert("api_keys", [
    		"roleid" => $data['roleid'],
    		"name" => $data['name'],
            "secretkey" => $data['secretkey'],
    	]);
    	if ($lastid == "0") { return "11"; } else { logSystem("API Key Added - ID: " . $lastid); return "10"; }
    	}


    public static function edit($data) {
    	global $database;
    	$database->update("api_keys", [
            "roleid" => $data['roleid'],
    		"name" => $data['name'],
            "secretkey" => $data['secretkey'],
    	], [ "id" => $data['id'] ]);
    	logSystem("API Key Edited - ID: " . $data['id']);
    	return "20";
    	}


    public static function delete($id) {
    	global $database;
        $database->delete("api_keys", [ "id" => $id ]);
    	logSystem("API Key Deleted - ID: " . $id);
    	return "30";
    	}


}

?>
