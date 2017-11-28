<?php

namespace UnserializeFixer\Exceptions;

class CorruptedException extends \Exception
{
	public function __construct() {

	parent::__construct("'Your string still corrupted :'('", 500);
  }
}