<?php
function getWikiId($target) {
	global $conn_TimeWiki, $conn_Wikipedia;
	
	$sql_id = "SELECT page_latest FROM page WHERE page_title = '" .$target. "'";
	$results = mysql_query($sql_id, $conn_Wikipedia);

	if($results !== false) {
		$array = mysql_fetch_array($results);
		$latest = $array['page_latest'];
		return $latest;
	}
}

function getTimeId($target) {
	global $conn_TimeWiki, $conn_Wikipedia;
	
	$sql_id = "SELECT id FROM persons WHERE name = '" .$target. "'";
	$results = mysql_query($sql_id, $conn_TimeWiki);

	if($results !== false) {
		$array = mysql_fetch_array($results);
		$id = $array['id'];
		return $id;
	}
}

function getDepth($target) {
	global $conn_TimeWiki, $conn_Wikipedia;
	
	$sql_select = "SELECT depth FROM persons WHERE wiki_id = " .$target;
	$results = mysql_query($sql_select, $conn_TimeWiki);

	if($results !== false) {
		$array = mysql_fetch_array($results);
		$depth = $array['depth'];
		return $depth;
	}
}

function getChildren($parent_name, $parent_depth) {
	global $conn_TimeWiki, $conn_Wikipedia;

	$sql_id = "SELECT id FROM persons WHERE name = '" . $parent_name . "'";
	$results = mysql_query($sql_id, $conn_TimeWiki);
	$array = mysql_fetch_array($results);
	$parent_id = $array['id'];

	if ($parent_id > 0) {
		$parent_quality = pow(10,-($parent_depth-1));	
		$sql_ids = "SELECT link_to FROM links WHERE link_from = ". $parent_id ." and quality = ". $parent_quality;
		$results = mysql_query($sql_ids, $conn_TimeWiki);
		while($row = mysql_fetch_array($results)) {
  			$IDs[] = $row['link_to'];  
		}
		$names = array();
		if (count($IDs) > 0) {
			$uniqueIDs = array_unique($IDs);
			foreach ($uniqueIDs as $ID){
				$sql_name = "SELECT name FROM persons WHERE id = " . $ID;
				$result = mysql_query($sql_name, $conn_TimeWiki);
				$array = mysql_fetch_array($result);
				$names[] = $array['name'];
			}
		}
		return($names);
	}
}

function getPage($id) {
	global $conn_TimeWiki, $conn_Wikipedia;

	$sql_text = "SELECT old_text FROM text WHERE old_id = $id";
	$results = mysql_query($sql_text, $conn_Wikipedia);

	if ($results !== false) {
		$array = mysql_fetch_array($results);
		return($array['old_text']);
	}
}

function find_candidates($source, $person_name) {
	global $conn_TimeWiki, $conn_Wikipedia;

	$candidates = array();	
	$before = strpos($source, "[[");

	while($before !== false) {
		$after = strpos($source, "]]", $before);
		$candidate = substr($source, $before +2, $after - $before -2);

		if ($candidate !== "Digital_Object_Identifier"){
		
			if ($candidate !== $person_name){

				if (strpos($candidate, ":") === false){

					if (strpos($candidate, ".") === false){
						$pipe = strpos($candidate, "|");

						if(is_numeric($pipe)){
							$candidate = substr($candidate, $pipe +1);
						}

						if (ctype_digit($candidate) === false){
							$candidates[] = $candidate;
						}	
					}
				}
			}
		}																				
		$before = strpos($source,"[[", $after);
	}
	$uniqueCandidates = array_unique($candidates);
	return $uniqueCandidates;
}

function check_candidates($candidates) {
	global $conn_TimeWiki, $conn_Wikipedia;
	
	$persons = array();

	foreach ($candidates as $candidate) {
		$candidate = str_replace(" ", "_", $candidate);
		$check_id = getWikiId($candidate);
		
		if ($check_id !== false) {
		/*	$sql_in_DB = "SELECT wiki_id FROM persons WHERE wiki_id = $check_id";
			$result = mysql_query($sql_in_DB, $conn_TimeWiki);
			if ($result === false) { */
				$sql_check = "SELECT old_text FROM text WHERE old_id = $check_id";
				$check_text = mysql_query($sql_check, $conn_Wikipedia);

				if ($check_text !== false) {
					$testsource = mysql_fetch_array($check_text)['old_text'];

					if (strrpos($testsource,"Kategorie:Geboren"))
						$persons[] = $candidate;
				}
		/*	}						
			else { 
				$persons[] = $candidate;
			} */
		}
	}
	sort($persons);					
	return($persons);
}

function display_persons($persons) {
	global $conn_TimeWiki, $conn_Wikipedia;

	if (count($persons) == 0){
		echo "Keine Links gefunden.";
	} else {
		foreach ($persons as $person) echo $person . ', ';
	}

	echo 'Anzahl gefundener Personen: '.count($persons);									// Ausgabe der Anzahl gefundener Personen
}
	
function getLinks($source, $person_name) {
	$candidates = find_candidates($source, $person_name);									// Funktionsaufruf "find_candidates"
	$persons = check_candidates($candidates);												// Funktionsaufruf "check_candidates"
	return($persons);																		// RŸckgabe der Links
}

function get_ID($person_name) {
	global $conn_TimeWiki;
		
	$sql_id = "SELECT id FROM persons WHERE name = '" . $person_name . "'";
	$results = mysql_query($sql_id, $conn_TimeWiki);
	$array = mysql_fetch_array($results);
	$person_id = $array['id'];
	$person_WikiID = getWikiID($person_name);
	if (!($person_id > 0)) 
		$person_id = save_Person($person_WikiID, $person_name);
	return($person_id);
}

function save_Person($person_WikiID, $person_name) {
	global $conn_TimeWiki;

	$sql_insert = "INSERT INTO persons (wiki_id, name, importance, linking, qual_linking, linked, qual_linked, depth)
								VALUES (" . $person_WikiID . ", '" . $person_name . "', 0, 0, 0, 0, 0, 0)";
	$result = mysql_query($sql_insert, $conn_TimeWiki);
	
	$sql_id = 'SELECT id FROM persons WHERE wiki_id = "' . $person_WikiID . '"';
	$result = mysql_query($sql_id, $conn_TimeWiki);
	$array = mysql_fetch_array($result);
	$person_id = $array['id'];
	return($person_id);
}

function from_to($from, $to, $father, $quality) {
	global $conn_TimeWiki;
	$sql_select = "SELECT over FROM links WHERE link_from = " .$from. " AND link_to = " .$father;
	$results = mysql_query($sql_select, $conn_TimeWiki);
	$array = mysql_fetch_array($results);
	$over = $array['over'];
	if($over === NULL)
		$over = $from;
	
	$sql_linking = 'INSERT INTO links (link_from, link_to, quality, over) VALUES(' .$from. ', ' .$to. ', ' .$quality. ', "' .$over. '-' .$to. '")';
	mysql_query($sql_linking, $conn_TimeWiki);
}

function evaluate_person($id) {
	global $conn_TimeWiki;
	
	$importance = 0.0;
	$quality_linking = 0.0;
	$sum_linking = 0.0;
	$count_linking = 0.0;
	$sql_read_linking = 'SELECT quality FROM links WHERE link_from = ' . $id;
	$results = mysql_query($sql_read_linking, $conn_TimeWiki);
	if ($results != FALSE) {
		while($row = mysql_fetch_array($results)) {
  			$quality_linking += $row['quality'];
  			$sum_linking++;
			if ($row['quality'] == 1) {
				$count_linking++;
			}
		}
		$importance += $quality_linking;
	}
	$quality_linked = 0.0;
	$sum_linked = 0.0;
	$count_linked = 0.0;
	$sql_read_linked = 'SELECT quality FROM links WHERE link_to = ' . $id;
	$results = mysql_query($sql_read_linked, $conn_TimeWiki);
	if ($results != FALSE)
		while($row = mysql_fetch_array($results)) {
  			$quality_linked += $row['quality'];
			$count_linked++;
		}
		$importance += $quality_linked;
	// echo $importance ."=". $quality_linking ."+". $quality_linked . PHP_EOL;
	
	$sql_write_linking = 'UPDATE persons SET importance = ' .$importance. ', '
											.'linking = ' .$count_linking. ', '
											.'qual_linking = ' .$quality_linking. ', '
											.'linked = ' .$count_linked. ', '
											.'qual_linked = ' .$quality_linked 
											.' WHERE id = ' .$id;
	mysql_query($sql_write_linking, $conn_TimeWiki);
}

function saveLinks($links, $person_name, $act_quality) {
	$from_id = get_ID($person_name);
	
	foreach ($links as $link) {
		$to_id = get_ID($link);
		from_to($from_id, $to_id, $from_id, $act_quality);
		evaluate_person($to_id);
	}
	evaluate_person($from_id);
}

function exist_in_db($name) {
	global $conn_TimeWiki, $conn_Wikipedia;
	
	$name = str_replace(" ", "_", $name);
	$sql_id = 'SELECT COUNT(*) as number FROM persons WHERE name = "' . $name . '"';
	$result = mysql_query($sql_id, $conn_TimeWiki);
	$number = mysql_result($result, 0);
	$existsInDb = $number > 0;

	return $existsInDb;
}

function new_act_person() {
	global $conn_TimeWiki, $conn_Wikipedia;
	
	$sql_name = 'SELECT name FROM persons ORDER BY depth ASC';
	$results = mysql_query($sql_name, $conn_TimeWiki);
	$array = mysql_fetch_array($results);
	$person_name = $array['name'];
	return($person_name);
}

function deeper($target){
	global $conn_TimeWiki, $conn_Wikipedia;

	$sql_read = 'SELECT depth FROM persons WHERE name = "' . $target . '"';
	$result = mysql_query($sql_read, $conn_TimeWiki);
	$array = mysql_fetch_array($result);
	$newdepth = $array['depth']+1;

	$sql_write = 'UPDATE persons SET depth= ' . $newdepth . ' WHERE name = "' . $target . '"';
	mysql_query($sql_write, $conn_TimeWiki);
}
?>