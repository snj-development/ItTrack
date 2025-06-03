<?php
#################
## system_log




switch ($request_method) {
    case 'get':
        isAuthorizedApi("viewTime");

        if(empty($filters)) {
            $result = $database->select("systemlog", "*");
        } else {
            $result = $database->select("systemlog", "*", [ "AND" => $filters ]);
        }


        $response = [ "status" => 1, "status_message" => "Success!", "result" => $result ];
    break;

    case 'add':
        isAuthorizedApi("addTime");

        $status = logSystemApi($data);


        if($status == 10) $response = [ "status" => 1, "status_message" => "Success! Item has been added successfully." ];
        else $response = [ "status" => 2, "status_message" => "Error! Unable to add item." ];
    break;




    default:
        $response = [ "status" => 907, "status_message" => "Request method " . $request_method . " not allowed for this resourse." ];
    break;
}





?>
