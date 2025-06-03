<?php

##################################
###       QUICK ACTIONS        ###
##################################


switch($_GET['qa']) {

	case "ticketClose":
        Ticket::updateStatus($_GET['id'],"Closed");
        header("Location:?route=".$_GET['reroute']."&id=".$_GET['routeid']);
        break;

	case "ticketReopen":
        Ticket::updateStatus($_GET['id'],"Reopened");
        header("Location:?route=".$_GET['reroute']."&id=".$_GET['routeid']);
        break;

	case "ticketAssignToMe":
        Ticket::assignTo($_GET['id'],$liu['id']);
        header("Location:?route=".$_GET['reroute']."&id=".$_GET['routeid']);
        break;

	case "getTicketReply":
        echo getSingleValue("tickets_replies","message",$_GET['id']);
        break;

    case "getUserEmail":
        echo getSingleValue("people","email",$_GET['id']);
    break;

	case "getPReply":
		echo getSingleValue("tickets_pr","content",$_GET['id']);
	break;

	case "setAutorefresh":
        Profile::setAutorefresh($liu['id'],$_GET['autorefresh']);
        header("Location:?route=".$_GET['reroute']."&id=".$_GET['routeid']."&section=".$_GET['section']);
    break;

	case "removeAvatar":
        Profile::removeAvatar($liu['id']);
        header("Location:?route=profile");
    break;

    case "updateIssueStatus":
        Issue::updateStatus($_POST['issueid'],$_POST['status']);
    break;


    case "download":
        $file = getRowById("files",$_GET['id']);
        $targetfile = $scriptpath . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . $file['file'];
        if (file_exists($targetfile)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $file['file'] . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($targetfile));
            readfile($targetfile);
            exit;
            }
        else echo _e("File does not exist.");
	break;

	case "show":
        $file = getRowById("files",$_GET['id']);
        $targetfile = $scriptpath . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . $file['file'];
        if (file_exists($targetfile)) {
            $content = get_mime_content($file['file']);
            header('Content-Type: ' . $content);
            header('Content-Length: ' . filesize($targetfile));
            readfile($targetfile);
            exit;
        }
        else echo _e("File does not exist.");
	break;


	case "assetsSelect":
		$filterid = $_GET['filterid'];
		$searchstring = "";
		if(isset($_GET['q'])) $searchstring = $_GET['q'];

		if($searchstring != "") {
			if($filterid != 0) {
				$items = $database->select("assets", "*", [ "AND" => [
					"clientid" => $filterid,
					"OR" => [
						"tag[~]" => $searchstring,
						"name[~]" => $searchstring
					]
				]]);
			} else {
				$items = $database->select("assets", "*", [ "OR" => [
					"tag[~]" => $searchstring,
					"name[~]" => $searchstring
				]]);
			}
		} else {
			if($filterid != 0) {
				$items = $database->select("assets", "*", [ "clientid" => $filterid ]);
			} else {
				$items = $database->select("assets", "*");
			}
		}

		$results = array();
		$results[0]['id'] = 0;
		$results[0]['text'] = __('None');

		$i = 1;
		foreach($items as $item) {
			$results[$i]['id'] = $item['id'];
			$results[$i]['text'] = $item['tag'] . " " . $item['name'];
			$i++;
		}

		echo json_encode($results);
	break;


	case "projectsSelect":
		$filterid = $_GET['filterid'];
		$searchstring = "";
		if(isset($_GET['q'])) $searchstring = $_GET['q'];

		if($searchstring != "") {
			if($filterid != 0) {
				$items = $database->select("projects", "*", [ "AND" => [
					"clientid" => $filterid,
					"OR" => [
						"tag[~]" => $searchstring,
						"name[~]" => $searchstring
					]
				]]);
			} else {
				$items = $database->select("projects", "*", [ "OR" => [
					"tag[~]" => $searchstring,
					"name[~]" => $searchstring
				]]);
			}
		} else {
			if($filterid != 0) {
				$items = $database->select("projects", "*", [ "clientid" => $filterid ]);
			} else {
				$items = $database->select("projects", "*");
			}
		}

		$results = array();
		$results[0]['id'] = 0;
		$results[0]['text'] = __('None');

		$i = 1;
		foreach($items as $item) {
			$results[$i]['id'] = $item['id'];
			$results[$i]['text'] = $item['tag'] . " " . $item['name'];
			$i++;
		}

		echo json_encode($results);
	break;


	case "usersSelect":
		$filterid = $_GET['filterid'];
		$searchstring = "";
		if(isset($_GET['q'])) $searchstring = $_GET['q'];

		if($searchstring != "") {
			if($filterid != 0) {
				$items = $database->select("people", "*", [ "AND" => [
					"type" => "user",
					"clientid" => $filterid,
					"OR" => [
						"name[~]" => $searchstring,
						"email[~]" => $searchstring
					]
				]]);
			} else {
				$items = $database->select("people", "*", [ "AND" => [
					"type" => "user",
					"OR" => [
						"name[~]" => $searchstring,
						"email[~]" => $searchstring
					]
				]]);
			}
		} else {
			if($filterid != 0) {
				$items = $database->select("people", "*", [ "AND" => [ "clientid" => $filterid, "type" => "user" ] ]);
			} else {
				$items = $database->select("people", "*", [ "type" => "user" ]);
			}
		}

		$results = array();
		$results[0]['id'] = 0;
		$results[0]['text'] = __('None');

		$i = 1;
		foreach($items as $item) {
			$results[$i]['id'] = $item['id'];
			$results[$i]['text'] = $item['name'] . " (" . $item['email'] . ")";
			$i++;
		}

		echo json_encode($results);
	break;



	case "issuesSelect":
		$filterid = $_GET['filterid'];
		$searchstring = "";
		if(isset($_GET['q'])) $searchstring = $_GET['q'];

		if($searchstring != "") {
			if($filterid != 0) {
				$items = $database->select("issues", "*", [
					"AND" => [
						"clientid" => $filterid,
						"name[~]" => $searchstring
					]
				]);
			} else {
				$items = $database->select("issues", "*", [
					"name[~]" => $searchstring
				]);
			}
		} else {
			if($filterid != 0) {
				$items = $database->select("issues", "*", [ "clientid" => $filterid ]);
			} else {
				$items = $database->select("issues", "*");
			}
		}

		$results = array();

		$i = 0;
		foreach($items as $item) {
			$results[$i]['id'] = $item['id'];
			$results[$i]['text'] = $item['name'];
			$i++;
		}

		echo json_encode($results);
	break;


	case "ticketsSelect":
		$filterid = $_GET['filterid'];
		$searchstring = "";
		if(isset($_GET['q'])) $searchstring = $_GET['q'];

		if($searchstring != "") {
			if($filterid != 0) {
				$items = $database->select("tickets", "*", [ "AND" => [
					"clientid" => $filterid,
					"OR" => [
						"ticket[~]" => $searchstring,
						"subject[~]" => $searchstring
					]
				]]);
			} else {
				$items = $database->select("tickets", "*", [ "OR" => [
					"ticket[~]" => $searchstring,
					"subject[~]" => $searchstring
				]]);
			}
		} else {
			if($filterid != 0) {
				$items = $database->select("tickets", "*", [ "clientid" => $filterid ]);
			} else {
				$items = $database->select("tickets", "*");
			}
		}

		$results = array();

		$i = 0;
		foreach($items as $item) {
			$results[$i]['id'] = $item['id'];
			$results[$i]['text'] = "#" . $item['ticket'] . " " . $item['subject'];
			$i++;
		}

		echo json_encode($results);
	break;

	case "usersSelectEmail":
		$filterid = $_GET['filterid'];
		$searchstring = "";
		if(isset($_GET['q'])) $searchstring = $_GET['q'];

		if($searchstring != "") {
			if($filterid != 0) {
				$items = $database->select("people", "*", [ "AND" => [
					"type" => "user",
					"clientid" => $filterid,
					"OR" => [
						"name[~]" => $searchstring,
						"email[~]" => $searchstring
					]
				]]);
			} else {
				$items = $database->select("people", "*", [ "AND" => [
					"type" => "user",
					"OR" => [
						"name[~]" => $searchstring,
						"email[~]" => $searchstring
					]
				]]);
			}
		} else {
			if($filterid != 0) {
				$items = $database->select("people", "*", [ "AND" => [ "clientid" => $filterid, "type" => "user" ] ]);
			} else {
				$items = $database->select("people", "*", [ "type" => "user" ]);
			}
		}

		$results = array();

		$i = 0;
		foreach($items as $item) {
			$results[$i]['id'] = $item['email'];
			$results[$i]['text'] = $item['name'] . " (" . $item['email'] . ")";
			$i++;
		}

		echo json_encode($results);
	break;

	case "sssssssa":



} // end switch



?>
