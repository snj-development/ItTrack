<?php
#################
## kb_articles




switch ($request_method) {
    case 'get':
        isAuthorizedApi("viewKB");

        if(empty($filters)) {
            $result = $database->select("kb_articles", "*");
        } else {
            $result = $database->select("kb_articles", "*", [ "AND" => $filters ]);
        }

        $i=0;
        foreach($result as $item) {
            $result[$i]['clients'] = unserialize($item['clients']);
            $i++;
        }

        $response = [ "status" => 1, "status_message" => "Success!", "result" => $result ];
    break;

    case 'add':
        isAuthorizedApi("addKB");

        $status = Kb::add($data);

        if($status == 10) $response = [ "status" => 1, "status_message" => "Success! Item has been added successfully." ];
        else $response = [ "status" => 2, "status_message" => "Error! Unable to add item." ];
    break;

    case 'edit':
        isAuthorizedApi("editKB");

        $status = Kb::edit($data);

        if($status == 20) $response = [ "status" => 1, "status_message" => "Success! Item has been updated successfully." ];
        else $response = [ "status" => 2, "status_message" => "Error! Unable to update item." ];
    break;

    case 'delete':
        isAuthorizedApi("deleteKB");

        $status = Kb::delete($id);

        if($status == 30) $response = [ "status" => 1, "status_message" => "Success! Item has been delete successfully." ];
        else $response = [ "status" => 2, "status_message" => "Error! Unable to delete item." ];
    break;



    default:
        $response = [ "status" => 907, "status_message" => "Request method " . $request_method . " not allowed for this resourse." ];
    break;
}





?>
