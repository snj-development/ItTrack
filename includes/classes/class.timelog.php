<?php

class Timelog extends App {


    public static function add($data) {
    	global $database;

    	$lastid = $database->insert("timelog", [
            "staffid" => $data['staffid'],
            "clientid" => $data['clientid'],
    		"projectid" => $data['projectid'],
            "assetid" => $data['assetid'],
            "issues" => serialize($data['issues']),
            "tickets" => serialize($data['tickets']),
    		"description" => $data['description'],
    		"date" => dateDb($data['date']),
            "start" => $data['start'],
            "end" => $data['end'],
    	]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Time Log Entry Added - ID: " . $lastid); return "10"; }
    }

    public static function edit($data) {
    	global $database;

    	$database->update("timelog", [
            "clientid" => $data['clientid'],
    		"projectid" => $data['projectid'],
            "assetid" => $data['assetid'],
            "issues" => serialize($data['issues']),
            "tickets" => serialize($data['tickets']),
    		"description" => $data['description'],
    		"date" => dateDb($data['date']),
            "start" => $data['start'],
            "end" => $data['end'],
    	], [ "id" => $data['id'] ]);
    	logSystem("Time Log Entry Edited - ID: " . $data['id']);
    	return "20";
    }

    public static function delete($id) {
    	global $database;
        $database->delete("timelog", [ "id" => $id ]);
    	logSystem("Time Log Entry Deleted - ID: " . $id);
    	return "30";
    }

    public static function release($data) {
    	global $database;

        $milestone = getRowById("milestones",$data['milestoneid']);
        $project = getRowById("projects",$data['projectid']);

        $issues = $database->select("issues", "*", [ "AND" => ["status" => "Done", "projectid" => $project['id'], "milestoneid" => 0] ]);

        foreach ($issues as $issue) {
            $database->update("issues", [
                "milestoneid" => $milestone['id']
            ], [ "id" => $issue['id'] ]);
        }


    	logSystem("Milestone Released - ID: " . $data['id']);
    	return "20";
    }


}


?>
