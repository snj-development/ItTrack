<?php
#################
## languages




switch ($request_method) {
    case 'get':
        isAuthorizedApi("manageSettings");

        if(empty($filters)) {
            $result = $database->select("languages", "*");
        } else {
            $result = $database->select("languages", "*", [ "AND" => $filters ]);
        }


        $response = [ "status" => 1, "status_message" => "Success!", "result" => $result ];
    break;




    default:
        $response = [ "status" => 907, "status_message" => "Request method " . $request_method . " not allowed for this resourse." ];
    break;
}





?>
