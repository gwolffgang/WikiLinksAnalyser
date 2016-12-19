<?php
require_once("config.php");

echo "<form action='extract.php' method='post'>" . PHP_EOL;
echo "  <b>name of the starting person:</b> <input type='text' name='start_person' value = '" . $start_person . "' />" . PHP_EOL;
echo "  <b>number of :</b> <input type='text' name='max_searches' value = '" . $max_searches . "' />" . PHP_EOL;
echo "  <b>name of the Wikipedia-sourcedatabase:</b> <input type='text' name='db_source' value = '" . $db_source . "' />" . PHP_EOL;
echo "  <b>name of the results-database:</b> <input type='text' name='db_results' value = '" . $db_results . "' />" . PHP_EOL;
echo "  <b>database host:</b> <input type='text' name='database_host' value = '" . $database_host . "' />" . PHP_EOL;
echo "  <b>database user:</b> <input type='text' name='database_user' value = '" . $database_user . "' />" . PHP_EOL;
echo "  <b>database password:</b> <input type='password' name='database_pass' value = '" . $database_pass . "' />" . PHP_EOL;

echo "  <input type='Submit' value='Start script' />" . PHP_EOL;
echo "</form>";
?>