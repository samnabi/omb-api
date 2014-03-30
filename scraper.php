<?php

// This file scrapes the OMB website and mirrors the information to a database.
// Run this file via cron job every day or so.

include_once('lib/config.php');
include_once('lib/functions.php');

error_reporting(E_ALL);

echo 'Building list of municipality URLs... ';
foreach(getMunis() as $muni) $munis_list[] = $muni['href'];
echo 'Done.'."\n";

echo 'Building list of all OMB cases (this could take a while!)...'."\n";
$cases = getCases($munis_list);
// cases_callback() will output a running total of the number of cases
echo 'Done.'."\n";

echo 'Writing results to DB... ';

// Connect to DB
if(!is_dir('db')) mkdir('db', 0777);
$db = new PDO('sqlite:db/'.$db_filename);
chmod('db/'.$db_filename, 0777);
$db->exec('DROP TABLE cases');
$db->exec('CREATE TABLE IF NOT EXISTS cases (id TEXT, muni TEXT, status TEXT, description TEXT, address TEXT)');

// Write to DB
foreach ($cases as $case) {
	$db->exec('INSERT INTO cases (id, muni, status, description, address) VALUES ("'.$case['id'].'", "'.$case['muni'].'", "'.$case['status'].'", "'.$case['description'].'", "'.$case['address'].'")');
}

echo 'Done'."\n";

?>