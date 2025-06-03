<?php

class Attribute extends App {

    // ----------------------------------------------------------------------------------------------
    // CATEGORIES

    public static function addAssetCategory($data) {
    	global $database;
        if(!isset($data['color'])) $data['color'] = "#167cc1";
    	$lastid = $database->insert("assetcategories", [ "name" => $data['name'], "color" => $data['color'] ]);
    	if ($lastid == "0") { return "11"; } else { logSystem("AssetCategory Added - ID: " . $lastid); return "10"; }
    	}

    public static function editAssetCategory($data) {
    	global $database;
        if(!isset($data['color'])) $data['color'] = "#167cc1";
    	$database->update("assetcategories", [ "name" => $data['name'], "color" => $data['color'] ], [ "id" => $data['id'] ]);
    	logSystem("AssetCategory Edited - ID: " . $data['id']);
    	return "20";
    	}

    public static function deleteAssetCategory($id) {
    	global $database;
        $database->delete("assetcategories", [ "id" => $id ]);
    	logSystem("AssetCategory Deleted - ID: " . $id);
    	return "30";
    	}



    // ----------------------------------------------------------------------------------------------
    // LICENSE TYPES

    public static function addLicenseCategory($data) {
    	global $database;
        if(!isset($data['color'])) $data['color'] = "#167cc1";
    	$lastid = $database->insert("licensecategories", [ "name" => $data['name'], "color" => $data['color'] ]);
    	if ($lastid == "0") { return "11"; } else {logSystem("LicenseCategory Added - ID: " . $lastid);  return "10"; }
    	}

    public static function editLicenseCategory($data) {
    	global $database;
        if(!isset($data['color'])) $data['color'] = "#167cc1";
    	$database->update("licensecategories", [ "name" => $data['name'], "color" => $data['color'] ], [ "id" => $data['id'] ]);
    	logSystem("LicenseCategory Edited - ID: " . $data['id']);
    	return "20";
    	}

    public static function deleteLicenseCategory($id) {
    	global $database;
        $database->delete("licensecategories", [ "id" => $id ]);
    	logSystem("LicenseCategory Deleted - ID: " . $id);
    	return "30";
    	}

    // ----------------------------------------------------------------------------------------------
    // ASSET STATES

    public static function addLabel($data) {
    	global $database;
        if(!isset($data['color'])) $data['color'] = "#167cc1";
    	$lastid = $database->insert("labels", [ "name" => $data['name'], "color" => $data['color'] ]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Label Added - ID: " . $lastid); return "10"; }
    	}

    public static function editLabel($data) {
    	global $database;
        if(!isset($data['color'])) $data['color'] = "#167cc1";
    	$database->update("labels", [ "name" => $data['name'], "color" => $data['color'] ], [ "id" => $data['id'] ]);
    	logSystem("Label Edited - ID: " . $data['id']);
    	return "20";
    	}

    public static function deleteLabel($id) {
    	global $database;
        $database->delete("labels", [ "id" => $id ]);
    	logSystem("Label Deleted - ID: " . $id);
    	return "30";
    	}

    // ----------------------------------------------------------------------------------------------
    // MANUFACTURERS

    public static function addManufacturer($data) {
    	global $database;
    	$lastid = $database->insert("manufacturers", [ "name" => $data['name'] ]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Manufacturer Added - ID: " . $lastid); return "10"; }
    	}

    public static function editManufacturer($data) {
    	global $database;
    	$database->update("manufacturers", [ "name" => $data['name'] ], [ "id" => $data['id'] ]);
    	logSystem("Manufacturer Edited - ID: " . $data['id']);
    	return "20";
    	}

    public static function deleteManufacturer($id) {
    	global $database;
        $database->delete("manufacturers", [ "id" => $id ]);
    	logSystem("Manufacturer Deleted - ID: " . $id);
    	return "30";
    	}



    // ----------------------------------------------------------------------------------------------
    // LOCATIONS

    public static function addLocation($data) {
    	global $database;
    	$lastid = $database->insert("locations", [ "name" => $data['name'], "clientid" => $data['clientid'] ]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Location Added - ID: " . $lastid); return "10"; }
    	}

    public static function editLocation($data) {
    	global $database;
    	$database->update("locations", [ "name" => $data['name'], "clientid" => $data['clientid'] ], [ "id" => $data['id'] ]);
    	logSystem("Location Edited - ID: " . $data['id']);
    	return "20";
    	}

    public static function deleteLocation($id) {
    	global $database;
        $database->delete("locations", [ "id" => $id ]);
    	logSystem("Location Deleted - ID: " . $id);
    	return "30";
    	}


    // ----------------------------------------------------------------------------------------------
    // ASSET MODELS

    public static function addModel($data) {
    	global $database;
    	$lastid = $database->insert("models", [ "name" => $data['name'] ]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Model Added - ID: " . $lastid); return "10"; }
    	}

    public static function editModel($data) {
    	global $database;
    	$database->update("models", [ "name" => $data['name'] ], [ "id" => $data['id'] ]);
    	logSystem("Model Edited - ID: " . $data['id']);
    	return "20";
    	}

    public static function deleteModel($id) {
    	global $database;
        $database->delete("models", [ "id" => $id ]);
    	logSystem("Model Deleted - ID: " . $id);
    	return "30";
    	}


    // ----------------------------------------------------------------------------------------------
    // SUPPLIERS

    public static function addSupplier($data) {
    	global $database;
    	$lastid = $database->insert("suppliers", [
    		"name" => $data['name'],
    		"address" => $data['address'],
    		"contactname" => $data['contactname'],
    		"phone" => $data['phone'],
    		"email" => $data['email'],
    		"web" => $data['web'],
    		"notes" => $data['notes']
    	]);
    	if ($lastid == "0") { return "11"; } else { logSystem("Supplier Added - ID: " . $lastid); return "10"; }
    	}


    public static function editSupplier($data) {
    	global $database;
    	$database->update("suppliers", [
    		"name" => $data['name'],
    		"address" => $data['address'],
    		"contactname" => $data['contactname'],
    		"phone" => $data['phone'],
    		"email" => $data['email'],
    		"web" => $data['web'],
    		"notes" => $data['notes']
    	], [ "id" => $data['id'] ]);
    	logSystem("Supplier Edited - ID: " . $data['id']);
    	return "20";
    	}


    public static function deleteSupplier($id) {
    	global $database;
        $database->delete("suppliers", [ "id" => $id ]);
    	logSystem("Supplier Deleted - ID: " . $id);
    	return "30";
    	}






    // ----------------------------------------------------------------------------------------------
    // QR CODES

    public static function generateQrcodes($data) {
    	global $database;
        global $date;

        $ids = array();

        for ($x = 1; $x <= $data['count']; $x++) {
            $current_id = $database->insert("qrcodes", [ "value" => randomString($data['length']) ]);
            array_push($ids, $current_id);
        }

        $lastid = $database->insert("qrcodes_batches", [ "date" => $date, "ids" => serialize($ids) ]);


    	if ($lastid == "0") { return "11"; } else { logSystem("QR Codes Generated - ID: " . $lastid); return "10"; }
    	}




    public static function editQrcode($data) {
    	global $database;

        $qrcode = getRowById("qrcodes",$data['id']);

    	$database->update("qrcodes", [ "value" => $data['value'] ], [ "id" => $data['id'] ]);

        $database->update("assets", [ "qrvalue" => $data['value'] ], [ "qrvalue" => $qrcode['value'] ]);
        $database->update("licenses", [ "qrvalue" => $data['value'] ], [ "qrvalue" => $qrcode['value'] ]);

    	logSystem("QR Code Edited - ID: " . $data['id']);
    	return "20";
    	}

    public static function deleteQrcode($id) {
    	global $database;

        $qrcode = getRowById("qrcodes",$data['id']);

        $database->delete("qrcodes", [ "id" => $id ]);

        $database->update("assets", [ "qrvalue" => "" ], [ "qrvalue" => $qrcode['value'] ]);
        $database->update("licenses", [ "qrvalue" => "" ], [ "qrvalue" => $qrcode['value'] ]);

    	logSystem("QR Code Deleted - ID: " . $id);
    	return "30";
    	}

    public static function deleteBatch($id) {
    	global $database;

        $database->delete("qrcodes_batches", [ "id" => $id ]);
        
    	logSystem("QR Code Batch Deleted - ID: " . $id);
    	return "30";
    	}


    public static function attachQrcode($data) {
    	global $database;

        if(isset($data['id'])) {
            $qrcode = getRowById("qrcodes",$data['id']);
        }

        if(isset($data['value'])) {
            $qrcode = $database->get("qrcodes", "*", ["value" => $data['value']]);
        }


        if(!empty($qrcode)) {
            if($database->has("assets", ["qrvalue" => $qrcode['value']])) return "11";
            elseif($database->has("licenses", ["qrvalue" => $qrcode['value']])) return "11";

            else {
                if($data['asset_id'] != 0) {
                    $database->update("assets", [ "qrvalue" => $qrcode['value'] ], [ "id" => $data['asset_id'] ]);

                }  elseif($data['license_id'] != 0) {
                    $database->update("licenses", [ "qrvalue" => $qrcode['value'] ], [ "id" => $data['license_id'] ]);
                }



                logSystem("QR Code Edited - ID: " . $qrcode['id']);
                return "10";
            }
        }
        else return "11";



    	}

    public static function detachQrcode($id) {
    	global $database;

        $qrcode = getRowById("qrcodes",$id);

        $database->update("assets", [ "qrvalue" => "" ], [ "qrvalue" => $qrcode['value'] ]);
        $database->update("licenses", [ "qrvalue" => "" ], [ "qrvalue" => $qrcode['value'] ]);

    	logSystem("QR Code Detached - ID: " . $id);
    	return "20";
    	}






}

?>
