<?php

class License extends App {


    public static function add($data) {
    	global $database;

        $customfields = getTable("licenses_customfields");
        $customfieldsdata = array();

        foreach ($customfields as $customfield) {
            $customfieldsdata[$customfield['id']] = $data[$customfield['id']];
        }


    	$lastid = $database->insert("licenses", [
    		"clientid" => $data['clientid'],
    		"statusid" => $data['statusid'],
    		"categoryid" => $data['categoryid'],
			"supplierid" => $data['supplierid'],
			"seats" => $data['seats'],
    		"tag" => $data['tag'],
    		"name" => $data['name'],
    		"serial" => encrypt_decrypt('encrypt', $data['serial']),
    		"notes" => $data['notes'],
            "customfields" => serialize($customfieldsdata),
            "qrvalue" => $data['qrvalue'],
    	]);
    	if ($lastid == "0") { return "11"; } else { logSystem("License Added - ID: " . $lastid); return "10"; }
    }


    public static function edit($data) {
    	global $database;

        $customfields = getTable("licenses_customfields");
        $customfieldsdata = array();

        foreach ($customfields as $customfield) {
            $customfieldsdata[$customfield['id']] = $data[$customfield['id']];
        }

    	$database->update("licenses", [
    		"clientid" => $data['clientid'],
    		"statusid" => $data['statusid'],
    		"categoryid" => $data['categoryid'],
			"supplierid" => $data['supplierid'],
			"seats" => $data['seats'],
    		"tag" => $data['tag'],
    		"name" => $data['name'],
    		"serial" => encrypt_decrypt('encrypt', $data['serial']),
    		"notes" => $data['notes'],
            "customfields" => serialize($customfieldsdata),
            "qrvalue" => $data['qrvalue'],
    	], [ "id" => $data['id'] ]);
    	logSystem("License Edited - ID: " . $data['id']);
    	return "20";
    }

    public static function delete($id) {
    	global $database;
        $database->delete("licenses", [ "id" => $id ]);
    	logSystem("License Deleted - ID: " . $id);
    	return "30";
    }

    public static function nextLicenseTag() {
    	global $database;
        $max = $database->max("licenses", "id");
    	return $max+1;
    }


    public static function assignAsset($data) { //assign license to asset
    	global $database;
    	$lastid = $database->insert("licenses_assets", [
    		"licenseid" => $data['licenseid'],
    		"assetid" => $data['assetid']
    	]);
    	if ($lastid == "0") { return "11"; } else { return "10"; }
    }

    public static function unassignAsset($id) { //unassign license to asset
    	global $database;
        $database->delete("licenses_assets", [ "id" => $id ]);
    	return "30";
	}


	public static function countUsedSeats($id) { //unassign license to asset
    	global $database;
        return $database->count("licenses_assets", [ "licenseid" => $id ] );
    }

}


?>
