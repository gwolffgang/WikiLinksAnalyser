<?php
require_once("config.php");
require_once("database.php");

echo "<html>" . PHP_EOL;
echo "	<head>" . PHP_EOL;
echo "	  <meta charset='utf-8'>" . PHP_EOL;
echo "	  <title>Aufbau einer Tabelle</title>" . PHP_EOL;
echo "	  <style> table, td, th {" . PHP_EOL;
echo "		  border: 1px solid black;" . PHP_EOL;
echo "	  	}" . PHP_EOL;
echo "	  </style>" . PHP_EOL;
echo "	</head>" . PHP_EOL;

echo "	<body>" . PHP_EOL;

echo "	  <form action='extract.php' method='post'>" . PHP_EOL;
echo "  	<b>name of the starting person:</b> <input type='text' name='start_person' value = '" . $start_person . "' />" . PHP_EOL;
echo "  	<b>number of :</b> <input type='text' name='max_searches' value = '" . $max_searches . "' />" . PHP_EOL;
echo "  	<b>name of the Wikipedia-sourcedatabase:</b> <input type='text' name='db_source' value = '" . $db_source . "' />" . PHP_EOL;
echo "  	<b>name of the results-database:</b> <input type='text' name='db_results' value = '" . $db_results . "' />" . PHP_EOL;
echo "  	<b>database host:</b> <input type='text' name='database_host' value = '" . $database_host . "' />" . PHP_EOL;
echo "  	<b>database user:</b> <input type='text' name='database_user' value = '" . $database_user . "' />" . PHP_EOL;
echo "		<b>database password:</b> <input type='password' name='database_pass' value = '" . $database_pass . "' />" . PHP_EOL;

echo "  	<input type='Submit' value='Start script' />" . PHP_EOL;
echo "	  </form>" . PHP_EOL;

$sql_select = "SELECT * FROM persons";
$results = mysql_query($sql_select, $conn_results);
$array = mysql_fetch_array($results);

echo "	  <table>" . PHP_EOL;
foreach ($array AS $person) {
	echo "		<tr>" . PHP_EOL;
	echo "		  <th>" . PHP_EOL;
	echo "			" . $array['name'] . PHP_EOL;
	echo "		  </th>" . PHP_EOL;
	echo "		  <th>" . PHP_EOL;
	echo "			" . $array['importance'] . PHP_EOL;
	echo "		  </th>" . PHP_EOL;
	echo "		</tr>" . PHP_EOL;
}
echo "	  </table>" . PHP_EOL;

echo "	</body>" . PHP_EOL;
echo "</html>" . PHP_EOL;
?>