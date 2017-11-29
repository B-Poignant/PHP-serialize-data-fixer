<?php

namespace UnserializeFixer\Interfaces;

interface iFixer {

	public static function writeLog($message, $description, $level);
	public static function getConfig();
}
