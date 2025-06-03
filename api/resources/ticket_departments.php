<?php
#################
## ticket_departments




switch ($request_method) {
    case 'get':
        isAuthorizedApi("viewTickets");

        if(empty($filters)) {
            $result = $database->select("tickets_departments", "*");
        } else {
            $result = $database->select("tickets_departments", "*", [ "AND" => $filters ]);
        }


        $response = [ "status" => 1, "status_message" => "Success!", "result" => $result ];
    break;




    default:
        $response = [ "status" => 907, "status_message" => "Request method " . $request_method . " not allowed for this resourse." ];
    break;
}





?>
