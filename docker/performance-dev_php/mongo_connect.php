<?php
$mng = new MongoDB\Driver\Manager("mongodb://mongodb:27017");
$bulk = new MongoDB\Driver\BulkWrite;
$filter = ['url' => 'www.google.com', 'date' => 'today'];

$ins = [
    '_id' => new MongoDB\BSON\ObjectID,
    'url' => 'www.google.com',
    'date' => 'today',
    'type' => 'fun' // Assuming $type is a variable containing the desired value
];
var_dump($ins);
$bulk->insert($ins);
var_dump($mng->executeBulkWrite('pageperformance-dev.cache', $bulk));

$query = new MongoDB\Driver\Query($filter);
$res = $mng->executeQuery('pageperformance-dev.cache', $query);
$result = current($res->toArray());
var_dump($result);
?>
