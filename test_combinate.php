<?php

if (! isset($argv[1], $argv[2])) {
	echo "Parameter required!\n";
	exit(1);
}

$binary = [
	"node" => "/usr/bin/env node js/teacrypt.js",
	"js"  => "/usr/bin/env node js/teacrypt.js",
	"cpp" => "cpp/bin",
	"c++" => "cpp/bin",
	"php" => PHP_BINARY." php/main.php"
];

if (! isset($binary[$argv[1]])) {
	echo "Invalid selection ".$argv[1]."\n";
	exit(1);
}
$encryptor = $binary[$argv[1]];

if (! isset($binary[$argv[2]])) {
	echo "Invalid selection ".$argv[2]."\n";
	exit(1);
}
$decryptor = $binary[$argv[2]];

var_dump($encryptor, $decryptor);

for ($i=1; $i <= 100000; $i++) { 
	$data = rstr($i);
	$key  = rstr(rand(1, 4096 * 10));
	$v = array_search("-v", $argv)===false;
	print "data length: ".$i."; key length: ".strlen($key)."\n";
	$v or print "raw data: ".$data."\n";
	$encrypted = trim(shell_exec($encryptor." encrypt \"{$data}\" \"{$key}\""));
	// var_dump($encrypted);die;
	$v or print "encrypted: ".$encrypted."\n";
	$decrypted = trim(shell_exec($decryptor." decrypt \"{$encrypted}\" \"{$key}\""));
	$v or print "decrypted: ".$decrypted."\n";
	print "is valid: ".($valid = ($data === $decrypted ? "yes" : "no"))."\n\n";
	$v or print "\n";
	if ($valid === "no") {
		exit;
	}
}




/**
 * @param int 	 $n
 * @param string $e
 * @return string
 */
function rstr($n = 32, $e = null)
{
	if (! is_string($e)) {
		$e = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890___";
	}
	$rn = "";
	$ln = strlen($e) - 1;
	for ($i=0; $i < $n; $i++) { 
		$rn .= $e[rand(0, $ln)];
	}
	return $rn;
}