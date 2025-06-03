<?php
#################
## roles




switch ($request_method) {
    case 'get':
        isAuthorizedApi("viewRoles");

        if(empty($filters)) {
            $result = $database->select("roles", "*");
        } else {
            $result = $database->select("roles", "*", [ "AND" => $filters ]);
        }

        $i=0;
        foreach($result as $item) {
            $result[$i]['perms'] = unserialize($item['perms']);
            $i++;
        }

        $response = [ "status" => 1, "status_message" => "Success!", "result" => $result ];
    break;




    default:
        $response = [ "status" => 907, "status_message" => "Request method " . $request_method . " not allowed for this resourse." ];
    break;
}





?>
