<?php
#################
## monitoring_checks




switch ($request_method) {
    case 'get':
        isAuthorizedApi("viewMonitoring");

        if(empty($filters)) {
            $result = $database->select("hosts_checks", "*");
        } else {
            $result = $database->select("hosts_checks", "*", [ "AND" => $filters ]);
        }

        $response = [ "status" => 1, "status_message" => "Success!", "result" => $result ];
    break;

    case 'add':
        isAuthorizedApi("manageHost");

        $status = Monitoring::addCheck($data);

        if($status == 10) $response = [ "status" => 1, "status_message" => "Success! Item has been added successfully." ];
        else $response = [ "status" => 2, "status_message" => "Error! Unable to add item." ];
    break;

    case 'edit':
        isAuthorizedApi("manageHost");

        $status = Monitoring::editCheck($data);

        if($status == 20) $response = [ "status" => 1, "status_message" => "Success! Item has been updated successfully." ];
        else $response = [ "status" => 2, "status_message" => "Error! Unable to update item." ];
    break;

    case 'delete':
        isAuthorizedApi("manageHost");

        $status = Monitoring::deleteCheck($id);

        if($status == 30) $response = [ "status" => 1, "status_message" => "Success! Item has been delete successfully." ];
        else $response = [ "status" => 2, "status_message" => "Error! Unable to delete item." ];
    break;



    default:
        $response = [ "status" => 907, "status_message" => "Request method " . $request_method . " not allowed for this resourse." ];
    break;
}





?>
