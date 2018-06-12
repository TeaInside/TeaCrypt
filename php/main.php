<?php

use TeaCrypt\TeaCrypt;

require __DIR__."/TeaCrypt.php";

if ($argv[1] === "encrypt") {
	print TeaCrypt::encrypt($argv[2], $argv[3], 1);
} else if ($argv[1] === "decrypt") {
	print TeaCrypt::decrypt($argv[2], $argv[3], 1);
}
