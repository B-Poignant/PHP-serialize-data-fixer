<?php

class Symfony_Fixer extends UnserializeFixer\Fixer{
	public static function writeLog($message, $data, $level = 'info') {
		
		$app['monolog']->info($message,$data);
	}
}