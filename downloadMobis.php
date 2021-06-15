<?php
/*
	
'    _____                      _                 _           _____          __     
'   |  __ \                    | |               | |         / ____|        / _|    
'   | |  | | _____      ___ __ | | ___   __ _  __| | ___ _ _| (_____      _| |_ ___ 
'   | |  | |/ _ \ \ /\ / / '_ \| |/ _ \ / _` |/ _` |/ _ \ '__\___ \ \ /\ / /  _/ __|
'   | |__| | (_) \ V  V /| | | | | (_) | (_| | (_| |  __/ |  ____) \ V  V /| | \__ \
'   |_____/ \___/ \_/\_/ |_| |_|_|\___/ \__,_|\__,_|\___|_| |_____/ \_/\_/ |_| |___/  Habbos and genereting xml's.
								Make by Laxus.
						Contact: corplaxus@gmail.com || Discord: laxus#3602
*/

	$furnidata = 'lella/furnidata.xml';

	$xmlHotel = simplexml_load_file($furnidata);

	$urlMobis = "https://habblize.com/swf/hof_furni/"; //link hof_furni with / 
	$urlMobis_icon = "https://habblize.com/swf/hof_furni/icons/"; //link of icons with /

	$nameswfFolder = "test"; // define the name of the folder that will save the swfs
	$nameIconFolder = "icons"; // define the name of the folder that will save the icons

	$pushMobis = array();
	$arrayToXml = array();

	$type = (isset($_GET['type'])) ? $_GET['type'] : '';
	

	if($type !== null) {
			
		if($type === "all") {
			for($i = 0; $i < count($xmlHotel->roomitemtypes->furnitype); $i++) {
				$newMobi = $xmlHotel->roomitemtypes->furnitype[$i]["classname"];
		
				$arrayPush = array_push($pushMobis, $newMobi);
			}
		
			//making downloads -> 
		
			for($x = 0; $x < count($pushMobis); $x++) {
				
				$contentMobis = file_get_contents($urlMobis . $pushMobis[$x] . '.swf');
				$contentIcons = file_get_contents($urlMobis_icon . $pushMobis[$x] . '_icon.png');
				
				//swfs
				if (is_dir($nameswfFolder) && is_dir($nameIconFolder)) {
 
					if (file_put_contents($nameswfFolder . $pushMobis[$x] . '.swf', $contentMobis) == true) {
						continue;
					} else {
						file_put_contents($nameswfFolder . $pushMobis[$x] . '.swf', $contentMobis);
					}


					//icons
					if (file_put_contents($nameswfFolder . '/' . $nameIconFolder . '/' . $pushMobis[$x] . '_icon.png', $contentIcons) == true) {
						continue;
					} else {
						file_put_contents($nameswfFolder . '/' . $nameIconFolder . '/' . $pushMobis[$x] . '_icon.png', $contentIcons);
					}

				} else {
					//create folders if not exists
					mkdir(__DIR__ . '/' . $nameswfFolder . '/', 0777, true);
					mkdir(__DIR__ . '/' . $nameswfFolder . '/' . $nameIconFolder . '/', 0777, true);

					if (file_put_contents($nameswfFolder . $pushMobis[$x] . '.swf', $contentMobis) == true) {
						continue;
					} else {
						file_put_contents($nameswfFolder . $pushMobis[$x] . '.swf', $contentMobis);
					}


					//icons
					if(file_put_contents($nameswfFolder . '/' . $nameIconFolder . '/' . $pushMobis[$x] . '_icon.png', $contentIcons) == true) {
						continue;
					} else {
						file_put_contents($nameswfFolder . '/' . $nameIconFolder . '/' . $pushMobis[$x] . '_icon.png', $contentIcons);
					}
				}	
			
			}
		
			for($z = 0; $z < count($xmlHotel->roomitemtypes->furnitype); $z++) {
				$xml = $xmlHotel->roomitemtypes->furnitype[$z];
		
				$arrayPush = array_push($arrayToXml, $xml);
			}
		
			//Genereting XML
			for($x = 0; $x < count($arrayToXml); $x++) {
				$generateXml = 
				"<furnitype id='" . $arrayToXml[$x]["id"] . "' classname='". $arrayToXml[$x]["classname"] . "'>
				<revision>" . $arrayToXml[$x]->revision . "</revision>
				<defaultdir>" . $arrayToXml[$x]->defaultdir . "</defaultdir>
				<xdim>" . $arrayToXml[$x]->xdim . "</xdim>
				<ydim>" . $arrayToXml[$x]->ydim . "</ydim>
				<partcolors/>
				<name>" . $arrayToXml[$x]->name . "</name>
				<description>" . $arrayToXml[$x]->description . "</description>
				<adurl/>
				<offerid>" . $arrayToXml[$x]->offerid . "</offerid>
				<buyout>" . $arrayToXml[$x]->buyout . "</buyout>
				<rentofferid>" . $arrayToXml[$x]->rentofferid . "</rentofferid>
				<rentbuyout>" . $arrayToXml[$x]->rentbuyout . "</rentbuyout>
				<bc>" . $arrayToXml[$x]->bc . "</bc>
				<excludeddynamic>" . $arrayToXml[$x]->excludeddynamic . "</excludeddynamic>
				<customparams>" . $arrayToXml[$x]->customparams . "</customparams>
				<specialtype>" . $arrayToXml[$x]->specialtype . "</specialtype>
				<canstandon>" . $arrayToXml[$x]->canstandon . "</canstandon>
				<cansiton>" . $arrayToXml[$x]->cansiton . "</cansiton>
				<canlayon>" . $arrayToXml[$x]->canlayon . "</canlayon>
				<furniline>" . $arrayToXml[$x]->furniline . "</furniline>
				</furnitype>";
				
				
				//Insert into .xml
				
				$openFile = fopen("furnidata_created.xml", "a");
		
				if($openFile == true) {
		
					$insertXml = fwrite($openFile, $generateXml."\n");
					fclose($openFile);
				} else {
		
					$createArchive = fopen("furnidata_created.xml", "w");
					$insertXml = fwrite($createArchive, $generateXml."\n");
					fclose($createArchive);
				}
				
			}

			echo json_encode([
				"response" => true,
				"All swf's download:" => true,
				"Total Download:" => count($pushMobis)
			]);
		} else if($type === "specific") {
			$specific = (isset($_GET['nameSwf'])) ? $_GET['nameSwf'] : '';

			if(isset($_GET['nameSwf']) && !empty($specific)) {
				$nameSWF = $specific; 
				$nameIcon = $specific . "_icon.png";
				if (strpos(file_get_contents($furnidata), $nameSWF)) {

					//making download
						//echo "oi";
				$contentMobis = file_get_contents($urlMobis . $nameSWF . '.swf');
				$contentIcons = file_get_contents($urlMobis_icon . $nameIcon);

				if (is_dir($nameswfFolder) && is_dir($nameIconFolder)) {
					
					//swf's
					
					file_put_contents($nameswfFolder . '/' . $nameSWF . '.swf', $contentMobis);
				

					//icon's mobi
					
					file_put_contents($nameswfFolder . '/' . $nameIconFolder . '/' . $nameIcon, $contentIcons);
					
				} else {
					//create folders if not exists
					mkdir(__DIR__ . '/' . $nameswfFolder . '/', 0777, true);
					mkdir(__DIR__ . '/' . $nameswfFolder . '/' . $nameIconFolder . '/', 0777, true);

					//swf's
					file_put_contents($nameswfFolder . '/' . $nameSWF . '.swf', $contentMobis);
					

					//icon's mobi
					file_put_contents($nameswfFolder . '/' . $nameIconFolder . '/' . $nameIcon, $contentIcons);
					
				}
				

				//end
				echo json_encode([
					"response" => true,
					"NameSWF Downloaded" => $nameSWF
				]);

			} else {
				echo json_encode([
					"response" => false,
					"message" => "This swf not exists in your furnidata."
				]);
			}

			} else {
				echo json_encode([
					"response" => false,
					"message" => "SWF file not specified."
				]);
			}
		} else {
			echo "No download type specified!";
		}
	} else {
		echo "No type was specified!";
	}

	header("Content-Type: application/json; charset=UTF-8");
?>