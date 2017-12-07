<?php

namespace UnserializeFixer\Exceptions;

class InvalidTypeException extends \Exception
{
	
	public function __construct($type)
	{
		
		parent::__construct($type . ' is not a possible type handleLastItemByType', 500);
	}
	
}
