<?php
#################
## files




switch ($request_method) {
    case 'get':
        isAuthorizedApi("manageData");

        if(empty($filters)) {
            $result = $database->select("files", "*");
        } else {
            $result = $database->select("files", "*", [ "AND" => $filters ]);
        }

        $response = [ "status" => 1, "status_message" => "Success!", "result" => $result ];
    break;

    case 'add':
        isAuthorizedApi("manageData");

        $status = File::upload($data,$_FILES);

        if($status == 9500) $response = [ "status" => 1, "status_message" => "Success! Item has been added successfully." ];
        else $response = [ "status" => 2, "status_message" => "Error! Unable to add item." ];
    break;


    case 'delete':
        isAuthorizedApi("manageData");

        $status = File::delete($id);

        if($status == 9503) $response = [ "status" => 1, "status_message" => "Success! Item has been delete successfully." ];
        else $response = [ "status" => 2, "status_message" => "Error! Unable to delete item." ];
    break;



    default:
        $response = [ "status" => 907, "status_message" => "Request method " . $request_method . " not allowed for this resourse." ];
    break;
}





?>
