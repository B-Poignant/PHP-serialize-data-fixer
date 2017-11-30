<?php

use UnserializeFixer\Interfaces;
use UnserializeFixer\Exceptions;

namespace UnserializeFixer;

class Config implements Interfaces\iConfig {

	private static $_resolve_method	 = null;
	private static $_log_enabled	 = null;

	public function init() {
		$data = self::getData();

		foreach ($data as $key => $value)
		{
			$next_call = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
			if (method_exists(__CLASS__, $next_call))
			{
				call_user_func_array(array(__CLASS__, $next_call), array($value));
			}
		}
	}

	public static function getData() {
		return ['log_enabled' => false, 'resolve_method' => 'complete'];
	}

	public static function setResolveMethod($resolve_method) {
		if (!in_array($resolve_method, ['complete', 'remove']))
		{
			throw new Exceptions\InvalidResolveMethodException($resolve_method);
		}

		if($resolve_method=='remove'){
			throw new Exceptions\NotImplementedWetException();
		}
		
		self::$_resolve_method = $resolve_method;
	}

	public static function getResolveMethod() {
		return self::$_resolve_method;
	}

	public static function setLogEnabled(bool $log_enabled) {

		self::$_log_enabled = $log_enabled;
	}

	public static function getLogEnabled() {
		return self::$_log_enabled;
	}

}
