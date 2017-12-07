<?php
namespace UnserializeFixer;

use UnserializeFixer\Exceptions;
use UnserializeFixer\Interfaces;


class Config implements Interfaces\iConfig
{
	
	private static $_resolve_method = null;
	private static $_log_enabled = null;
	
	public static function getResolveMethod() :string
	{
		return self::$_resolve_method;
	}
	
	public static function setResolveMethod(string $resolve_method) :void
	{
		if (!in_array($resolve_method, ['complete', 'remove', 'exception'])) {
			throw new Exceptions\InvalidResolveMethodException($resolve_method);
		}
		
		/* if($resolve_method=='remove' || $resolve_method=='exception'){
		  throw new Exceptions\NotImplementedWetException();
		  } */
		
		self::$_resolve_method = $resolve_method;
	}
	
	public static function getLogEnabled() :bool
	{
		return self::$_log_enabled;
	}
	
	public static function setLogEnabled(bool $log_enabled) :void
	{
		
		self::$_log_enabled = $log_enabled;
	}
	
	public function init() :void
	{
		$data = self::getData();
		foreach ($data as $key => $value) {
			$next_call = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
			if (method_exists(__CLASS__, $next_call)) {
				call_user_func_array(array(__CLASS__, $next_call), array($value));
			}
		}
	}
	
	public static function getData() :array
	{
		return ['log_enabled' => false, 'resolve_method' => 'complete'];
	}
	
}
