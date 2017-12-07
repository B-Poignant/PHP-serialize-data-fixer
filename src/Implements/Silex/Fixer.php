<?php

class Fixer extends UnserializeFixer\Fixer
{
	//https://silex.symfony.com/doc/2.0/providers/monolog.html
	public static function writeLog($message, $data, $level = 'info')
	{
		
		switch ($level) {
			case 'debug':
				$app['monolog']->debug($message, $data);
				break;
			case 'warning':
				$app['monolog']->warning($message, $data);
				break;
			case 'error':
				$app['monolog']->error($message, $data);
				break;
			default:
			case 'info':
				$app['monolog']->info($message, $data);
				break;
		}
	}
}