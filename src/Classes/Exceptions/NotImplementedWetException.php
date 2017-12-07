<?php

namespace UnserializeFixer\Exceptions;

class NotImplementedWetException extends \Exception
{
	
	public function __construct()
	{
		
		parent::__construct("'Not Implemented Wet", 500);
	}
	
}
