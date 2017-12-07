<?php

class Symfony extends UnserializeFixer\Fixer
{
	public static function writeLog($message, $data, $level = 'info')
	{
		$logger = $this->get('logger');
		
		switch ($level) {
			case 'debug':
				$logger->debug($message, $data);
				break;
			case 'warning':
				$logger->warning($message, $data);
				break;
			case 'error':
				$logger->error($message, $data);
				break;
			default:
			case 'info':
				$logger->info($message, $data);
				break;
		}
		
		$logger->info($message, $data);
	}
}