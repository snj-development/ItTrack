<?php
#################
## custom_asset_fields




switch ($request_method) {
    case 'get':
        isAuthorizedApi("viewCustomFields");

        if(empty($filters)) {
            $result = $database->select("assets_customfields", "*");
        } else {
            $result = $database->select("assets_customfields", "*", [ "AND" => $filters ]);
        }


        $response = [ "status" => 1, "status_message" => "Success!", "result" => $result ];
    break;




    default:
        $response = [ "status" => 907, "status_message" => "Request method " . $request_method . " not allowed for this resourse." ];
    break;
}





?>
