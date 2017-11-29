<?php

namespace UnserializeFixer\Exceptions;

class InvalidResolveMethodException extends \Exception {

	public function __construct($method) {

		parent::__construct($method." is not a valid resolve method", 500);
	}

}
