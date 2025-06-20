<?php

class Issue extends App {

    public static function add($data) {
    	global $database;
    	$lastid = $database->insert("issues", [
    		"clientid" => $data['clientid'],
    		"assetid" => $data['assetid'],
    		"projectid" => $data['projectid'],
    		"adminid" => $data['adminid'],
            "milestoneid" => 0,
    		"issuetype" => $data['issuetype'],
    		"priority" => $data['priority'],
    		"status" => $data['status'],
    		"name" => $data['name'],
    		"description" => $data['description'],
    		"duedate" => dateDb($data['duedate']),
    		"timespent" => 0,
    		"dateadded" => date("Y-m-d H:i:s"),
			"startdate" => dateDb($data['startdate'])
    	]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Issue Added - ID: " . $lastid); return "10"; }
    }

    public static function edit($data) {
    	global $database;
    	$database->update("issues", [
    		"clientid" => $data['clientid'],
    		"assetid" => $data['assetid'],
    		"projectid" => $data['projectid'],
    		"adminid" => $data['adminid'],
    		"issuetype" => $data['issuetype'],
    		"priority" => $data['priority'],
    		"status" => $data['status'],
    		"name" => $data['name'],
    		"description" => $data['description'],
			"startdate" => dateDb($data['startdate']),
    		"duedate" => dateDb($data['duedate']),
    	], [ "id" => $data['id'] ]);
    	logSystem("Issue Edited - ID: " . $data['id']);
    	return "20";
    }

    public static function delete($id) {
    	global $database;
        $database->delete("issues", [ "id" => $id ]);
    	logSystem("Issue Deleted - ID: " . $id);
    	return "30";
    }

    public static function updateStatus($id,$status) {
        global $database;
        $database->update("issues", [
            "status" => $status
        ], [ "id" => $id ]);
        return "20";
    }

}


?>
