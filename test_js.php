<?php



for ($i=1; $i <= 100000; $i++) { 
	$data = rstr($i);
	$key  = rstr(1, 100);
	$v = array_search("-v", $argv)===false;
	print "data length: ".$i."\n";
	$v or print "raw data: ".$data."\n";
	$encrypted = trim(shell_exec("/usr/bin/env node ".__DIR__."/js/teacrypt.js encrypt \"{$data}\" \"{$key}\""));
	$v or print "encrypted: ".$encrypted."\n";
	$decrypted = trim(shell_exec("/usr/bin/env node ".__DIR__."/js/teacrypt.js decrypt \"{$encrypted}\" \"{$key}\""));
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
