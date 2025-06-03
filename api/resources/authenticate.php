<?php
#################
## authenticate




switch ($request_method) {
    case 'get':
        //isAuthorizedApi("manageData");

        if(empty($filters)) {
            $response = [ "status" => 0, "status_message" => "Authentication failure" ];
        } else {
            $peopleid = signInApi($filters['username'],$filters['password']);


            if($peopleid != 0) {
                $result = $database->get("people","*",["id" => $peopleid]);
                $response = [ "status" => 1, "status_message" => "Success!", "result" => $result ];
            } else {
                $response = [ "status" => 0, "status_message" => "Authentication failure" ];
            }
        }


    break;





    default:
        $response = [ "status" => 907, "status_message" => "Request method " . $request_method . " not allowed for this resourse." ];
    break;
}





?>
