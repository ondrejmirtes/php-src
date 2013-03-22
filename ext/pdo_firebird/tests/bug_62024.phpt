--TEST--
Bug #62024 Cannot insert second row with null using parametrized query (Firebird PDO)
--SKIPIF--
<?php extension_loaded("pdo_firebird") or die("skip"); ?>
<?php function_exists("ibase_query") or die("skip"); ?>
--FILE--
<?php

require("testdb.inc");

$dbh = new PDO("firebird:dbname=$test_base",$user,$password) or die;
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
$value = '2';
@$dbh->exec('DROP TABLE test_insert');
$dbh->exec("CREATE TABLE test_insert (ID INTEGER NOT NULL, TEXT VARCHAR(10))");

$dbh->commit();

//start actual test

$sql = "insert into test_insert (id, text) values (?, ?)";
$sttmt = $dbh->prepare($sql);

$args_ok = [1, "test1"];
$args_err = [2, null];

$res = $sttmt->execute($args_ok);
var_dump($res);

$res = $sttmt->execute($args_err);
var_dump($res);

$dbh->commit();


//teardown test data
$sttmt = $dbh->prepare('DELETE FROM test_insert');
$sttmt->execute();

$dbh->commit();

$dbh->exec('DROP TABLE test_insert');

unset($sttmt);
unset($dbh);

?>
--EXPECT--
bool(true)
bool(true)

