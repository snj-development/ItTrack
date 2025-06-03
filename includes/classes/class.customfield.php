<?php

class CustomField extends App {


    public static function addAssetCF($data) {
    	global $database;
    	$lastid = $database->insert("assets_customfields", [
    		"type" => $data['type'],
    		"name" => $data['name'],
            "description" => $data['description'],
            "options" => $data['options'],

    	]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Asset Custom Field Added - ID: " . $lastid); return "10"; }
    }


    public static function editAssetCF($data) {
    	global $database;
    	$database->update("assets_customfields", [
            "type" => $data['type'],
    		"name" => $data['name'],
            "description" => $data['description'],
            "options" => $data['options'],

    	], [ "id" => $data['id'] ]);
    	logSystem("Asset Custom Field Edited - ID: " . $data['id']);
    	return "20";
    }


    public static function deleteAssetCF($id) {
    	global $database;
        $database->delete("assets_customfields", [ "id" => $id ]);
    	logSystem("Asset Custom Field Deleted - ID: " . $id);
    	return "30";
    }




    public static function addLicenseCF($data) {
    	global $database;
    	$lastid = $database->insert("licenses_customfields", [
    		"type" => $data['type'],
    		"name" => $data['name'],
            "description" => $data['description'],
            "options" => $data['options'],

    	]);
    	if ($lastid == "0") { return "11"; } else { logSystem("License Custom Field Added - ID: " . $lastid); return "10"; }
    }


    public static function editLicenseCF($data) {
    	global $database;
    	$database->update("licenses_customfields", [
            "type" => $data['type'],
    		"name" => $data['name'],
            "description" => $data['description'],
            "options" => $data['options'],

    	], [ "id" => $data['id'] ]);
    	logSystem("License Custom Field Edited - ID: " . $data['id']);
    	return "20";
    }


    public static function deleteLicenseCF($id) {
    	global $database;
        $database->delete("licenses_customfields", [ "id" => $id ]);
    	logSystem("License Custom Field Deleted - ID: " . $id);
    	return "30";
    }

}

?>
