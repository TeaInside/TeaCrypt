<?php

namespace TeaCrypt;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 * @link https://github.com/ammarfaizi2
 * @license MIT
 * @version 0.0.1
 * @package \TeaCrypt
 */
final class TeaCrypt
{
	/**
	 * Encrypt method.
	 *
	 * @param string $string
	 * @param string $key
	 * @param bool	 $binarySafe
	 * @return string
	 */
	final public static function encrypt($string, $key, $binarySafe = true)
	{
		$slen = strlen($string);
		$klen = strlen($key);
		$r = $newKey = "";
		$salt = self::saltGenerator();
		$cost = 1;
		for($i=$j=0;$i<$klen;$i++) {
			$newKey .= chr(ord($key[$i]) ^ ord($salt[$j++]));
			if ($j === 5) {
				$j = 0;
			}
		}	
		for($i=$j=$k=0;$i<$slen;$i++) {
			$r .= chr(
				ord($string[$i]) ^ ord($newKey[$j++]) ^ ord($salt[$k++]) ^ ($i << $j) ^ ($k >> $j) ^
				($slen % $cost) ^ ($cost >> $j) ^ ($cost >> $i) ^ ($cost >> $k) ^
				($cost ^ ($slen % ($i + $j + $k + 1))) ^ (($cost << $i) % 2) ^ (($cost << $j) % 2) ^ 
				(($cost << $k) % 2) ^ (($cost * ($i+$j+$k)) % 3)
			);
			$cost++;
			if ($j === $klen) {
				$j = 0;
			}
			if ($k === 5) {
				$k = 0;
			}
		}
		$r .= $salt;
		if ($binarySafe) {
			return strrev(base64_encode($r));
		} else {
			return $r;
		}
	}



	/**
	 * Decrypt method.
	 *
	 * @param string $string
	 * @param string $key
	 * @param bool	 $binarySafe
	 * @return string
	 */
	final public static function decrypt($string, $key, $binarySafe = true)
	{
		if ($binarySafe) {
			$string = base64_decode(strrev($string));
		}
		$slen = strlen($string);
		$salt = substr($string, $slen - 5);
		$string = substr($string, 0, ($slen = $slen - 5));
		$klen = strlen($key);
		$newKey = $r = "";
		$cost = 1;
		for($i=$j=0;$i<$klen;$i++) {
			$newKey .= chr(ord($key[$i]) ^ ord($salt[$j++]));
			if ($j === 5) {
				$j = 0;
			}
		}
		for($i=$j=$k=0;$i<$slen;$i++) {
			$r .= chr(
				ord($string[$i]) ^ ord($newKey[$j++]) ^ ord($salt[$k++]) ^ ($i << $j) ^ ($k >> $j) ^
				($slen % $cost) ^ ($cost >> $j) ^ ($cost >> $i) ^ ($cost >> $k) ^
				($cost ^ ($slen % ($i + $j + $k + 1))) ^ (($cost << $i) % 2) ^ (($cost << $j) % 2) ^ 
				(($cost << $k) % 2) ^ (($cost * ($i+$j+$k)) % 3)
			);
			$cost++;
			if ($j === $klen) {
				$j = 0;
			}
			if ($k === 5) {
				$k = 0;
			}
		}
		return $r;
	}

	/**
	 * @param int $n
	 * @return string
	 */
	final public static function saltGenerator($n = 5)
	{
		$s = range(chr(1), chr(0x7f));
		$r = ""; $c=count($s)-1;
		for($i=0;$i<$n;$i++) {
			$r.= $s[rand(0, $c)];
		}
		return $r;
	}
}