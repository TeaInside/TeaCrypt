<?php

for ($i=0; $i < 300; $i++) { 
	$str = "Hello World";
	$key = rstr(rand(4096, 4096*10));
	echo $i." klen ".strlen($key)."\n";
	echo $encrypt = trim(shell_exec(PHP_BINARY." ".__DIR__."/php/main.php encrypt \"{$str}\" \"{$key}\""));
	echo "\n".($xx = trim(shell_exec(PHP_BINARY." ".__DIR__."/php/main.php decrypt \"{$encrypt}\" \"{$key}\"")))."\n";
	echo "\n";
	if ($xx !== $str) {
		exit("invalid");
	}
}
die;

for ($i=1; $i <= 100000; $i++) { 
	$data = rstr($i);
	$key  = rstr(1, 100);
	$v = array_search("-v", $argv)===false;
	print "data length: ".$i."\n";
	$v or print "raw data: ".$data."\n";
	$encrypted = trim(shell_exec(PHP_BINARY." ".__DIR__."/php/main.php encrypt \"{$data}\" \"{$key}\""));
	$v or print "encrypted: ".$encrypted."\n";
	$decrypted = trim(shell_exec(PHP_BINARY." ".__DIR__."/php/main.php decrypt \"{$encrypted}\" \"{$key}\""));
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