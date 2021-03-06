<?php
require_once("config.php");
require_once("database.php");
require_once("functions.php");

$start_time = time();
if(isset($_POST["database_host"]) && $_POST["database_host"] != "")
	$database_user = htmlspecialchars($_POST["database_host"]);
if(isset($_POST["database_user"]) && $_POST["database_user"] != "")
	$database_user = htmlspecialchars($_POST["database_user"]);
if(isset($_POST["database_pass"]) && $_POST["database_pass"] != "")
	$database_user = htmlspecialchars($_POST["database_pass"]);
if(isset($_POST["db_source"]) && $_POST["db_source"] != "")
	$db_source = htmlspecialchars($_POST["db_source"]);
if(isset($_POST["db_results"]) && $_POST["db_results"] != "")
	$db_results = htmlspecialchars($_POST["db_results"]);
if(isset($_POST["start_person"]) && $_POST["start_person"] != "")
	$start_person = htmlspecialchars($_POST["start_person"]);
if(isset($_POST["max_searches"]) && $_POST["max_searches"] != "")
	if(is_numeric($_POST["max_searches"]) && $max_searches > 0)
		$max_searches = $_POST["max_searches"];
	else
		exit;
	
if(exist_in_db($start_person)){
	$act_person = new_act_person();
} else {
	$act_person = $start_person;
}
$act_person = str_replace(" ", "_", $act_person);
for ($i = 0; $i < $max_searches; $i++){
	echo $i+1 . ".: " . $act_person . "\n";
	if ($print) echo "Bestimme Wikipedia-ID von ". $act_person .". ";
	$wiki_id = getWikiId($act_person);
	if ($print) echo " ID: ". $wiki_id ."\n";
	if ($print) echo "Bestimme TimeWiki-ID von ". $act_person .". ";
	$time_id = getTimeId($act_person);
	if ($print) echo " ID: ". $time_id ."\n";
	if (is_numeric($wiki_id)) {
		$act_person_depth = getDepth($wiki_id);
		$act_quality = pow(10,-$act_person_depth);
		
		if ($act_person_depth > 0) {
	//		if ($act_person_depth > 1)
	//			exit;
			echo "Erstelle Links ". ($act_person_depth+1) ."ten Grades von ". $act_person . PHP_EOL;
				
			$children = getChildren($act_person, $act_person_depth);
			if (count($children) > 0) {
				$from_id = get_ID($act_person);
				foreach ($children as $child){
					if ($print) echo "Speichere Child-Links von " .$child. PHP_EOL;
					$grandchildren = getChildren($child, $act_person_depth);
					$grandchildren = array_diff($grandchildren, $children);
					foreach ($grandchildren as $grandchild) {
						$to_id = get_ID($grandchild);
						$father_id = get_ID($child);
						from_to($from_id, $to_id, $father_id, $act_quality);
					}
				}
				evaluate_person(get_ID($act_person));
				if ($print) echo " Links eingetragen." . PHP_EOL;
			} else
				if ($print) echo " Keine weiteren Links gefunden." . PHP_EOL;
		} else {
			if ($print) echo "Lade Wikipedia-Text von ". $act_person ."." . PHP_EOL;
			$source = getPage($wiki_id);
			if ($source) {
				if ($print) echo " Text geladen." . PHP_EOL;
				if ($print) echo "Suche Links von ". $act_person ."." . PHP_EOL;		
				$links = getLinks($source, $act_person);
				if ($links) {
					if ($print) echo " ". count($links) ." Links gefunden:" . PHP_EOL;
					if ($print) foreach ($links as $link) echo $link . PHP_EOL;
					if ($print) echo "Checke und sichere Links von " .$act_person."." . PHP_EOL;
					if (count($links) > 0){
						saveLinks($links, $act_person, $act_quality);
						if ($print) echo " Links eingetragen." . PHP_EOL;
					} else {
						if ($print) echo " Keine Links auf andere Personen bei " .$act_person." gefunden." . PHP_EOL;
					}
				} else {
					if ($print) echo " Keine Links bei " .$act_person." gefunden." . PHP_EOL;
				}
			} else {
				if ($print) echo " Keinen Text gefunden." . PHP_EOL;
			}
		}	
	}
	deeper($act_person);
	$act_person = new_act_person();
}
echo "  Bearbeitungszeit: ".(time()-$start_time)." Sekunde(n)" . PHP_EOL;
?>