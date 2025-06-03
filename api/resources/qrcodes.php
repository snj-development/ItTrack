<?php
#################
## manufacturers




switch ($request_method) {
    case 'get':
        isAuthorizedApi("manageData");

        if(empty($filters)) {
            $result = $database->select("qrcodes", "*");
        } else {
            $result = $database->select("qrcodes", "*", [ "AND" => $filters ]);
        }

        $response = [ "status" => 1, "status_message" => "Success!", "result" => $result ];
    break;

    case 'edit':
        isAuthorizedApi("manageData");

        $status = Attribute::editQrcode($data);

        if($status == 20) $response = [ "status" => 1, "status_message" => "Success! Item has been updated successfully." ];
        else $response = [ "status" => 2, "status_message" => "Error! Unable to update item." ];
    break;


    case 'delete':
        isAuthorizedApi("manageData");

        $status = Attribute::deleteQrcode($id);

        if($status == 30) $response = [ "status" => 1, "status_message" => "Success! Item has been deleted successfully." ];
        else $response = [ "status" => 2, "status_message" => "Error! Unable to delete item." ];
    break;


    case 'attach':
        isAuthorizedApi("manageData");

        $status = Attribute::attachQrcode($data);

        if($status == "10") $response = [ "status" => 1, "status_message" => "Success! Item has been attached successfully." ];
        elseif($status == "11") $response = [ "status" => 2, "status_message" => "Error! Unable to attach item. Make sure the QR code is generated and free." ];
        else $response = [ "status" => 2, "status_message" => "Error! Unable to attach item." ];
    break;

    case 'detach':
        isAuthorizedApi("manageData");

        $status = Attribute::detachQrcode($id);

        if($status == 30) $response = [ "status" => 1, "status_message" => "Success! Item has been detached successfully." ];
        else $response = [ "status" => 2, "status_message" => "Error! Unable to detach item." ];
    break;


    default:
        $response = [ "status" => 907, "status_message" => "Request method " . $request_method . " not allowed for this resourse." ];
    break;
}





?>
