<?php

namespace UnserializeFixer\Exceptions;

class DuplicateIndexException extends \Exception
{
	
	public function __construct($value)
	{
		
		parent::__construct('Duplicate index : ' . $value, 500);
	}
	
}
