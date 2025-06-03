<?php

class Import extends App {


    public static function assets($data, $file) {
    	global $database;
		
		$customfields = getTable("assets_customfields");
		$customfieldsdata = array();
		
		$csv = fopen($file["file"]["tmp_name"],"r");
		$filename = pathinfo($file['file']['name'], PATHINFO_FILENAME);
		$lineindex = 0;


		while( ($item = fgetcsv($csv, 0, ",", '"') ) !== FALSE ) {
			
			if($lineindex == 0) { 
				//skip first line
				$lineindex++; 
				continue; 
			}

			$clientid = self::dataMatcher("client", $item[0]);

			$categoryid = self::dataMatcher("asset_category", $item[1], $clientid);
			$adminid = self::dataMatcher("admin", $item[2], $clientid);
			$userid = self::dataMatcher("user", $item[3], $clientid);
			$manufacturerid = self::dataMatcher("manufacturer", $item[4], $clientid);
			$modelid = self::dataMatcher("model", $item[5], $clientid);
			$supplierid = self::dataMatcher("supplier", $item[6], $clientid);
			$statusid = self::dataMatcher("status", $item[7], $clientid);
			$tag = self::dataMatcher("asset_tag", $item[10], $clientid);
			$locationid = self::dataMatcher("location", $item[14], $clientid);

			$itemindex = 15;
			foreach ($customfields as $customfield) {
				if(isset($item[$itemindex])) {
					$customfieldsdata[$customfield['id']] = $item[$itemindex];
				}
				$itemindex++;
			}

			$lastid = $database->insert("assets", [
				"categoryid" => $categoryid,
				"adminid" => $adminid,
				"clientid" => $clientid,
				"userid" => $userid,
				"manufacturerid" => $manufacturerid,
				"modelid" => $modelid,
				"supplierid" => $supplierid,
				"statusid" => $statusid,
				"purchase_date" => dateDb($item[8]),
				"warranty_months" => $item[9],
				"tag" => $tag,
				"name" => $item[11],
				"serial" => $item[12],
				"notes" => $item[13],
				"locationid" => $locationid,
				"customfields" => serialize($customfieldsdata),
	
			]);


			$lineindex++;
		}


    }




    public static function licenses($data, $file) {
    	global $database;
		
		$customfields = getTable("licenses_customfields");
		$customfieldsdata = array();
		
		$csv = fopen($file["file"]["tmp_name"],"r");
		$filename = pathinfo($file['file']['name'], PATHINFO_FILENAME);
		$lineindex = 0;


		while( ($item = fgetcsv($csv, 0, ",", '"') ) !== FALSE ) {
			
			if($lineindex == 0) { 
				//skip first line
				$lineindex++; 
				continue; 
			}

			$clientid = self::dataMatcher("client", $item[0]);
			$categoryid = self::dataMatcher("license_category", $item[2], $clientid);
			$supplierid = self::dataMatcher("supplier", $item[3], $clientid);
			$statusid = self::dataMatcher("status", $item[1], $clientid);
			$tag = self::dataMatcher("license_tag", $item[5], $clientid);

			$itemindex = 9;
			foreach ($customfields as $customfield) {
				if(isset($item[$itemindex])) {
					$customfieldsdata[$customfield['id']] = $item[$itemindex];
				}
				$itemindex++;
			}

			$lastid = $database->insert("licenses", [
				"clientid" => $clientid,
				"statusid" => $statusid,
				"categoryid" => $categoryid,
				"supplierid" => $supplierid,
				"seats" => $item[4],
				"tag" => $tag,
				"name" => $item[6],
				"serial" => encrypt_decrypt('encrypt', $item[7]),
				"notes" => $item[8],
				"customfields" => serialize($customfieldsdata),
			]);


			$lineindex++;
		}


    }



	public static function assetsSample() {
		global $database;

		$customfields = getTable("assets_customfields");
		
		header('Content-Type: application/excel');
		header('Content-Disposition: attachment; filename="sample.csv"');
		
		$output = fopen('php://output', 'w');

		$header = [
			"Client", // 0
			"Category", // 1###
			"Asset Admin Email Address", // 2
			"Asset User Email Address", // 3
			"Manufacturer", //4
			"Model", //5
			"Supplier", //6
			"Status", //7
			"Purchase Date", //8
			"Warranty Months", //9
			"Tag", //10 ###
			"Name", //11 ###
			"Serial", //12
			"Notes", //13
			"Location", //14
		];

		foreach ($customfields as $customfield) {
			array_push($header, $customfield['name']);
        }
	
	
		fputcsv($output, $header, ",");



		fclose($output);

	}

	public static function licensesSample() {
		global $database;

		$customfields = getTable("licenses_customfields");
		
		header('Content-Type: application/excel');
		header('Content-Disposition: attachment; filename="sample.csv"');
		
		$output = fopen('php://output', 'w');

		$header = [
			"Client", //0
			"Status", //1
			"Category", //2
			"Supplier", //3
			"Seats", //4
			"Tag", //5
			"Name", //6
			"Serial", //7
			"Notes", //8
		];

		foreach ($customfields as $customfield) {
			array_push($header, $customfield['name']);
        }
	
		fputcsv($output, $header, ",");

		fclose($output);
	}


	private static function dataMatcher($type, $value, $clientid=0) {
		global $database;
		$result = 0;


		if($type == "client") {
			$client = $database->get("clients", "*", ["name" => $value]);

			if(!empty($client)) { $result = $client['id']; }
			else {
				$result = $database->insert("clients", [
					"name" => $value,
					"asset_tag_prefix" => getConfigValue("asset_tag_prefix"),
					"license_tag_prefix" => getConfigValue("license_tag_prefix"),
					"notes" => "",
				]);
			}
		}


		if($type == "asset_category") {
			$category = $database->get("assetcategories", "*", ["name" => $value]);

			if(!empty($category)) { $result = $category['id']; }
			else {
				$result = $database->insert("assetcategories", [
					"name" => $value,
					"color" => rand_color(),
				]);
			}
		}

		if($type == "license_category") {
			$category = $database->get("licensecategories", "*", ["name" => $value]);

			if(!empty($category)) { $result = $category['id']; }
			else {
				$result = $database->insert("licensecategories", [
					"name" => $value,
					"color" => rand_color(),
				]);
			}
		}

		if($type == "asset_tag") {

			if($value != "") $result = $value;
			else {
				if($clientid == 0) {
					$result = getConfigValue("asset_tag_prefix") . Asset::nextAssetTag();
				}
				else {
					$client = $database->get("clients", "*", ["id" => $clientid]);
					$result = $client['asset_tag_prefix'] . Asset::nextAssetTag();
				}
			}
			
		}

		if($type == "license_tag") {

			if($value != "") $result = $value;
			else {
				if($clientid == 0) {
					$result = getConfigValue("license_tag_prefix") . License::nextLicenseTag();
				}
				else {
					$client = $database->get("clients", "*", ["id" => $clientid]);
					$result = $client['license_tag_prefix'] . License::nextLicenseTag();
				}
			}
			
		}


		if($type == "admin") {
			$admin = $database->get("people", "*", ["email" => strtolower($value)]);

			if(!empty($admin)) $result = $admin['id'];
			else $result = 0;
		}

		if($type == "user") {
			$user = $database->get("people", "*", ["email" => strtolower($value)]);

			if(!empty($user)) $result = $user['id'];
			else $result = 0;
		}


		if($type == "manufacturer") {
			$manufacturer = $database->get("manufacturers", "*", ["name" => $value]);

			if(!empty($manufacturer)) { $result = $manufacturer['id']; }
			else {
				$result = $database->insert("manufacturers", [
					"name" => $value,
				]);
			}
		}


		if($type == "model") {
			$model = $database->get("models", "*", ["name" => $value]);

			if(!empty($model)) { $result = $model['id']; }
			else {
				$result = $database->insert("models", [
					"name" => $value,
				]);
			}
		}


		if($type == "supplier") {
			$supplier = $database->get("suppliers", "*", ["name" => $value]);

			if(!empty($supplier)) { $result = $supplier['id']; }
			else {
				$result = $database->insert("suppliers", [
					"name" => $value,
					"address" => "", "contactname" => "", "phone" => "", "email" => "", "web" => "", "notes" => ""
				]);
			}
		}


		if($type == "status") {
			$status = $database->get("labels", "*", ["name" => $value]);

			if(!empty($status)) { $result = $status['id']; }
			else {
				$result = $database->insert("labels", [
					"name" => $value,
					"color" => rand_color(),
				]);
			}
		}

		if($type == "location") {
			$location = $database->get("locations", "*", [ "AND" => ["name" => $value, "clientid" => $clientid] ]  );

			if(!empty($location)) { $result = $location['id']; }
			else {
				$result = $database->insert("locations", [
					"clientid" => $clientid,
					"name" => $value,
				]);
			}
		}


	




		return $result;
	}


}

?>
