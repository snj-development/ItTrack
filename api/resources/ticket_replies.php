<?php
#################
## tickets




switch ($request_method) {
    case 'get':
        isAuthorizedApi("viewTickets");

        if(empty($filters)) {
            $result = $database->select("tickets_replies", "*");
        } else {
            $result = $database->select("tickets_replies", "*", [ "AND" => $filters ]);
        }

        $i=0;
        foreach($result as $item) {
            $result[$i]['ccs'] = unserialize($item['ccs']);
            $i++;
        }

        $response = [ "status" => 1, "status_message" => "Success!", "result" => $result ];
    break;

    case 'add':
        isAuthorizedApi("addTicket");

        $status = Ticket::addReply($data);

        if($status == 10) $response = [ "status" => 1, "status_message" => "Success! Item has been added successfully." ];
        else $response = [ "status" => 2, "status_message" => "Error! Unable to add item." ];
    break;




    default:
        $response = [ "status" => 907, "status_message" => "Request method " . $request_method . " not allowed for this resourse." ];
    break;
}





?>
