<?php

class Symfony_Fixer extends UnserializeFixer\Fixer{
	public static function writeLog($message, $data, $level = 'info') {
		$logger = $this->get('logger');
		$logger->info($message,$data);
	}
}