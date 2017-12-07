<?php

namespace UnserializeFixer\Interfaces;

interface iFixer
{
	
	public static function writeLog(string $message, $description, string $level) :void;
	
	public static function getConfig();
}
