#!/usr/bin/php
<?php

$num_rows = 50;
$species_names = explode("\n", file_get_contents("mammals.txt"));
$uuids = array();

$dbconn = pg_connect("host=localhost port=5432 dbname=carnivorae user=ubuntu password=orangered");
if (!$dbconn) {
  echo "ERROR: Could not connect to database\n";
  exit;
}

pg_query($dbconn, "DROP TABLE IF EXISTS critters");
pg_query($dbconn, "CREATE TABLE critters (id integer PRIMARY KEY, species varchar(50), uuid varchar(50), foo numeric(5, 4), link1 varchar(50), link2 varchar(50))");

for ($i = 0; $i < $num_rows; $i++) {
  $species = $species_names[rand(0, count($species_names)-1)];
  $uuid = uniqid("", true);
  $foo = floatval(rand(0, 99999)) / 10000;
  pg_query($dbconn, "INSERT INTO critters VALUES($i, '$species', '$uuid', $foo, NULL, NULL)");
  $uuids[$i] = $uuid;
}

for ($i = 0; $i < $num_rows; $i++) {
  do {
    $id1 = rand(0, $num_rows - 1);
    $id2 = rand(0, $num_rows - 1);
  } while (($id1 == $i) || ($id2 == $i) || ($id1 == $id2));
  pg_query($dbconn, "UPDATE critters SET link1='{$uuids[$id1]}', link2='{$uuids[$id2]}' WHERE id=$i");
}

?>