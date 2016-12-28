<?php
require_once("config.php");
require_once("database.php");

echo "<html>" . PHP_EOL;
echo "	<head>" . PHP_EOL;
echo "	  <meta charset='utf-8'>" . PHP_EOL;
echo "	  <title>WikiLinksAnalyser</title>" . PHP_EOL;
echo "	  <style> table, td, th {" . PHP_EOL;
echo "		  border: 1px solid black;" . PHP_EOL;
echo "	  	}" . PHP_EOL;
echo "	  </style>" . PHP_EOL;
echo "	</head>" . PHP_EOL;
echo "	<body>" . PHP_EOL;
echo "	  <form action='extract.php' method='post'>" . PHP_EOL;
echo "  	<b>name of the starting person:</b> <input type='text' name='start_person' value = '" . $start_person . "' /> <br>" . PHP_EOL;
echo "  	<b>number of max. searches:</b> <input type='text' name='max_searches' value = '" . $max_searches . "' /> <br>" . PHP_EOL;
echo "  	<b>name of the Wikipedia-sourcedatabase:</b> <input type='text' name='db_source' value = '" . $db_source . "' /> <br>" . PHP_EOL;
echo "  	<b>name of the results-database:</b> <input type='text' name='db_results' value = '" . $db_results . "' /> <br>" . PHP_EOL;
echo "  	<b>database host:</b> <input type='text' name='database_host' value = '" . $database_host . "' /> <br>" . PHP_EOL;
echo "  	<b>database user:</b> <input type='text' name='database_user' value = '" . $database_user . "' /> <br>" . PHP_EOL;
echo "		<b>database password:</b> <input type='password' name='database_pass' value = '" . $database_pass . "' /> <br>" . PHP_EOL;
echo "  	<input type='Submit' value='Start script' />" . PHP_EOL;
echo "	  </form>" . PHP_EOL;

echo "	  <table>" . PHP_EOL;
echo "		<thead>" . PHP_EOL;
echo "		  <tr>" . PHP_EOL;
echo "			<th>name</th>" . PHP_EOL;
echo "			<th>importance</th>" . PHP_EOL;
echo "			<th>linking</th>" . PHP_EOL;
echo "			<th>quality of linking</th>" . PHP_EOL;
echo "			<th>linked</th>" . PHP_EOL;
echo "			<th>quality of linked</th>" . PHP_EOL;
echo "			<th>depth</th>" . PHP_EOL;
echo "			<th>last updated</th>" . PHP_EOL;
echo "		  </tr>" . PHP_EOL;
echo "		</thead>" . PHP_EOL;
echo "		<tbody>" . PHP_EOL;

$pdo = new PDO('mysql:host=' .$database_host. ';dbname=' . $db_results , $database_user, $database_pass);
$sql = "SELECT * FROM persons";
foreach ($pdo->query($sql) as $person) {
  echo "		  <tr>" . PHP_EOL;
  echo "			<th>" . $person['name'] . "</th>" . PHP_EOL;
  echo "			<th>" . $person['importance'] . "</th>" . PHP_EOL;
  echo "			<th>" . $person['linking'] . "</th>" . PHP_EOL;
  echo "			<th>" . $person['qual_linking'] . "</th>" . PHP_EOL;
  echo "			<th>" . $person['linked'] . "</th>" . PHP_EOL;
  echo "			<th>" . $person['qual_linked'] . "</th>" . PHP_EOL;
  echo "			<th>" . $person['depth'] . "</th>" . PHP_EOL;
  echo "			<th>" . $person['updated'] . "</th>" . PHP_EOL;
  echo "		  </tr>" . PHP_EOL;
}
$pdo = null;
echo "		</tbody>" . PHP_EOL;
echo "	  </table>" . PHP_EOL;
echo "	</body>" . PHP_EOL;
echo "</html>" . PHP_EOL;
?>