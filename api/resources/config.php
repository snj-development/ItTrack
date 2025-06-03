<?php
#################
## ticket_departments




switch ($request_method) {
    case 'get':
        isAuthorizedApi("manageSettings");

        if(empty($filters)) {
            $result = $database->select("config", "*");
        } else {
            $result = $database->select("config", "*", [ "AND" => $filters ]);
        }


        $response = [ "status" => 1, "status_message" => "Success!", "result" => $result ];
    break;




    default:
        $response = [ "status" => 907, "status_message" => "Request method " . $request_method . " not allowed for this resourse." ];
    break;
}





?>
