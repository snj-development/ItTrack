<?php

class Milestone extends App {


    public static function add($data) {
    	global $database;

    	$lastid = $database->insert("milestones", [
    		"projectid" => $data['projectid'],
    		"name" => $data['name'],
    		"duedate" => dateDb($data['duedate']),
            "description" => $data['description'],
    	]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Milestone Added - ID: " . $lastid); return "10"; }
    }

    public static function edit($data) {
    	global $database;

    	$database->update("milestones", [
    		"name" => $data['name'],
    		"duedate" => dateDb($data['duedate']),
            "description" => $data['description'],
    	], [ "id" => $data['id'] ]);
    	logSystem("Milestone Edited - ID: " . $data['id']);
    	return "20";
    }

    public static function delete($id) {
    	global $database;
        $database->delete("milestones", [ "id" => $id ]);
    	logSystem("Project Deleted - ID: " . $id);
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
